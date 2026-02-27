<?php

if (!defined('ABSPATH')) {
    exit;
}

// ---------------------------------------------------------------------------
// 4. REST API ENDPOINT
// ---------------------------------------------------------------------------

add_action('rest_api_init', 'ccspro_register_rest_routes');
add_filter('rest_post_dispatch', 'ccspro_rest_no_cache_headers', 10, 3);

function ccspro_rest_no_cache_headers($response, $server, $request) {
    if (strpos($request->get_route(), '/ccspro/v1/') === 0) {
        $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->header('Pragma', 'no-cache');
    }
    return $response;
}

function ccspro_register_rest_routes() {
    register_rest_route('ccspro/v1', '/site-config', array(
        'methods' => 'GET',
        'callback' => 'ccspro_rest_get_site_config',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('ccspro/v1', '/landing-page/(?P<slug>[a-z0-9\-]+)', array(
        'methods' => 'GET',
        'callback' => 'ccspro_rest_get_landing_page',
        'permission_callback' => '__return_true',
        'args' => array(
            'slug' => array(
                'required' => true,
                'validate_callback' => function ($param) {
                    return is_string($param) && preg_match('/^[a-z0-9\-]+$/', $param);
                },
            ),
        ),
    ));
    register_rest_route('ccspro/v1', '/menus', array(
        'methods' => 'GET',
        'callback' => 'ccspro_rest_get_menus',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('ccspro/v1', '/page/pricing', array(
        'methods' => 'GET',
        'callback' => 'ccspro_rest_get_pricing_page',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('ccspro/v1', '/page/about', array(
        'methods' => 'GET',
        'callback' => 'ccspro_rest_get_about_page',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('ccspro/v1', '/page/contact', array(
        'methods' => 'GET',
        'callback' => 'ccspro_rest_get_contact_page',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('ccspro/v1', '/contact/submit', array(
        'methods'             => 'POST',
        'callback'            => 'ccspro_rest_submit_contact',
        'permission_callback' => '__return_true',
        'args'                => array(
            'name'    => array('required' => true,  'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'),
            'email'   => array('required' => true,  'type' => 'string', 'sanitize_callback' => 'sanitize_email', 'validate_callback' => 'is_email'),
            'role'    => array('required' => true,  'type' => 'string', 'sanitize_callback' => 'sanitize_text_field'),
            'message' => array('required' => true,  'type' => 'string', 'sanitize_callback' => 'sanitize_textarea_field'),
            '_hp'     => array('required' => false, 'type' => 'string', 'default' => ''),
        ),
    ));
}

function ccspro_rest_submit_contact($request) {
    // Honeypot check — bots auto-fill this; humans leave it empty
    if (!empty($request->get_param('_hp'))) {
        return rest_ensure_response(array('success' => true));
    }

    // Rate limiting — max 3 submissions per IP per 15 minutes
    $ip         = sanitize_text_field(
        !empty($_SERVER['HTTP_X_FORWARDED_FOR'])
            ? explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]
            : ($_SERVER['REMOTE_ADDR'] ?? 'unknown')
    );
    $rate_key   = 'ccspro_contact_rate_' . md5($ip);
    $rate_count = (int) get_transient($rate_key);

    if ($rate_count >= 3) {
        return new WP_Error(
            'rate_limit',
            'Too many submissions. Please wait before trying again.',
            array('status' => 429)
        );
    }

    set_transient($rate_key, $rate_count + 1, 15 * MINUTE_IN_SECONDS);

    $name    = $request->get_param('name');
    $email   = $request->get_param('email');
    $role    = $request->get_param('role');
    $message = $request->get_param('message');

    if (mb_strlen($message) > 5000) {
        return new WP_Error('message_too_long', 'Message must be 5000 characters or fewer.', array('status' => 400));
    }

    // Store submission as CPT entry
    $post_title = $name . ' — ' . date('Y-m-d H:i');
    $post_id    = wp_insert_post(array(
        'post_type'   => 'contact_submission',
        'post_title'  => $post_title,
        'post_status' => 'publish',
    ), true);

    if (is_wp_error($post_id)) {
        return new WP_Error('db_error', 'Could not save submission.', array('status' => 500));
    }

    update_post_meta($post_id, '_ccspro_name',    $name);
    update_post_meta($post_id, '_ccspro_email',   $email);
    update_post_meta($post_id, '_ccspro_role',    $role);
    update_post_meta($post_id, '_ccspro_message', $message);
    update_post_meta($post_id, '_ccspro_ip',      $ip);
    update_post_meta($post_id, '_ccspro_read',    '');

    // Send notification email
    $to      = get_option('ccspro_contact_email', 'harsh@focusdesignconsulting.com');
    $subject = 'New Contact: ' . $name . ' (' . $role . ')';
    $body    = '<html><body>' .
               '<h2>New contact form submission</h2>' .
               '<p><strong>Name:</strong> ' . esc_html($name)    . '</p>' .
               '<p><strong>Email:</strong> ' . esc_html($email)  . '</p>' .
               '<p><strong>Role:</strong> '  . esc_html($role)   . '</p>' .
               '<p><strong>Message:</strong><br>' . nl2br(esc_html($message)) . '</p>' .
               '<hr><p style="color:#888;font-size:12px;">Submitted from IP: ' . esc_html($ip) . '</p>' .
               '</body></html>';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($to, $subject, $body, $headers);

    // CRM integration hook — attach handlers in a separate plugin/snippet
    do_action('ccspro_contact_submitted', $post_id, array(
        'name'    => $name,
        'email'   => $email,
        'role'    => $role,
        'message' => $message,
        'ip'      => $ip,
    ));

    return rest_ensure_response(array('success' => true));
}

function ccspro_rest_get_site_config($request) {
    $coming_soon = get_option('ccspro_coming_soon', '0') === '1';

    if (!function_exists('get_field')) {
        return rest_ensure_response(array('comingSoon' => $coming_soon));
    }

    $site_logo = get_field('site_logo', 'option');
    $logo_url = null;
    if (is_array($site_logo) && isset($site_logo['url']) && $site_logo['url'] !== '') {
        $logo_url = $site_logo['url'];
    }

    $trust_badges = get_field('footer_trust_badges', 'option') ?: array();
    $trust_badges = array_values(array_filter(array_map(function ($row) {
        $icon = isset($row['icon']) ? $row['icon'] : '';
        $text = isset($row['text']) ? $row['text'] : '';
        if ($icon === '' && $text === '') {
            return null;
        }
        return array(
            'icon' => $icon,
            'text' => $text,
        );
    }, $trust_badges)));

    return rest_ensure_response(array(
        'comingSoon' => $coming_soon,
        'header' => array(
            'logoUrl' => $logo_url,
            'logoText' => get_field('site_logo_text', 'option') ?: 'CCS Pro',
            'ctaButton' => array(
                'label' => get_field('header_cta_label', 'option') ?: 'Get Started',
                'href' => get_field('header_cta_href', 'option') ?: '#',
            ),
            'signinLink' => array(
                'label' => get_field('header_signin_label', 'option') ?: 'Sign In',
                'href' => get_field('header_signin_href', 'option') ?: '#',
            ),
        ),
        'footer' => array(
            'brandName' => get_field('footer_brand_name', 'option') ?: 'CCS Pro',
            'tagline' => get_field('footer_tagline', 'option') ?: 'Credentialing packets. Done once. Ready always.',
            'trustBadges' => $trust_badges,
            'copyright' => get_field('footer_copyright', 'option') ?: '© 2025 CCS Pro. All rights reserved.',
        ),
    ));
}

function ccspro_get_menu_links_by_location($location) {
    $locations = get_nav_menu_locations();
    if (!is_array($locations) || !isset($locations[$location])) {
        return array();
    }

    $menu_items = wp_get_nav_menu_items($locations[$location]);
    if (!is_array($menu_items)) {
        return array();
    }

    $links = array();
    foreach ($menu_items as $item) {
        $links[] = array(
            'label' => isset($item->title) ? $item->title : '',
            'href' => isset($item->url) ? $item->url : '#',
            'openInNewTab' => isset($item->target) && $item->target === '_blank',
        );
    }

    return $links;
}

function ccspro_rest_get_menus($request) {
    return rest_ensure_response(array(
        'primaryNav' => ccspro_get_menu_links_by_location('ccspro-primary-nav'),
        'footerCol1' => ccspro_get_menu_links_by_location('ccspro-footer-col1'),
        'footerCol2' => ccspro_get_menu_links_by_location('ccspro-footer-col2'),
        'footerCol3' => ccspro_get_menu_links_by_location('ccspro-footer-col3'),
    ));
}

function ccspro_rest_get_landing_page($request) {
    $slug = $request->get_param('slug');

    $posts = get_posts(array(
        'post_type' => 'landing_page',
        'name' => $slug,
        'post_status' => 'publish',
        'posts_per_page' => 1,
    ));

    if (empty($posts)) {
        return new WP_Error('not_found', 'Landing page not found', array('status' => 404));
    }

    $post = $posts[0];
    $post_id = $post->ID;

    if (!function_exists('get_field')) {
        return new WP_Error('acf_missing', 'ACF is required', array('status' => 500));
    }

    $data = ccspro_transform_landing_page_to_frontend($post_id);
    return rest_ensure_response($data);
}

/**
 * Transform ACF fields to match frontend TypeScript structure (landing.ts).
 */
function ccspro_transform_landing_page_to_frontend($post_id) {
    $g = function ($name, $default = '') {
        $v = get_field($name, $post_id);
        return $v !== false && $v !== null && $v !== '' ? $v : $default;
    };

    $nav_links = get_field('nav_links', $post_id) ?: array();
    $nav_links = array_map(function ($row) {
        return array('label' => isset($row['label']) ? $row['label'] : '', 'href' => isset($row['href']) ? $row['href'] : '#');
    }, $nav_links);

    $hero_trust = get_field('hero_trust_indicators', $post_id) ?: array();
    $hero_trust = array_map(function ($row) {
        return array('icon' => isset($row['icon']) ? $row['icon'] : 'Circle', 'text' => isset($row['text']) ? $row['text'] : '');
    }, $hero_trust);

    $hero_docs = get_field('hero_dashboard_documents', $post_id) ?: array();
    $hero_docs = array_map(function ($row) {
        return array(
            'name' => isset($row['name']) ? $row['name'] : '',
            'status' => isset($row['status']) ? $row['status'] : '',
            'statusColor' => isset($row['status_color']) ? $row['status_color'] : 'gray',
        );
    }, $hero_docs);

    $readiness_states = array(
        array('label' => 'Missing', 'color' => 'red'),
        array('label' => 'Uploaded', 'color' => 'blue'),
        array('label' => 'Expiring Soon', 'color' => 'orange'),
        array('label' => 'Expired', 'color' => 'gray'),
        array('label' => 'Complete', 'color' => 'green'),
    );
    $how_states = get_field('how_readiness_states', $post_id);
    if (is_array($how_states) && !empty($how_states)) {
        $readiness_states = array_map(function ($row) {
            return array('label' => isset($row['label']) ? $row['label'] : '', 'color' => isset($row['color']) ? $row['color'] : 'gray');
        }, $how_states);
    }

    $verification_items = get_field('verification_items', $post_id) ?: array();
    $verification_items = array_map(function ($row) {
        return array('icon' => isset($row['icon']) ? $row['icon'] : 'Circle', 'label' => isset($row['label']) ? $row['label'] : '');
    }, $verification_items);

    $founder_bullets = get_field('founder_bullets', $post_id) ?: array();
    $founder_bullets = array_map(function ($row) {
        return isset($row['bullet_text']) ? $row['bullet_text'] : '';
    }, $founder_bullets);

    $problems = get_field('problems', $post_id) ?: array();
    $problems = array_map(function ($row) {
        return array(
            'icon' => isset($row['icon']) ? $row['icon'] : 'Circle',
            'title' => isset($row['title']) ? $row['title'] : '',
            'description' => isset($row['description']) ? $row['description'] : '',
        );
    }, $problems);

    $how_steps = get_field('how_it_works_steps', $post_id) ?: array();
    $how_steps = array_map(function ($row) {
        return array(
            'icon' => isset($row['icon']) ? $row['icon'] : 'Circle',
            'step' => isset($row['step_number']) ? $row['step_number'] : '',
            'title' => isset($row['title']) ? $row['title'] : '',
            'description' => isset($row['description']) ? $row['description'] : '',
        );
    }, $how_steps);

    $provider_steps = get_field('provider_steps', $post_id) ?: array();
    $provider_steps = array_map(function ($row) {
        return array(
            'icon' => isset($row['icon']) ? $row['icon'] : 'Circle',
            'step' => isset($row['step_number']) ? $row['step_number'] : '',
            'title' => isset($row['title']) ? $row['title'] : '',
            'description' => isset($row['description']) ? $row['description'] : '',
        );
    }, $provider_steps);
    if (empty($provider_steps)) {
        $provider_steps = $how_steps;
    }

    $group_steps = get_field('group_steps', $post_id) ?: array();
    $group_steps = array_map(function ($row) {
        return array(
            'icon' => isset($row['icon']) ? $row['icon'] : 'Circle',
            'step' => isset($row['step_number']) ? $row['step_number'] : '',
            'title' => isset($row['title']) ? $row['title'] : '',
            'description' => isset($row['description']) ? $row['description'] : '',
        );
    }, $group_steps);

    $features_items = get_field('features_items', $post_id) ?: array();
    $features_items = array_map(function ($row) {
        return array(
            'icon' => isset($row['icon']) ? $row['icon'] : 'Circle',
            'title' => isset($row['title']) ? $row['title'] : '',
            'description' => isset($row['description']) ? $row['description'] : '',
            'link' => isset($row['link']) ? $row['link'] : '#',
        );
    }, $features_items);

    $packet_checklist = get_field('packet_checklist', $post_id) ?: array();
    $packet_checklist = array_map(function ($row) {
        return isset($row['item_text']) ? $row['item_text'] : '';
    }, $packet_checklist);

    $security_features = get_field('security_features', $post_id) ?: array();
    $security_features = array_map(function ($row) {
        return array('icon' => isset($row['icon']) ? $row['icon'] : 'Shield', 'text' => isset($row['text']) ? $row['text'] : '');
    }, $security_features);

    $security_badges = get_field('security_floating_badges', $post_id) ?: array();
    $security_badges = array_map(function ($row) {
        return isset($row['badge_text']) ? $row['badge_text'] : '';
    }, $security_badges);

    $caqh_benefits = get_field('caqh_benefits', $post_id) ?: array();
    $caqh_benefits = array_map(function ($row) {
        return isset($row['benefit_text']) ? $row['benefit_text'] : '';
    }, $caqh_benefits);

    $caqh_consent = get_field('caqh_consent_modes', $post_id) ?: array();
    $caqh_consent = array_map(function ($row) {
        return array(
            'icon' => isset($row['icon']) ? $row['icon'] : 'Circle',
            'title' => isset($row['title']) ? $row['title'] : '',
            'description' => isset($row['description']) ? $row['description'] : '',
        );
    }, $caqh_consent);
    $provider_bullets = get_field('provider_bullets', $post_id) ?: array();
    $provider_bullets = array_values(array_filter(array_map(function ($row) {
        return isset($row['bullet_text']) ? $row['bullet_text'] : '';
    }, $provider_bullets)));

    $group_bullets = get_field('group_bullets', $post_id) ?: array();
    $group_bullets = array_values(array_filter(array_map(function ($row) {
        return isset($row['bullet_text']) ? $row['bullet_text'] : '';
    }, $group_bullets)));

    $group_notes = get_field('group_notes', $post_id) ?: array();
    $group_notes = array_values(array_filter(array_map(function ($row) {
        return isset($row['note_text']) ? $row['note_text'] : '';
    }, $group_notes)));

    $pricing_content = array(
        'sectionTitle' => get_field('pricing_section_title', $post_id) ?: 'Simple pricing. No surprises.',
        'sectionSubtitle' => get_field('pricing_section_subtitle', $post_id) ?: "Whether you're a solo provider or managing a 50-person group...",
        'providerCard' => array(
            'badge' => get_field('provider_badge', $post_id) ?: 'For Individual Providers',
            'price' => get_field('provider_price', $post_id) ?: '$99/year',
            'priceSub' => get_field('provider_price_sub', $post_id) ?: '+ $60 per packet generated',
            'bullets' => $provider_bullets,
            'cta' => array(
                'label' => get_field('provider_cta_label', $post_id) ?: 'Get Started — $99/year',
                'href' => get_field('provider_cta_href', $post_id) ?: '#',
            ),
            'finePrint' => get_field('provider_fine_print', $post_id) ?: 'No contracts. Cancel anytime.',
            'callout' => get_field('provider_callout', $post_id) ?: 'Most providers pay under $600 total in year one.',
            'highlighted' => (bool) get_field('provider_highlighted', $post_id),
        ),
        'groupCard' => array(
            'badge' => get_field('group_badge', $post_id) ?: 'For Groups & Facilities',
            'price' => get_field('group_price', $post_id) ?: '$1,199/seat/year',
            'priceSub' => get_field('group_price_sub', $post_id) ?: 'All payer packet workflows included',
            'bullets' => $group_bullets,
            'cta' => array(
                'label' => get_field('group_cta_label', $post_id) ?: 'Talk to Us',
                'href' => get_field('group_cta_href', $post_id) ?: '/contact',
            ),
            'finePrint' => get_field('group_fine_print', $post_id) ?: "Up to 50 seats. More than 50? Let's talk.",
            'notes' => $group_notes,
            'secondaryLink' => array(
                'label' => get_field('group_secondary_link_label', $post_id) ?: 'See full feature comparison →',
                'href' => get_field('group_secondary_link_href', $post_id) ?: '/pricing',
            ),
            'highlighted' => (bool) get_field('group_highlighted', $post_id),
        ),
    );

    $ecosystem_pairs = get_field('ecosystem_pairs', $post_id) ?: array();
    $ecosystem_pairs = array_values(array_filter(array_map(function ($row) {
        $provider_action = isset($row['provider_action']) ? $row['provider_action'] : '';
        $connector = isset($row['connector']) ? $row['connector'] : '';
        $group_outcome = isset($row['group_outcome']) ? $row['group_outcome'] : '';
        if ($provider_action === '' && $connector === '' && $group_outcome === '') {
            return null;
        }
        return array(
            'providerAction' => $provider_action,
            'connector' => $connector,
            'groupOutcome' => $group_outcome,
        );
    }, $ecosystem_pairs)));

    $support_features = get_field('support_features', $post_id) ?: array();
    $support_features = array_map(function ($row) {
        return array('icon' => isset($row['icon']) ? $row['icon'] : 'Circle', 'text' => isset($row['text']) ? $row['text'] : '');
    }, $support_features);

    $support_links = get_field('support_links', $post_id) ?: array();
    $support_links = array_map(function ($row) {
        return array('label' => isset($row['label']) ? $row['label'] : '', 'href' => isset($row['href']) ? $row['href'] : '#');
    }, $support_links);

    $team_members = get_field('team_members', $post_id) ?: array();
    $team_members = array_map(function ($row) {
        return array(
            'name' => isset($row['name']) ? $row['name'] : '',
            'role' => isset($row['role']) ? $row['role'] : '',
            'icon' => isset($row['icon']) ? $row['icon'] : 'User',
            'bio' => isset($row['bio']) ? $row['bio'] : '',
        );
    }, $team_members);

    $faq_items = get_field('faq_items', $post_id) ?: array();
    $faq_items = array_map(function ($row) {
        return array(
            'question' => isset($row['question']) ? $row['question'] : '',
            'answer' => isset($row['answer']) ? $row['answer'] : '',
        );
    }, $faq_items);

    return array(
        'siteConfig' => array(
            'name' => get_field('site_name', $post_id) ?: 'CCS Pro',
            'tagline' => get_field('site_tagline', $post_id) ?: '',
            'description' => get_field('site_description', $post_id) ?: '',
        ),
        'navLinks' => $nav_links,
        'navCtas' => array(
            'primary' => array('label' => get_field('nav_primary_label', $post_id) ?: 'Start free', 'href' => get_field('nav_primary_href', $post_id) ?: '#pricing'),
            'secondary' => array('label' => get_field('nav_secondary_label', $post_id) ?: 'Book a demo', 'href' => get_field('nav_secondary_href', $post_id) ?: '#demo'),
            'signIn' => array('label' => get_field('nav_signin_label', $post_id) ?: 'Sign in', 'href' => get_field('nav_signin_href', $post_id) ?: '#'),
        ),
        'heroContent' => array(
            'headline' => get_field('hero_headline', $post_id) ?: '',
            'headlineHighlight' => get_field('hero_headline_highlight', $post_id) ?: '',
            'headlineSuffix' => get_field('hero_headline_suffix', $post_id) ?: 'Ready Always.',
            'subheadline' => get_field('hero_subheadline', $post_id) ?: '',
            'primaryCta' => array('label' => get_field('hero_primary_label', $post_id) ?: '', 'href' => get_field('hero_primary_href', $post_id) ?: '#'),
            'secondaryCta' => array('label' => get_field('hero_secondary_label', $post_id) ?: '', 'href' => get_field('hero_secondary_href', $post_id) ?: '#'),
            'tertiaryCta' => array('label' => get_field('hero_tertiary_label', $post_id) ?: '', 'href' => get_field('hero_tertiary_href', $post_id) ?: '#'),
            'trustIndicators' => $hero_trust,
        ),
        'heroDashboard' => array(
            'title' => get_field('hero_dashboard_title', $post_id) ?: 'Credential Packet',
            'subtitle' => get_field('hero_dashboard_subtitle', $post_id) ?: '',
            'completionPercent' => (int) get_field('hero_dashboard_completion', $post_id) ?: 0,
            'stateValue' => get_field('hero_dashboard_state', $post_id) ?: '',
            'npiValue' => get_field('hero_dashboard_npi', $post_id) ?: '',
            'readinessStates' => $readiness_states,
            'documents' => $hero_docs,
            'buttons' => array(
                'primary' => get_field('hero_dashboard_btn_primary', $post_id) ?: 'Generate Signed PDF',
                'secondary' => get_field('hero_dashboard_btn_secondary', $post_id) ?: 'Generate Packet PDF',
            ),
        ),
        'verificationContent' => array(
            'headline' => get_field('verification_headline', $post_id) ?: '',
            'items' => $verification_items,
        ),
        'founderContent' => array(
            'name' => get_field('founder_name', $post_id) ?: '',
            'title' => get_field('founder_title', $post_id) ?: '',
            'initials' => get_field('founder_initials', $post_id) ?: 'DR',
            'quote' => get_field('founder_quote', $post_id) ?: '',
            'bullets' => $founder_bullets,
        ),
        'problemOutcomeContent' => array(
            'problems' => $problems,
            'outcomeText' => array(
                'prefix' => get_field('outcome_prefix', $post_id) ?: '',
                'middle' => get_field('outcome_middle', $post_id) ?: '',
                'suffix' => get_field('outcome_suffix', $post_id) ?: '',
            ),
        ),
        'howItWorksContent' => array(
            'sectionTitle' => get_field('how_it_works_title', $post_id) ?: 'How it works',
            'sectionSubtitle' => get_field('how_it_works_subtitle', $post_id) ?: '',
            'steps' => $how_steps,
            'providerSteps' => $provider_steps,
            'groupSteps' => $group_steps,
            'readinessNote' => array(
                'label' => get_field('how_readiness_label', $post_id) ?: '5 Readiness States:',
                'states' => $readiness_states,
            ),
        ),
        'ecosystemContent' => array(
            'headline' => get_field('ecosystem_headline', $post_id) ?: 'One profile. Two sides of credentialing. Finally connected.',
            'subheadline' => get_field('ecosystem_subheadline', $post_id) ?: 'Providers build it once. Groups use it everywhere.',
            'pairs' => $ecosystem_pairs,
        ),
        'featuresContent' => array(
            'sectionTitle' => get_field('features_title', $post_id) ?: "What's included",
            'sectionSubtitle' => get_field('features_subtitle', $post_id) ?: '',
            'features' => $features_items,
        ),
        'packetPreviewContent' => array(
            'sectionTitle' => get_field('packet_title', $post_id) ?: '',
            'sectionSubtitle' => get_field('packet_subtitle', $post_id) ?: '',
            'fileName' => get_field('packet_filename', $post_id) ?: '',
            'checklist' => $packet_checklist,
            'cta' => array(
                'label' => get_field('packet_cta_label', $post_id) ?: '',
                'href' => get_field('packet_cta_href', $post_id) ?: '#',
            ),
        ),
        'securityContent' => array(
            'badge' => get_field('security_badge', $post_id) ?: '',
            'sectionTitle' => get_field('security_title', $post_id) ?: '',
            'sectionSubtitle' => get_field('security_subtitle', $post_id) ?: '',
            'features' => $security_features,
            'cta' => array(
                'label' => get_field('security_cta_label', $post_id) ?: '',
                'href' => get_field('security_cta_href', $post_id) ?: '#',
            ),
            'floatingBadges' => $security_badges,
        ),
        'caqhConciergeContent' => array(
            'badge' => get_field('caqh_badge', $post_id) ?: '',
            'sectionTitle' => get_field('caqh_title', $post_id) ?: '',
            'sectionSubtitle' => get_field('caqh_subtitle', $post_id) ?: '',
            'benefitsTitle' => get_field('caqh_benefits_title', $post_id) ?: 'What we do for you:',
            'benefits' => $caqh_benefits,
            'cta' => array(
                'label' => get_field('caqh_cta_label', $post_id) ?: '',
                'href' => get_field('caqh_cta_href', $post_id) ?: '#',
            ),
            'consentTitle' => get_field('caqh_consent_title', $post_id) ?: 'Choose your consent mode:',
            'consentModes' => $caqh_consent,
            'alwaysIncluded' => array(
                'icon' => get_field('caqh_always_icon', $post_id) ?: 'Bell',
                'title' => get_field('caqh_always_title', $post_id) ?: 'Always included:',
                'description' => get_field('caqh_always_description', $post_id) ?: '',
            ),
        ),
        'pricingContent' => $pricing_content,
        'supportContent' => array(
            'sectionTitle' => get_field('support_title', $post_id) ?: "We're here when you need us",
            'sectionSubtitle' => get_field('support_subtitle', $post_id) ?: '',
            'features' => $support_features,
            'links' => $support_links,
        ),
        'teamContent' => array(
            'sectionTitle' => get_field('team_title', $post_id) ?: 'The team behind CCS Pro',
            'sectionSubtitle' => get_field('team_subtitle', $post_id) ?: '',
            'members' => $team_members,
        ),
        'faqContent' => array(
            'sectionTitle' => get_field('faq_title', $post_id) ?: 'Frequently asked questions',
            'sectionSubtitle' => get_field('faq_subtitle', $post_id) ?: '',
            'items' => $faq_items,
        ),
        'finalCtaContent' => array(
            'headline' => get_field('final_cta_headline', $post_id) ?: '',
            'subheadline' => get_field('final_cta_subheadline', $post_id) ?: '',
            'primaryCta' => array('label' => get_field('final_cta_primary_label', $post_id) ?: '', 'href' => get_field('final_cta_primary_href', $post_id) ?: '#'),
            'secondaryCta' => array('label' => get_field('final_cta_secondary_label', $post_id) ?: '', 'href' => get_field('final_cta_secondary_href', $post_id) ?: '#'),
        ),
    );
}

// ---------------------------------------------------------------------------
// REST: Pricing / About / Contact page endpoints
// ---------------------------------------------------------------------------

function ccspro_rest_get_pricing_page($request) {
    if (!function_exists('get_field')) {
        return new WP_Error('acf_missing', 'ACF is required', array('status' => 500));
    }

    $provider_bullets = get_field('pricing_provider_bullets', 'option') ?: array();
    $provider_bullets = array_values(array_filter(array_map(function ($row) {
        return isset($row['bullet_text']) ? $row['bullet_text'] : '';
    }, $provider_bullets)));

    $provider_extras = get_field('pricing_provider_extras', 'option') ?: array();
    $provider_extras = array_values(array_filter(array_map(function ($row) {
        return isset($row['extra_text']) ? $row['extra_text'] : '';
    }, $provider_extras)));

    $group_bullets = get_field('pricing_group_bullets', 'option') ?: array();
    $group_bullets = array_values(array_filter(array_map(function ($row) {
        return isset($row['bullet_text']) ? $row['bullet_text'] : '';
    }, $group_bullets)));

    $group_extras = get_field('pricing_group_extras', 'option') ?: array();
    $group_extras = array_values(array_filter(array_map(function ($row) {
        return isset($row['extra_text']) ? $row['extra_text'] : '';
    }, $group_extras)));

    $feature_table = get_field('pricing_feature_table', 'option') ?: array();
    $feature_table = array_values(array_filter(array_map(function ($cat) {
        $category = isset($cat['category']) ? $cat['category'] : '';
        $rows = isset($cat['rows']) && is_array($cat['rows']) ? $cat['rows'] : array();
        $rows = array_map(function ($row) {
            return array(
                'feature' => isset($row['feature']) ? $row['feature'] : '',
                'provider' => !empty($row['provider']),
                'group' => !empty($row['group']),
            );
        }, $rows);
        if ($category === '' && empty($rows)) return null;
        return array('category' => $category, 'rows' => $rows);
    }, $feature_table)));

    $faq_items = get_field('pricing_faq_items', 'option') ?: array();
    $faq_items = array_values(array_filter(array_map(function ($row) {
        $q = isset($row['question']) ? $row['question'] : '';
        $a = isset($row['answer']) ? $row['answer'] : '';
        if ($q === '' && $a === '') return null;
        return array('question' => $q, 'answer' => $a);
    }, $faq_items)));

    $provider_secondary = null;
    $psl = get_field('pricing_provider_secondary_link_label', 'option') ?: '';
    $psh = get_field('pricing_provider_secondary_link_href', 'option') ?: '';
    if ($psl !== '' || $psh !== '') {
        $provider_secondary = array('label' => $psl, 'href' => $psh ?: '#');
    }

    $group_secondary = null;
    $gsl = get_field('pricing_group_secondary_link_label', 'option') ?: '';
    $gsh = get_field('pricing_group_secondary_link_href', 'option') ?: '';
    if ($gsl !== '' || $gsh !== '') {
        $group_secondary = array('label' => $gsl, 'href' => $gsh ?: '#');
    }

    $data = array(
        'hero' => array(
            'headline' => get_field('pricing_hero_headline', 'option') ?: '',
            'subheadline' => get_field('pricing_hero_subheadline', 'option') ?: '',
        ),
        'provider' => array(
            'badge' => get_field('pricing_provider_badge', 'option') ?: '',
            'price' => get_field('pricing_provider_price', 'option') ?: '',
            'subtext' => get_field('pricing_provider_price_sub', 'option') ?: '',
            'bullets' => $provider_bullets,
            'cta' => array(
                'label' => get_field('pricing_provider_cta_label', 'option') ?: '',
                'href' => get_field('pricing_provider_cta_href', 'option') ?: '#',
            ),
            'finePrint' => get_field('pricing_provider_fine_print', 'option') ?: '',
            'highlighted' => !empty(get_field('pricing_provider_highlighted', 'option')),
            'extras' => $provider_extras,
            'secondaryLink' => $provider_secondary,
        ),
        'group' => array(
            'badge' => get_field('pricing_group_badge', 'option') ?: '',
            'price' => get_field('pricing_group_price', 'option') ?: '',
            'subtext' => get_field('pricing_group_price_sub', 'option') ?: '',
            'bullets' => $group_bullets,
            'cta' => array(
                'label' => get_field('pricing_group_cta_label', 'option') ?: '',
                'href' => get_field('pricing_group_cta_href', 'option') ?: '#',
            ),
            'finePrint' => get_field('pricing_group_fine_print', 'option') ?: '',
            'highlighted' => !empty(get_field('pricing_group_highlighted', 'option')),
            'extras' => $group_extras,
            'secondaryLink' => $group_secondary,
        ),
        'featureTable' => $feature_table,
        'faq' => array(
            'sectionTitle' => get_field('pricing_faq_title', 'option') ?: '',
            'sectionSubtitle' => get_field('pricing_faq_subtitle', 'option') ?: '',
            'items' => $faq_items,
        ),
        'finalCta' => array(
            'headline' => get_field('pricing_final_cta_headline', 'option') ?: '',
            'providerCta' => array(
                'label' => get_field('pricing_final_cta_provider_label', 'option') ?: '',
                'href' => get_field('pricing_final_cta_provider_href', 'option') ?: '#',
            ),
            'groupCta' => array(
                'label' => get_field('pricing_final_cta_group_label', 'option') ?: '',
                'href' => get_field('pricing_final_cta_group_href', 'option') ?: '#',
            ),
        ),
    );

    return rest_ensure_response($data);
}

function ccspro_rest_get_about_page($request) {
    if (!function_exists('get_field')) {
        return new WP_Error('acf_missing', 'ACF is required', array('status' => 500));
    }

    $stats = get_field('about_why_texas_stats', 'option') ?: array();
    $stats = array_values(array_filter(array_map(function ($row) {
        $v = isset($row['value']) ? $row['value'] : '';
        $l = isset($row['label']) ? $row['label'] : '';
        if ($v === '' && $l === '') return null;
        return array('value' => $v, 'label' => $l);
    }, $stats)));

    $differentiators = get_field('about_differentiators', 'option') ?: array();
    $differentiators = array_values(array_filter(array_map(function ($row) {
        $t = isset($row['title']) ? $row['title'] : '';
        $d = isset($row['description']) ? $row['description'] : '';
        if ($t === '' && $d === '') return null;
        return array('title' => $t, 'description' => $d);
    }, $differentiators)));

    $data = array(
        'hero' => array(
            'headline' => get_field('about_hero_headline', 'option') ?: '',
            'subheadline' => get_field('about_hero_subheadline', 'option') ?: '',
        ),
        'mission' => get_field('about_mission', 'option') ?: '',
        'whyTexas' => array(
            'paragraph' => get_field('about_why_texas_paragraph', 'option') ?: '',
            'stats' => $stats,
        ),
        'differentiators' => $differentiators,
        'cta' => array(
            'text' => get_field('about_cta_text', 'option') ?: '',
            'link' => array(
                'label' => get_field('about_cta_link_label', 'option') ?: '',
                'href' => get_field('about_cta_link_href', 'option') ?: '#',
            ),
        ),
    );

    return rest_ensure_response($data);
}

function ccspro_rest_get_contact_page($request) {
    if (!function_exists('get_field')) {
        return new WP_Error('acf_missing', 'ACF is required', array('status' => 500));
    }

    $role_options = get_field('contact_role_options', 'option') ?: array();
    $role_options = array_values(array_filter(array_map(function ($row) {
        return isset($row['option_text']) ? $row['option_text'] : '';
    }, $role_options)));

    $data = array(
        'hero' => array(
            'headline' => get_field('contact_hero_headline', 'option') ?: '',
            'subheadline' => get_field('contact_hero_subheadline', 'option') ?: '',
        ),
        'formFields' => array(
            'roleOptions' => $role_options,
        ),
        'contactInfo' => array(
            'email' => get_field('contact_email', 'option') ?: '',
            'responseTime' => get_field('contact_response_time', 'option') ?: '',
            'businessHours' => get_field('contact_business_hours', 'option') ?: '',
        ),
        'groupCallout' => array(
            'headline' => get_field('contact_group_callout_headline', 'option') ?: '',
            'body' => get_field('contact_group_callout_body', 'option') ?: '',
        ),
    );

    return rest_ensure_response($data);
}
