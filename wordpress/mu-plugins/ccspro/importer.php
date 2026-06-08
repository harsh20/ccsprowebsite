<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'ccspro_register_importer_page');
add_action('admin_post_ccspro_import_mock_content', 'ccspro_handle_mock_content_import');

function ccspro_register_importer_page() {
    add_submenu_page(
        'ccspro-settings',
        'Import Mock Content',
        'Import Mock Content',
        'manage_options',
        'ccspro-import-mock-content',
        'ccspro_render_importer_page'
    );
}

function ccspro_render_importer_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $status = isset($_GET['ccspro_import']) ? sanitize_text_field(wp_unslash($_GET['ccspro_import'])) : '';
    ?>
    <div class="wrap">
        <h1>Import Mock Content</h1>
        <?php if ($status === 'success') : ?>
            <div class="notice notice-success"><p>Mock content imported successfully.</p></div>
        <?php elseif ($status === 'acf-missing') : ?>
            <div class="notice notice-error"><p>Advanced Custom Fields is required to run the importer.</p></div>
        <?php endif; ?>
        <p>This imports the bundled frontend mock content into WordPress. It creates or updates the <code>default</code> landing page, site options, named page options, and the registered menus.</p>
        <p>You can edit everything in WordPress afterwards. Re-running the importer updates the same CCS Pro-managed content.</p>
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('ccspro_import_mock_content'); ?>
            <input type="hidden" name="action" value="ccspro_import_mock_content" />
            <?php submit_button('Import Mock Content'); ?>
        </form>
    </div>
    <?php
}

function ccspro_handle_mock_content_import() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }

    check_admin_referer('ccspro_import_mock_content');

    if (!function_exists('update_field')) {
        wp_safe_redirect(add_query_arg('ccspro_import', 'acf-missing', admin_url('admin.php?page=ccspro-import-mock-content')));
        exit;
    }

    $data = ccspro_get_mock_import_data();

    ccspro_import_site_options($data);
    ccspro_import_named_page_options($data);
    ccspro_import_menus($data['menus']);
    ccspro_import_default_landing_page($data['landing']);

    wp_safe_redirect(add_query_arg('ccspro_import', 'success', admin_url('admin.php?page=ccspro-import-mock-content')));
    exit;
}

function ccspro_import_site_options($data) {
    update_field('site_logo_text', $data['site']['header']['logoText'], 'option');
    update_field('header_cta_label', $data['site']['header']['ctaButton']['label'], 'option');
    update_field('header_cta_href', $data['site']['header']['ctaButton']['href'], 'option');
    update_field('header_signin_label', $data['site']['header']['signinLink']['label'], 'option');
    update_field('header_signin_href', $data['site']['header']['signinLink']['href'], 'option');
    update_field('footer_brand_name', $data['site']['footer']['brandName'], 'option');
    update_field('footer_tagline', $data['site']['footer']['tagline'], 'option');
    update_field('footer_trust_badges', $data['site']['footer']['trustBadges'], 'option');
    update_field('footer_copyright', $data['site']['footer']['copyright'], 'option');
}

function ccspro_import_named_page_options($data) {
    $pricing = $data['pricing'];
    update_field('pricing_hero_headline', $pricing['hero']['headline'], 'option');
    update_field('pricing_hero_subheadline', $pricing['hero']['subheadline'], 'option');
    update_field('pricing_provider_badge', $pricing['provider']['badge'], 'option');
    update_field('pricing_provider_price', $pricing['provider']['price'], 'option');
    update_field('pricing_provider_price_sub', $pricing['provider']['subtext'], 'option');
    update_field('pricing_provider_highlighted', $pricing['provider']['highlighted'], 'option');
    update_field('pricing_provider_bullets', ccspro_import_rows_from_strings($pricing['provider']['bullets'], 'bullet_text'), 'option');
    update_field('pricing_provider_cta_label', $pricing['provider']['cta']['label'], 'option');
    update_field('pricing_provider_cta_href', $pricing['provider']['cta']['href'], 'option');
    update_field('pricing_provider_fine_print', $pricing['provider']['finePrint'], 'option');
    update_field('pricing_provider_extras', ccspro_import_rows_from_strings($pricing['provider']['extras'], 'extra_text'), 'option');
    update_field('pricing_group_badge', $pricing['group']['badge'], 'option');
    update_field('pricing_group_price', $pricing['group']['price'], 'option');
    update_field('pricing_group_price_sub', $pricing['group']['subtext'], 'option');
    update_field('pricing_group_highlighted', $pricing['group']['highlighted'], 'option');
    update_field('pricing_group_bullets', ccspro_import_rows_from_strings($pricing['group']['bullets'], 'bullet_text'), 'option');
    update_field('pricing_group_cta_label', $pricing['group']['cta']['label'], 'option');
    update_field('pricing_group_cta_href', $pricing['group']['cta']['href'], 'option');
    update_field('pricing_group_fine_print', $pricing['group']['finePrint'], 'option');
    update_field('pricing_group_extras', ccspro_import_rows_from_strings($pricing['group']['extras'], 'extra_text'), 'option');
    update_field('pricing_group_secondary_link_label', $pricing['group']['secondaryLink']['label'], 'option');
    update_field('pricing_group_secondary_link_href', $pricing['group']['secondaryLink']['href'], 'option');
    update_field('pricing_feature_table', $pricing['featureTable'], 'option');
    update_field('pricing_faq_title', $pricing['faq']['sectionTitle'], 'option');
    update_field('pricing_faq_subtitle', $pricing['faq']['sectionSubtitle'], 'option');
    update_field('pricing_faq_items', $pricing['faq']['items'], 'option');
    update_field('pricing_final_cta_headline', $pricing['finalCta']['headline'], 'option');
    update_field('pricing_final_cta_provider_label', $pricing['finalCta']['providerCta']['label'], 'option');
    update_field('pricing_final_cta_provider_href', $pricing['finalCta']['providerCta']['href'], 'option');
    update_field('pricing_final_cta_group_label', $pricing['finalCta']['groupCta']['label'], 'option');
    update_field('pricing_final_cta_group_href', $pricing['finalCta']['groupCta']['href'], 'option');

    $about = $data['about'];
    update_field('about_hero_headline', $about['hero']['headline'], 'option');
    update_field('about_hero_subheadline', $about['hero']['subheadline'], 'option');
    update_field('about_mission', $about['mission'], 'option');
    update_field('about_why_texas_paragraph', $about['whyTexas']['paragraph'], 'option');
    update_field('about_why_texas_stats', $about['whyTexas']['stats'], 'option');
    update_field('about_differentiators', $about['differentiators'], 'option');
    update_field('about_cta_text', $about['cta']['text'], 'option');
    update_field('about_cta_link_label', $about['cta']['link']['label'], 'option');
    update_field('about_cta_link_href', $about['cta']['link']['href'], 'option');

    $contact = $data['contact'];
    update_field('contact_hero_headline', $contact['hero']['headline'], 'option');
    update_field('contact_hero_subheadline', $contact['hero']['subheadline'], 'option');
    update_field('contact_role_options', ccspro_import_rows_from_strings($contact['formFields']['roleOptions'], 'option_text'), 'option');
    update_field('contact_email', $contact['contactInfo']['email'], 'option');
    update_field('contact_phone', $contact['contactInfo']['phone'] ?? '', 'option');
    update_field('contact_response_time', $contact['contactInfo']['responseTime'], 'option');
    update_field('contact_business_hours', $contact['contactInfo']['businessHours'], 'option');
    update_field('contact_group_callout_headline', $contact['groupCallout']['headline'], 'option');
    update_field('contact_group_callout_body', $contact['groupCallout']['body'], 'option');
}

function ccspro_import_menus($menus) {
    $menu_map = array(
        'ccspro-primary-nav' => array('name' => 'CCS Pro Primary Navigation', 'items' => $menus['primary']),
        'ccspro-footer-col1' => array('name' => 'CCS Pro Footer Product', 'items' => $menus['footer1']),
        'ccspro-footer-col2' => array('name' => 'CCS Pro Footer Company', 'items' => $menus['footer2']),
        'ccspro-footer-col3' => array('name' => 'CCS Pro Footer Legal', 'items' => $menus['footer3']),
    );

    $locations = get_nav_menu_locations();

    foreach ($menu_map as $location => $config) {
        $menu = wp_get_nav_menu_object($config['name']);
        if (!$menu) {
            $menu_id = wp_create_nav_menu($config['name']);
            $menu = wp_get_nav_menu_object($menu_id);
        }

        $items = wp_get_nav_menu_items($menu->term_id);
        if (is_array($items)) {
            foreach ($items as $item) {
                wp_delete_post($item->ID, true);
            }
        }

        foreach ($config['items'] as $item) {
            wp_update_nav_menu_item($menu->term_id, 0, array(
                'menu-item-title' => $item['label'],
                'menu-item-url' => $item['href'],
                'menu-item-status' => 'publish',
                'menu-item-target' => !empty($item['openInNewTab']) ? '_blank' : '',
            ));
        }

        $locations[$location] = (int) $menu->term_id;
    }

    set_theme_mod('nav_menu_locations', $locations);
}

function ccspro_import_default_landing_page($landing) {
    $existing = get_page_by_path('default', OBJECT, 'landing_page');
    $postarr = array(
        'post_type' => 'landing_page',
        'post_status' => 'publish',
        'post_title' => 'Default Landing Page',
        'post_name' => 'default',
    );

    if ($existing) {
        $postarr['ID'] = $existing->ID;
        $post_id = wp_update_post($postarr, true);
    } else {
        $post_id = wp_insert_post($postarr, true);
    }

    if (is_wp_error($post_id) || !$post_id) {
        return;
    }

    foreach ($landing['simple_fields'] as $field => $value) {
        update_field($field, $value, $post_id);
    }

    foreach ($landing['repeaters'] as $field => $value) {
        update_field($field, $value, $post_id);
    }
}

function ccspro_import_rows_from_strings($values, $key) {
    $rows = array();
    foreach ($values as $value) {
        $rows[] = array($key => $value);
    }
    return $rows;
}

function ccspro_get_mock_import_data() {
    $year = gmdate('Y');

    return array(
        'site' => array(
            'header' => array(
                'logoText' => 'CCS Pro',
                'ctaButton' => array('label' => 'Get Started', 'href' => '#'),
                'signinLink' => array('label' => 'Sign In', 'href' => '#'),
            ),
            'footer' => array(
                'brandName' => 'CCS Pro',
                'tagline' => 'Credentialing packets for Texas providers. Built once, ready always.',
                'trustBadges' => array(
                    array('icon' => 'Shield', 'text' => 'HIPAA Compliant'),
                    array('icon' => 'FileCheck', 'text' => 'BAA Available'),
                    array('icon' => 'MapPin', 'text' => 'Texas-Based'),
                ),
                'copyright' => 'Copyright ' . $year . ' CCS Pro. All rights reserved.',
            ),
        ),
        'menus' => array(
            'primary' => array(
                array('label' => 'Product', 'href' => '/#how-it-works', 'openInNewTab' => false),
                array('label' => 'Pricing', 'href' => '/pricing', 'openInNewTab' => false),
                array('label' => 'About', 'href' => '/about', 'openInNewTab' => false),
                array('label' => 'Contact', 'href' => '/contact', 'openInNewTab' => false),
            ),
            'footer1' => array(
                array('label' => 'How It Works', 'href' => '/#how-it-works', 'openInNewTab' => false),
                array('label' => 'For Providers', 'href' => '/pricing#provider', 'openInNewTab' => false),
                array('label' => 'For Groups', 'href' => '/pricing#groups', 'openInNewTab' => false),
                array('label' => 'Pricing', 'href' => '/pricing', 'openInNewTab' => false),
            ),
            'footer2' => array(
                array('label' => 'About Us', 'href' => '/about', 'openInNewTab' => false),
                array('label' => 'Contact', 'href' => '/contact', 'openInNewTab' => false),
                array('label' => 'Help Center', 'href' => '/help', 'openInNewTab' => false),
            ),
            'footer3' => array(
                array('label' => 'Privacy Policy', 'href' => '#', 'openInNewTab' => false),
                array('label' => 'Terms of Service', 'href' => '#', 'openInNewTab' => false),
                array('label' => 'BAA', 'href' => '#', 'openInNewTab' => false),
            ),
        ),
        'landing' => ccspro_get_mock_landing_data(),
        'pricing' => ccspro_get_mock_pricing_page_data(),
        'about' => ccspro_get_mock_about_page_data(),
        'contact' => ccspro_get_mock_contact_page_data(),
    );
}

function ccspro_get_mock_pricing_page_data() {
    return array(
        'hero' => array(
            'headline' => 'Simple pricing. No surprises.',
            'subheadline' => "Whether you're a solo provider or managing a 50-person group, CCS Pro fits your workflow and your budget.",
        ),
        'provider' => array(
            'badge' => 'For Individual Providers',
            'price' => '$99/year',
            'subtext' => '+ $60 per packet generated',
            'bullets' => array('Complete LHL234 profile', 'Unlimited document storage', 'E-signature via SignNow', 'Packet generation on demand'),
            'cta' => array('label' => 'Get Started - $99/year', 'href' => '#'),
            'finePrint' => 'No contracts. Cancel anytime.',
            'highlighted' => false,
            'extras' => array('Most providers pay under $600 total in year one.'),
        ),
        'group' => array(
            'badge' => 'For Groups & Facilities',
            'price' => '$1,199/seat/year',
            'subtext' => 'All payer packet workflows included',
            'bullets' => array('Full provider roster management', 'Real-time compliance dashboard', 'Payer-specific packet generation', 'Provider consent and invite system'),
            'cta' => array('label' => 'Talk to Us', 'href' => '/contact'),
            'finePrint' => "Up to 50 seats. More than 50? Let's talk.",
            'highlighted' => true,
            'secondaryLink' => array('label' => 'See full feature comparison ->', 'href' => '#comparison'),
            'extras' => array('One seat = one provider in your roster', 'All payer workflows included, no packet fees.', "Need more than 50 seats? Let's talk."),
        ),
        'featureTable' => array(
            array('category' => 'Profile & Documents', 'rows' => array(
                array('feature' => 'LHL234 / TSCA profile', 'provider' => true, 'group' => true),
                array('feature' => 'Document upload and storage', 'provider' => true, 'group' => true),
                array('feature' => 'Expiration date tracking', 'provider' => true, 'group' => true),
                array('feature' => 'CAQH attestation workflow', 'provider' => true, 'group' => true),
                array('feature' => 'GPT-assisted LHL234 extraction', 'provider' => true, 'group' => true),
            )),
            array('category' => 'Packet Generation', 'rows' => array(
                array('feature' => 'Standard credentialing packet', 'provider' => true, 'group' => true),
                array('feature' => 'E-signature via SignNow', 'provider' => true, 'group' => true),
                array('feature' => 'Payer-specific packet generation', 'provider' => false, 'group' => true),
                array('feature' => 'Bulk packet generation', 'provider' => false, 'group' => true),
            )),
            array('category' => 'Group Management', 'rows' => array(
                array('feature' => 'Provider roster dashboard', 'provider' => false, 'group' => true),
                array('feature' => 'Real-time compliance tracking', 'provider' => false, 'group' => true),
                array('feature' => 'Provider invite by NPI', 'provider' => false, 'group' => true),
                array('feature' => 'Provider consent management', 'provider' => false, 'group' => true),
                array('feature' => 'Reminder and alert system', 'provider' => false, 'group' => true),
            )),
            array('category' => 'Security & Compliance', 'rows' => array(
                array('feature' => 'HIPAA-compliant infrastructure', 'provider' => true, 'group' => true),
                array('feature' => 'Azure secure hosting', 'provider' => true, 'group' => true),
                array('feature' => 'BAA available', 'provider' => true, 'group' => true),
                array('feature' => 'Audit log', 'provider' => false, 'group' => true),
            )),
        ),
        'faq' => array(
            'sectionTitle' => 'Pricing FAQ',
            'sectionSubtitle' => '',
            'items' => array(
                array('question' => 'Is the $60 packet fee per payer or per generation?', 'answer' => 'Per generation. Each time you generate a packet, that is one $60 charge regardless of which payer it is for.'),
                array('question' => 'Can a provider be in multiple groups at the same time?', 'answer' => 'Yes. A provider can consent to multiple group connections simultaneously. Each group pays for their own seat.'),
                array('question' => 'What happens if I need to change a provider in my roster?', 'answer' => 'You can remove a provider at any time. The seat has a 90-day waiting period before reassignment. If you need to onboard a new provider immediately, simply purchase an additional seat.'),
                array('question' => 'Are there setup fees or contracts?', 'answer' => 'No setup fees, no long-term contracts. Providers pay annually. Groups pay annually per seat. Cancel before your renewal and you will not be charged again.'),
                array('question' => 'Do you offer a free trial?', 'answer' => 'We do not currently offer a free trial, but providers can sign up, build their full profile, and explore the platform before generating their first packet.'),
            ),
        ),
        'finalCta' => array(
            'headline' => 'Ready to stop rebuilding from scratch?',
            'providerCta' => array('label' => 'Start as a Provider - $99/year', 'href' => '#'),
            'groupCta' => array('label' => 'Talk to Us About Group Pricing', 'href' => '/contact'),
        ),
    );
}

function ccspro_get_mock_about_page_data() {
    return array(
        'hero' => array(
            'headline' => 'Built for the people who keep healthcare credentialed.',
            'subheadline' => "CCS Pro started because the credentialing process in Texas is still largely manual, repetitive, and broken. We're fixing that.",
        ),
        'mission' => 'Our mission is to give every Texas provider a portable, always-ready credentialing profile they own completely - and give every group and facility the compliance infrastructure to manage their roster without the chaos.',
        'whyTexas' => array(
            'paragraph' => 'Texas has one of the largest and most complex provider markets in the country. The LHL234 (Texas Standardized Credentialing Application) is required by every payer and facility in the state, yet the process of filling it out, keeping it current, and submitting it to multiple organizations remains almost entirely manual. CCS Pro was built from day one around the Texas credentialing landscape.',
            'stats' => array(
                array('value' => '125,000+', 'label' => 'Credentialing providers in Texas'),
                array('value' => '60-120 days', 'label' => 'Average payer enrollment'),
                array('value' => '2-year', 'label' => 'Re-credentialing cycles'),
            ),
        ),
        'differentiators' => array(
            array('title' => 'Provider-first portability', 'description' => 'Your profile belongs to you, not your group. Switch employers, add new facilities, or go independent - your credentials follow you.'),
            array('title' => 'Texas-native', 'description' => 'Built around the LHL234/TSCA from day one. Not a generic credentialing tool with Texas bolted on after the fact.'),
            array('title' => 'Priced for real practices', 'description' => 'Solo providers pay $99/year, not enterprise software pricing. Groups pay per seat, not per feature.'),
        ),
        'cta' => array(
            'text' => 'Want to learn more or talk to the team? Reach out.',
            'link' => array('label' => 'Contact Us', 'href' => '/contact'),
        ),
    );
}

function ccspro_get_mock_contact_page_data() {
    return array(
        'hero' => array(
            'headline' => 'Get in touch.',
            'subheadline' => "Whether you're a provider with a question or a group looking to get set up, we're here.",
        ),
        'formFields' => array(
            'roleOptions' => array('Provider', 'Group or Facility', 'Other'),
        ),
        'contactInfo' => array(
            'email' => 'support@ccsprocert.com',
            'phone' => '+1 210-315-6322',
            'responseTime' => 'We respond within one business day.',
            'businessHours' => 'Monday - Friday, 9 AM - 5 PM CT',
        ),
        'groupCallout' => array(
            'headline' => 'Evaluating CCS Pro for your group or facility?',
            'body' => "Tell us your roster size and we'll put together a tailored walkthrough.",
        ),
    );
}

function ccspro_get_mock_landing_data() {
    return array(
        'simple_fields' => array(
            'site_name' => 'CCS Pro',
            'site_tagline' => 'Credentialing packets. Done once. Ready always.',
            'site_description' => 'Credentialing packets for Texas providers.',
            'nav_primary_label' => 'Get Started',
            'nav_primary_href' => '#',
            'nav_signin_label' => 'Sign In',
            'nav_signin_href' => '#',
            'hero_headline' => 'Credentialing Packets.',
            'hero_headline_highlight' => 'Done Once.',
            'hero_headline_suffix' => 'Ready Always.',
            'hero_subheadline' => 'CCS Pro lets Texas providers build their credentialing profile once and hand it off anywhere - in under 10 minutes.',
            'hero_primary_label' => 'Start as a Provider - $99/year',
            'hero_primary_href' => '/pricing#provider',
            'hero_secondary_label' => 'I manage a group or facility ->',
            'hero_secondary_href' => '/pricing#groups',
            'hero_dashboard_title' => 'Provider Profile',
            'hero_dashboard_subtitle' => 'Credentialing Readiness',
            'hero_dashboard_completion' => 85,
            'hero_dashboard_state' => 'Texas',
            'hero_dashboard_npi' => '1234567890',
            'hero_dashboard_btn_primary' => 'Generate Packet',
            'hero_dashboard_btn_secondary' => 'View Profile',
            'homepage_pain_section_label' => 'The Problem',
            'homepage_pain_headline' => "Credentialing hasn't changed. The paperwork still wins.",
            'homepage_pain_summary_text' => 'CCS Pro eliminates the rebuild. Your profile is built once, kept current, and ready to go whenever credentialing comes calling.',
            'outcome_prefix' => 'Built once',
            'outcome_middle' => ', kept current, ',
            'outcome_suffix' => 'ready to go',
            'how_it_works_title' => 'How it works',
            'how_it_works_subtitle' => 'Three simple steps to a submission-ready credentialing packet',
            'ecosystem_headline' => 'One profile. Two sides of credentialing. Finally connected.',
            'ecosystem_subheadline' => 'Providers build it once. Groups use it everywhere.',
            'homepage_cta_a_headline' => 'Providers: your profile is 10 minutes away.',
            'homepage_cta_a_subheadline' => 'Build it once. Use it for your entire career.',
            'homepage_cta_a_primary_label' => 'Start for $99/year',
            'homepage_cta_a_primary_href' => '/pricing#provider',
            'homepage_cta_a_secondary_label' => 'See how it works ->',
            'homepage_cta_a_secondary_href' => '#how-it-works',
            'homepage_cta_a_style' => 'indigo',
            'pricing_section_title' => 'Simple pricing. No surprises.',
            'pricing_section_subtitle' => "Whether you're a solo provider or managing a 50-person group, CCS Pro fits your workflow and your budget.",
            'provider_badge' => 'For Individual Providers',
            'provider_price' => '$99/year',
            'provider_price_sub' => '+ $60 per packet generated',
            'provider_cta_label' => 'Get Started - $99/year',
            'provider_cta_href' => '/pricing#provider',
            'provider_fine_print' => 'No contracts. Cancel anytime.',
            'provider_callout' => 'Most providers pay under $600 total in year one.',
            'provider_highlighted' => 0,
            'group_badge' => 'For Groups & Facilities',
            'group_price' => '$1,199/seat/year',
            'group_price_sub' => 'All payer packet workflows included',
            'group_cta_label' => 'Talk to Us',
            'group_cta_href' => '/contact',
            'group_fine_print' => "Up to 50 seats. More than 50? Let's talk.",
            'group_secondary_link_label' => 'See full feature comparison ->',
            'group_secondary_link_href' => '/pricing',
            'group_highlighted' => 1,
            'homepage_cta_b_headline' => 'Managing a group or facility?',
            'homepage_cta_b_subheadline' => 'Stop chasing providers for documents. Get your whole roster compliant in one place.',
            'homepage_cta_b_primary_label' => 'Talk to Us',
            'homepage_cta_b_primary_href' => '/contact',
            'homepage_cta_b_secondary_label' => 'See group pricing ->',
            'homepage_cta_b_secondary_href' => '/pricing#groups',
            'homepage_cta_b_style' => 'emerald',
            'home_support_headline' => "We're here when you need us",
            'faq_title' => 'Frequently asked questions',
            'faq_subtitle' => 'Everything you need to know about CCS Pro',
        ),
        'repeaters' => array(
            'nav_links' => array(
                array('label' => 'Product', 'href' => '/#how-it-works'),
                array('label' => 'Pricing', 'href' => '/pricing'),
                array('label' => 'About', 'href' => '/about'),
                array('label' => 'Contact', 'href' => '/contact'),
            ),
            'hero_trust_indicators' => array(
                array('icon' => 'Shield', 'text' => 'HIPAA Compliant'),
                array('icon' => 'FileCheck', 'text' => 'Texas LHL234 Ready'),
                array('icon' => 'ShieldCheck', 'text' => 'SignNow E-Signature'),
            ),
            'hero_dashboard_documents' => array(
                array('name' => 'DEA Certificate', 'status' => 'Complete', 'status_color' => 'green'),
                array('name' => 'Malpractice Insurance', 'status' => 'Complete', 'status_color' => 'green'),
                array('name' => 'Board Certification', 'status' => 'Expiring Soon', 'status_color' => 'orange'),
                array('name' => 'State License', 'status' => 'Complete', 'status_color' => 'green'),
            ),
            'homepage_pain_cards' => array(
                array('icon' => 'Clock', 'title' => '45+ Minutes Per Packet', 'body' => 'Every new employer, facility, or payer asks for the same information. You build it from scratch every time.'),
                array('icon' => 'RefreshCw', 'title' => 'Re-credentialing Every 2 Years', 'body' => 'The cycle never stops. Licenses expire. Documents lapse. And someone has to chase them all down.'),
                array('icon' => 'FileX', 'title' => 'One Missing Doc Delays Everything', 'body' => 'A single expired certificate can stall a 90-120 day payer enrollment. There is no buffer.'),
            ),
            'problems' => array(
                array('icon' => 'Clock', 'title' => '45+ Minutes Per Packet', 'description' => 'Every new employer, facility, or payer asks for the same information. You build it from scratch every time.'),
                array('icon' => 'RefreshCw', 'title' => 'Re-credentialing Every 2 Years', 'description' => 'The cycle never stops. Licenses expire. Documents lapse. And someone has to chase them all down.'),
                array('icon' => 'FileX', 'title' => 'One Missing Doc Delays Everything', 'description' => 'A single expired certificate can stall a 90-120 day payer enrollment. There is no buffer.'),
            ),
            'provider_steps' => array(
                array('icon' => 'Upload', 'step_number' => '01', 'title' => 'Build Your Profile Once', 'description' => 'Upload your existing LHL234 and we extract everything automatically, or enter your information directly. Takes 10 minutes.'),
                array('icon' => 'FileCheck', 'step_number' => '02', 'title' => 'Keep Documents Current', 'description' => 'Upload licenses, DEA certificate, malpractice insurance, board certifications. We track expiration dates so nothing lapses.'),
                array('icon' => 'Send', 'step_number' => '03', 'title' => 'Generate and Sign On Demand', 'description' => 'When anyone requests your credentials, generate a complete signed packet in seconds. Hand it off and get back to practicing.'),
            ),
            'group_steps' => array(
                array('icon' => 'Users', 'step_number' => '01', 'title' => 'Add Providers by NPI', 'description' => "Enter a provider's NPI. If they're already on CCS Pro, they get a consent notification. If not, they get an invite link."),
                array('icon' => 'LayoutDashboard', 'step_number' => '02', 'title' => 'Track Roster Compliance', 'description' => "Your dashboard shows real-time status across every provider - who's current, who has expiring documents, who hasn't completed their profile."),
                array('icon' => 'ClipboardCheck', 'step_number' => '03', 'title' => 'Generate Payer Packets', 'description' => "When you're ready to submit to BCBS, UHC, Aetna, or any other payer, generate a complete payer-specific packet for any provider in your roster."),
            ),
            'ecosystem_pairs' => array(
                array('provider_action' => 'Signs LHL234 in under 10 minutes', 'connector' => 'enables', 'group_outcome' => 'Generates any payer packet on demand'),
                array('provider_action' => 'Keeps documents and licenses current', 'connector' => 'means', 'group_outcome' => 'Always has a compliant, submission-ready roster'),
                array('provider_action' => 'Attests CAQH profile once', 'connector' => 'so', 'group_outcome' => 'Submits to any payer without chasing anyone'),
                array('provider_action' => 'Joins CCS Pro once', 'connector' => 'and', 'group_outcome' => 'Every future group gets instant access with one consent'),
            ),
            'provider_bullets' => ccspro_import_rows_from_strings(array('Complete LHL234 profile', 'Unlimited document storage', 'E-signature via SignNow', 'Packet generation on demand'), 'bullet_text'),
            'group_bullets' => ccspro_import_rows_from_strings(array('Full provider roster management', 'Real-time compliance dashboard', 'Payer-specific packet generation', 'Provider consent and invite system'), 'bullet_text'),
            'group_notes' => ccspro_import_rows_from_strings(array('One seat = one provider in your roster.', 'All payer workflows included, no packet fees.', "Need more than 50 seats? Let's talk."), 'note_text'),
            'support_features' => array(
                array('icon' => 'Mail', 'text' => 'Response within one business day.'),
                array('icon' => 'MessageSquare', 'text' => 'Available during business hours, Monday through Friday.'),
                array('icon' => 'BookOpen', 'text' => 'Step-by-step guides for every workflow.'),
            ),
            'support_links' => array(
                array('label' => 'Email Support', 'href' => 'mailto:support@ccsprocert.com'),
                array('label' => 'Help Center', 'href' => '/help'),
            ),
            'home_support_channels' => array(
                array('icon' => 'Mail', 'title' => 'Email Support', 'description' => 'Response within one business day.', 'link' => 'mailto:support@ccsprocert.com'),
                array('icon' => 'MessageSquare', 'title' => 'Live Chat', 'description' => 'Available during business hours, Monday through Friday.', 'link' => ''),
                array('icon' => 'BookOpen', 'title' => 'Help Center', 'description' => 'Step-by-step guides for every workflow.', 'link' => '/help'),
            ),
            'faq_items' => array(
                array('question' => 'Do I need a group to use CCS Pro as a provider?', 'answer' => 'No. Individual providers sign up independently for $99/year. You own your profile completely.'),
                array('question' => 'What is the LHL234 and why does it matter?', 'answer' => 'The LHL234, also called the Texas Standardized Credentialing Application (TSCA), is the document every Texas payer and facility uses to credential providers. CCS Pro is built around it.'),
                array('question' => 'How does a group access my information as a provider?', 'answer' => 'Only with your explicit consent. When a group adds you by NPI, you receive a notification and must approve the connection. You can revoke access at any time.'),
                array('question' => 'What happens if I need to change a provider in my roster?', 'answer' => 'You can remove a provider at any time. The seat they occupied has a 90-day waiting period before it can be reassigned to a new provider. You can always purchase an additional seat immediately if you need to onboard someone sooner.'),
                array('question' => 'What payers does CCS Pro support for group packet generation?', 'answer' => 'We support all major Texas payers including BCBS of Texas, UnitedHealthcare, Aetna, Humana, and Cigna, with more being added.'),
                array('question' => 'Is my data HIPAA compliant?', 'answer' => 'Yes. CCS Pro is hosted on Azure with a HIPAA-compliant infrastructure. All third-party vendors including SignNow and Stripe have signed Business Associate Agreements.'),
            ),
        ),
    );
}
