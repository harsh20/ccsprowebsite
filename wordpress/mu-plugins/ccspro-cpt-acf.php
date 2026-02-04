<?php
/**
 * Plugin Name: CCS Pro Landing Page CPT & ACF
 * Description: Registers Landing Page CPT, ACF fields, and REST API for headless frontend.
 * Version: 1.0.0
 * Author: CCS Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

// ---------------------------------------------------------------------------
// 1. CUSTOM POST TYPE
// ---------------------------------------------------------------------------

add_action('init', 'ccspro_register_landing_page_cpt');

function ccspro_register_landing_page_cpt() {
    register_post_type('landing_page', array(
        'labels' => array(
            'name' => 'Landing Pages',
            'singular_name' => 'Landing Page',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Landing Page',
            'edit_item' => 'Edit Landing Page',
            'new_item' => 'New Landing Page',
            'view_item' => 'View Landing Page',
            'search_items' => 'Search Landing Pages',
            'not_found' => 'No landing pages found',
            'not_found_in_trash' => 'No landing pages found in Trash',
        ),
        'public' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
        'menu_icon' => 'dashicons-welcome-view-site',
        'rewrite' => array('slug' => 'landing'),
    ));
}

// ---------------------------------------------------------------------------
// 2. COMING SOON MODE (site-config API + admin toggle)
// ---------------------------------------------------------------------------

add_action('admin_menu', 'ccspro_add_coming_soon_menu');

function ccspro_add_coming_soon_menu() {
    add_options_page(
        'CCS Pro Site',
        'CCS Pro Site',
        'manage_options',
        'ccspro-site',
        'ccspro_render_coming_soon_page'
    );
}

function ccspro_render_coming_soon_page() {
    if (isset($_POST['ccspro_coming_soon']) && current_user_can('manage_options')) {
        check_admin_referer('ccspro_coming_soon');
        $value = !empty($_POST['ccspro_coming_soon']) ? '1' : '0';
        update_option('ccspro_coming_soon', $value);
        echo '<div class="notice notice-success"><p>Coming soon mode ' . ($value === '1' ? 'enabled' : 'disabled') . '.</p></div>';
    }
    $current = get_option('ccspro_coming_soon', '0') === '1';
    ?>
    <div class="wrap">
        <h1>CCS Pro Site</h1>
        <form method="post" action="">
            <?php wp_nonce_field('ccspro_coming_soon'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Coming soon mode</th>
                    <td>
                        <label>
                            <input type="checkbox" name="ccspro_coming_soon" value="1" <?php checked($current); ?> />
                            Show "Coming Soon" page on the live site instead of the full landing page
                        </label>
                        <p class="description">The frontend (ccsprocert.com) fetches this setting at runtime. Toggling here takes effect immediately; no redeploy needed.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save'); ?>
        </form>
    </div>
    <?php
}

// ---------------------------------------------------------------------------
// 3. CORS HEADERS
// ---------------------------------------------------------------------------

add_action('rest_api_init', 'ccspro_add_cors_headers', 15);

function ccspro_add_cors_headers() {
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function ($value) {
        $origin = get_http_origin();
        $allowed = array(
            'https://ccsprocert.com',
            'https://www.ccsprocert.com',
            'http://localhost:5173',
            'http://127.0.0.1:5173',
        );
        if ($origin && in_array($origin, $allowed, true)) {
            header('Access-Control-Allow-Origin: ' . $origin);
        }
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Headers: Authorization, Content-Type');
        return $value;
    });
}

add_action('init', 'ccspro_handle_preflight');

function ccspro_handle_preflight() {
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS' && isset($_SERVER['HTTP_ORIGIN'])) {
        $allowed = array('https://ccsprocert.com', 'https://www.ccsprocert.com', 'http://localhost:5173', 'http://127.0.0.1:5173');
        if (in_array($_SERVER['HTTP_ORIGIN'], $allowed, true)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, OPTIONS');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Headers: Authorization, Content-Type');
            status_header(200);
            exit;
        }
    }
}

// ---------------------------------------------------------------------------
// 4. ACF FIELD GROUPS (requires ACF to be active)
// ---------------------------------------------------------------------------

add_action('acf/init', 'ccspro_register_acf_field_groups');

function ccspro_register_acf_field_groups() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    $groups = ccspro_get_field_group_config();
    foreach ($groups as $group) {
        acf_add_local_field_group($group);
    }
}

function ccspro_get_field_group_config() {
    $default_location = array(array(
        array('param' => 'post_type', 'operator' => '==', 'value' => 'landing_page'),
    ));

    return array(
        // ----- Site Config -----
        array(
            'key' => 'group_ccspro_site_config',
            'title' => 'Site Config',
            'fields' => array(
                array('key' => 'field_site_name', 'label' => 'Site Name', 'name' => 'site_name', 'type' => 'text', 'default_value' => 'CCS Pro'),
                array('key' => 'field_site_tagline', 'label' => 'Tagline', 'name' => 'site_tagline', 'type' => 'text'),
                array('key' => 'field_site_description', 'label' => 'Description', 'name' => 'site_description', 'type' => 'textarea'),
            ),
            'location' => $default_location,
        ),
        // ----- Navigation -----
        array(
            'key' => 'group_ccspro_nav',
            'title' => 'Navigation',
            'fields' => array(
                array(
                    'key' => 'field_nav_links',
                    'label' => 'Nav Links',
                    'name' => 'nav_links',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_nav_link_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
                        array('key' => 'field_nav_link_href', 'label' => 'Href', 'name' => 'href', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_nav_primary_label', 'label' => 'Primary CTA Label', 'name' => 'nav_primary_label', 'type' => 'text'),
                array('key' => 'field_nav_primary_href', 'label' => 'Primary CTA Href', 'name' => 'nav_primary_href', 'type' => 'text'),
                array('key' => 'field_nav_secondary_label', 'label' => 'Secondary CTA Label', 'name' => 'nav_secondary_label', 'type' => 'text'),
                array('key' => 'field_nav_secondary_href', 'label' => 'Secondary CTA Href', 'name' => 'nav_secondary_href', 'type' => 'text'),
                array('key' => 'field_nav_signin_label', 'label' => 'Sign In Label', 'name' => 'nav_signin_label', 'type' => 'text'),
                array('key' => 'field_nav_signin_href', 'label' => 'Sign In Href', 'name' => 'nav_signin_href', 'type' => 'text'),
            ),
            'location' => $default_location,
        ),
        // ----- Hero -----
        array(
            'key' => 'group_ccspro_hero',
            'title' => 'Hero Section',
            'fields' => array(
                array('key' => 'field_hero_headline', 'label' => 'Headline', 'name' => 'hero_headline', 'type' => 'text'),
                array('key' => 'field_hero_headline_highlight', 'label' => 'Headline Highlight', 'name' => 'hero_headline_highlight', 'type' => 'text'),
                array('key' => 'field_hero_subheadline', 'label' => 'Subheadline', 'name' => 'hero_subheadline', 'type' => 'textarea'),
                array('key' => 'field_hero_primary_label', 'label' => 'Primary CTA Label', 'name' => 'hero_primary_label', 'type' => 'text'),
                array('key' => 'field_hero_primary_href', 'label' => 'Primary CTA Href', 'name' => 'hero_primary_href', 'type' => 'text'),
                array('key' => 'field_hero_secondary_label', 'label' => 'Secondary CTA Label', 'name' => 'hero_secondary_label', 'type' => 'text'),
                array('key' => 'field_hero_secondary_href', 'label' => 'Secondary CTA Href', 'name' => 'hero_secondary_href', 'type' => 'text'),
                array('key' => 'field_hero_tertiary_label', 'label' => 'Tertiary CTA Label', 'name' => 'hero_tertiary_label', 'type' => 'text'),
                array('key' => 'field_hero_tertiary_href', 'label' => 'Tertiary CTA Href', 'name' => 'hero_tertiary_href', 'type' => 'text'),
                array(
                    'key' => 'field_hero_trust_indicators',
                    'label' => 'Trust Indicators',
                    'name' => 'hero_trust_indicators',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_hero_trust_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'Shield'),
                        array('key' => 'field_hero_trust_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_hero_dashboard_title', 'label' => 'Dashboard Title', 'name' => 'hero_dashboard_title', 'type' => 'text'),
                array('key' => 'field_hero_dashboard_subtitle', 'label' => 'Dashboard Subtitle', 'name' => 'hero_dashboard_subtitle', 'type' => 'text'),
                array('key' => 'field_hero_dashboard_completion', 'label' => 'Completion %', 'name' => 'hero_dashboard_completion', 'type' => 'number', 'default_value' => 92),
                array('key' => 'field_hero_dashboard_state', 'label' => 'State Value', 'name' => 'hero_dashboard_state', 'type' => 'text'),
                array('key' => 'field_hero_dashboard_npi', 'label' => 'NPI Value', 'name' => 'hero_dashboard_npi', 'type' => 'text'),
                array(
                    'key' => 'field_hero_dashboard_documents',
                    'label' => 'Dashboard Documents',
                    'name' => 'hero_dashboard_documents',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_hero_doc_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text'),
                        array('key' => 'field_hero_doc_status', 'label' => 'Status', 'name' => 'status', 'type' => 'text'),
                        array('key' => 'field_hero_doc_color', 'label' => 'Status Color', 'name' => 'status_color', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_hero_dashboard_btn_primary', 'label' => 'Button Primary', 'name' => 'hero_dashboard_btn_primary', 'type' => 'text'),
                array('key' => 'field_hero_dashboard_btn_secondary', 'label' => 'Button Secondary', 'name' => 'hero_dashboard_btn_secondary', 'type' => 'text'),
            ),
            'location' => $default_location,
        ),
        // ----- Verification / Logo Strip -----
        array(
            'key' => 'group_ccspro_verification',
            'title' => 'Verification / Logo Strip',
            'fields' => array(
                array('key' => 'field_verification_headline', 'label' => 'Headline', 'name' => 'verification_headline', 'type' => 'text'),
                array(
                    'key' => 'field_verification_items',
                    'label' => 'Items',
                    'name' => 'verification_items',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_verification_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text'),
                        array('key' => 'field_verification_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
                    ),
                ),
            ),
            'location' => $default_location,
        ),
        // ----- Founder Spotlight -----
        array(
            'key' => 'group_ccspro_founder',
            'title' => 'Founder Spotlight',
            'fields' => array(
                array('key' => 'field_founder_name', 'label' => 'Name', 'name' => 'founder_name', 'type' => 'text'),
                array('key' => 'field_founder_title', 'label' => 'Title', 'name' => 'founder_title', 'type' => 'text'),
                array('key' => 'field_founder_initials', 'label' => 'Initials', 'name' => 'founder_initials', 'type' => 'text'),
                array('key' => 'field_founder_quote', 'label' => 'Quote', 'name' => 'founder_quote', 'type' => 'textarea'),
                array(
                    'key' => 'field_founder_bullets',
                    'label' => 'Bullets',
                    'name' => 'founder_bullets',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_founder_bullet_text', 'label' => 'Text', 'name' => 'bullet_text', 'type' => 'text'),
                    ),
                ),
            ),
            'location' => $default_location,
        ),
        // ----- Problem / Outcome -----
        array(
            'key' => 'group_ccspro_problem_outcome',
            'title' => 'Problem / Outcome',
            'fields' => array(
                array(
                    'key' => 'field_problems',
                    'label' => 'Problems',
                    'name' => 'problems',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_problem_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text'),
                        array('key' => 'field_problem_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
                        array('key' => 'field_problem_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea'),
                    ),
                ),
                array('key' => 'field_outcome_prefix', 'label' => 'Outcome Prefix', 'name' => 'outcome_prefix', 'type' => 'text'),
                array('key' => 'field_outcome_middle', 'label' => 'Outcome Middle', 'name' => 'outcome_middle', 'type' => 'text'),
                array('key' => 'field_outcome_suffix', 'label' => 'Outcome Suffix', 'name' => 'outcome_suffix', 'type' => 'text'),
            ),
            'location' => $default_location,
        ),
        // ----- How It Works -----
        array(
            'key' => 'group_ccspro_how_it_works',
            'title' => 'How It Works',
            'fields' => array(
                array('key' => 'field_how_title', 'label' => 'Section Title', 'name' => 'how_it_works_title', 'type' => 'text'),
                array('key' => 'field_how_subtitle', 'label' => 'Section Subtitle', 'name' => 'how_it_works_subtitle', 'type' => 'text'),
                array(
                    'key' => 'field_how_steps',
                    'label' => 'Steps',
                    'name' => 'how_it_works_steps',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_how_step_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text'),
                        array('key' => 'field_how_step_number', 'label' => 'Step Number', 'name' => 'step_number', 'type' => 'text', 'placeholder' => '01'),
                        array('key' => 'field_how_step_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
                        array('key' => 'field_how_step_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea'),
                    ),
                ),
                array('key' => 'field_how_readiness_label', 'label' => 'Readiness Note Label', 'name' => 'how_readiness_label', 'type' => 'text'),
                array(
                    'key' => 'field_how_readiness_states',
                    'label' => 'Readiness States',
                    'name' => 'how_readiness_states',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_how_state_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
                        array('key' => 'field_how_state_color', 'label' => 'Color', 'name' => 'color', 'type' => 'text'),
                    ),
                ),
            ),
            'location' => $default_location,
        ),
        // ----- Features -----
        array(
            'key' => 'group_ccspro_features',
            'title' => 'Features',
            'fields' => array(
                array('key' => 'field_features_title', 'label' => 'Section Title', 'name' => 'features_title', 'type' => 'text'),
                array('key' => 'field_features_subtitle', 'label' => 'Section Subtitle', 'name' => 'features_subtitle', 'type' => 'text'),
                array(
                    'key' => 'field_features_items',
                    'label' => 'Features',
                    'name' => 'features_items',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_feature_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text'),
                        array('key' => 'field_feature_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
                        array('key' => 'field_feature_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea'),
                        array('key' => 'field_feature_link', 'label' => 'Link', 'name' => 'link', 'type' => 'text'),
                    ),
                ),
            ),
            'location' => $default_location,
        ),
        // ----- Packet Preview -----
        array(
            'key' => 'group_ccspro_packet',
            'title' => 'Packet Preview',
            'fields' => array(
                array('key' => 'field_packet_title', 'label' => 'Section Title', 'name' => 'packet_title', 'type' => 'text'),
                array('key' => 'field_packet_subtitle', 'label' => 'Section Subtitle', 'name' => 'packet_subtitle', 'type' => 'text'),
                array('key' => 'field_packet_filename', 'label' => 'File Name', 'name' => 'packet_filename', 'type' => 'text'),
                array(
                    'key' => 'field_packet_checklist',
                    'label' => 'Checklist',
                    'name' => 'packet_checklist',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_packet_check_item', 'label' => 'Item', 'name' => 'item_text', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_packet_cta_label', 'label' => 'CTA Label', 'name' => 'packet_cta_label', 'type' => 'text'),
                array('key' => 'field_packet_cta_href', 'label' => 'CTA Href', 'name' => 'packet_cta_href', 'type' => 'text'),
            ),
            'location' => $default_location,
        ),
        // ----- Security -----
        array(
            'key' => 'group_ccspro_security',
            'title' => 'Security',
            'fields' => array(
                array('key' => 'field_security_badge', 'label' => 'Badge', 'name' => 'security_badge', 'type' => 'text'),
                array('key' => 'field_security_title', 'label' => 'Title', 'name' => 'security_title', 'type' => 'text'),
                array('key' => 'field_security_subtitle', 'label' => 'Subtitle', 'name' => 'security_subtitle', 'type' => 'textarea'),
                array(
                    'key' => 'field_security_features',
                    'label' => 'Features',
                    'name' => 'security_features',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_security_feat_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text'),
                        array('key' => 'field_security_feat_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_security_cta_label', 'label' => 'CTA Label', 'name' => 'security_cta_label', 'type' => 'text'),
                array('key' => 'field_security_cta_href', 'label' => 'CTA Href', 'name' => 'security_cta_href', 'type' => 'text'),
                array(
                    'key' => 'field_security_floating_badges',
                    'label' => 'Floating Badges',
                    'name' => 'security_floating_badges',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_security_badge_text', 'label' => 'Badge Text', 'name' => 'badge_text', 'type' => 'text'),
                    ),
                ),
            ),
            'location' => $default_location,
        ),
        // ----- CAQH Concierge -----
        array(
            'key' => 'group_ccspro_caqh',
            'title' => 'CAQH Concierge',
            'fields' => array(
                array('key' => 'field_caqh_badge', 'label' => 'Badge', 'name' => 'caqh_badge', 'type' => 'text'),
                array('key' => 'field_caqh_title', 'label' => 'Title', 'name' => 'caqh_title', 'type' => 'text'),
                array('key' => 'field_caqh_subtitle', 'label' => 'Subtitle', 'name' => 'caqh_subtitle', 'type' => 'textarea'),
                array('key' => 'field_caqh_benefits_title', 'label' => 'Benefits Title', 'name' => 'caqh_benefits_title', 'type' => 'text'),
                array(
                    'key' => 'field_caqh_benefits',
                    'label' => 'Benefits',
                    'name' => 'caqh_benefits',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_caqh_benefit_text', 'label' => 'Text', 'name' => 'benefit_text', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_caqh_cta_label', 'label' => 'CTA Label', 'name' => 'caqh_cta_label', 'type' => 'text'),
                array('key' => 'field_caqh_cta_href', 'label' => 'CTA Href', 'name' => 'caqh_cta_href', 'type' => 'text'),
                array('key' => 'field_caqh_consent_title', 'label' => 'Consent Title', 'name' => 'caqh_consent_title', 'type' => 'text'),
                array(
                    'key' => 'field_caqh_consent_modes',
                    'label' => 'Consent Modes',
                    'name' => 'caqh_consent_modes',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_caqh_consent_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text'),
                        array('key' => 'field_caqh_consent_title_f', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
                        array('key' => 'field_caqh_consent_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea'),
                    ),
                ),
                array('key' => 'field_caqh_always_icon', 'label' => 'Always Included Icon', 'name' => 'caqh_always_icon', 'type' => 'text'),
                array('key' => 'field_caqh_always_title', 'label' => 'Always Included Title', 'name' => 'caqh_always_title', 'type' => 'text'),
                array('key' => 'field_caqh_always_description', 'label' => 'Always Included Description', 'name' => 'caqh_always_description', 'type' => 'text'),
            ),
            'location' => $default_location,
        ),
        // ----- Pricing -----
        array(
            'key' => 'group_ccspro_pricing',
            'title' => 'Pricing',
            'fields' => array(
                array('key' => 'field_pricing_title', 'label' => 'Section Title', 'name' => 'pricing_title', 'type' => 'text'),
                array('key' => 'field_pricing_subtitle', 'label' => 'Section Subtitle', 'name' => 'pricing_subtitle', 'type' => 'text'),
                array(
                    'key' => 'field_pricing_plans',
                    'label' => 'Plans',
                    'name' => 'pricing_plans',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_plan_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text'),
                        array('key' => 'field_plan_price', 'label' => 'Price', 'name' => 'price', 'type' => 'text'),
                        array('key' => 'field_plan_period', 'label' => 'Period', 'name' => 'period', 'type' => 'text'),
                        array('key' => 'field_plan_yearly_price', 'label' => 'Yearly Price', 'name' => 'yearly_price', 'type' => 'text'),
                        array('key' => 'field_plan_yearly_label', 'label' => 'Yearly Label', 'name' => 'yearly_label', 'type' => 'text'),
                        array('key' => 'field_plan_description', 'label' => 'Description', 'name' => 'description', 'type' => 'text'),
                        array(
                            'key' => 'field_plan_features',
                            'label' => 'Features',
                            'name' => 'features',
                            'type' => 'repeater',
                            'sub_fields' => array(
                                array('key' => 'field_plan_feature_text', 'label' => 'Feature', 'name' => 'feature_text', 'type' => 'text'),
                            ),
                        ),
                        array('key' => 'field_plan_cta', 'label' => 'CTA', 'name' => 'cta', 'type' => 'text'),
                        array('key' => 'field_plan_highlighted', 'label' => 'Highlighted', 'name' => 'highlighted', 'type' => 'true_false', 'default_value' => 0),
                        array('key' => 'field_plan_badge', 'label' => 'Badge', 'name' => 'badge', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_pricing_update_price', 'label' => 'Update Price Text', 'name' => 'pricing_update_price', 'type' => 'text'),
                array('key' => 'field_pricing_refund_policy', 'label' => 'Refund Policy', 'name' => 'pricing_refund_policy', 'type' => 'textarea'),
                array('key' => 'field_pricing_refund_label', 'label' => 'Refund Link Label', 'name' => 'pricing_refund_label', 'type' => 'text'),
                array('key' => 'field_pricing_refund_href', 'label' => 'Refund Link Href', 'name' => 'pricing_refund_href', 'type' => 'text'),
            ),
            'location' => $default_location,
        ),
        // ----- Support -----
        array(
            'key' => 'group_ccspro_support',
            'title' => 'Support',
            'fields' => array(
                array('key' => 'field_support_title', 'label' => 'Section Title', 'name' => 'support_title', 'type' => 'text'),
                array('key' => 'field_support_subtitle', 'label' => 'Section Subtitle', 'name' => 'support_subtitle', 'type' => 'text'),
                array(
                    'key' => 'field_support_features',
                    'label' => 'Features',
                    'name' => 'support_features',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_support_feat_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text'),
                        array('key' => 'field_support_feat_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text'),
                    ),
                ),
                array(
                    'key' => 'field_support_links',
                    'label' => 'Links',
                    'name' => 'support_links',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_support_link_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
                        array('key' => 'field_support_link_href', 'label' => 'Href', 'name' => 'href', 'type' => 'text'),
                    ),
                ),
            ),
            'location' => $default_location,
        ),
        // ----- Team -----
        array(
            'key' => 'group_ccspro_team',
            'title' => 'Team',
            'fields' => array(
                array('key' => 'field_team_title', 'label' => 'Section Title', 'name' => 'team_title', 'type' => 'text'),
                array('key' => 'field_team_subtitle', 'label' => 'Section Subtitle', 'name' => 'team_subtitle', 'type' => 'text'),
                array(
                    'key' => 'field_team_members',
                    'label' => 'Members',
                    'name' => 'team_members',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_team_member_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text'),
                        array('key' => 'field_team_member_role', 'label' => 'Role', 'name' => 'role', 'type' => 'text'),
                        array('key' => 'field_team_member_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text'),
                        array('key' => 'field_team_member_bio', 'label' => 'Bio', 'name' => 'bio', 'type' => 'textarea'),
                    ),
                ),
            ),
            'location' => $default_location,
        ),
        // ----- FAQ -----
        array(
            'key' => 'group_ccspro_faq',
            'title' => 'FAQ',
            'fields' => array(
                array('key' => 'field_faq_title', 'label' => 'Section Title', 'name' => 'faq_title', 'type' => 'text'),
                array('key' => 'field_faq_subtitle', 'label' => 'Section Subtitle', 'name' => 'faq_subtitle', 'type' => 'text'),
                array(
                    'key' => 'field_faq_items',
                    'label' => 'FAQ Items',
                    'name' => 'faq_items',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_faq_question', 'label' => 'Question', 'name' => 'question', 'type' => 'text'),
                        array('key' => 'field_faq_answer', 'label' => 'Answer', 'name' => 'answer', 'type' => 'wysiwyg'),
                    ),
                ),
            ),
            'location' => $default_location,
        ),
        // ----- Final CTA -----
        array(
            'key' => 'group_ccspro_final_cta',
            'title' => 'Final CTA',
            'fields' => array(
                array('key' => 'field_final_cta_headline', 'label' => 'Headline', 'name' => 'final_cta_headline', 'type' => 'text'),
                array('key' => 'field_final_cta_subheadline', 'label' => 'Subheadline', 'name' => 'final_cta_subheadline', 'type' => 'text'),
                array('key' => 'field_final_cta_primary_label', 'label' => 'Primary CTA Label', 'name' => 'final_cta_primary_label', 'type' => 'text'),
                array('key' => 'field_final_cta_primary_href', 'label' => 'Primary CTA Href', 'name' => 'final_cta_primary_href', 'type' => 'text'),
                array('key' => 'field_final_cta_secondary_label', 'label' => 'Secondary CTA Label', 'name' => 'final_cta_secondary_label', 'type' => 'text'),
                array('key' => 'field_final_cta_secondary_href', 'label' => 'Secondary CTA Href', 'name' => 'final_cta_secondary_href', 'type' => 'text'),
            ),
            'location' => $default_location,
        ),
        // ----- Footer -----
        array(
            'key' => 'group_ccspro_footer',
            'title' => 'Footer',
            'fields' => array(
                array('key' => 'field_footer_brand_name', 'label' => 'Brand Name', 'name' => 'footer_brand_name', 'type' => 'text'),
                array('key' => 'field_footer_brand_description', 'label' => 'Brand Description', 'name' => 'footer_brand_description', 'type' => 'textarea'),
                array(
                    'key' => 'field_footer_trust_badges',
                    'label' => 'Trust Badges',
                    'name' => 'footer_trust_badges',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_footer_badge_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text'),
                        array('key' => 'field_footer_badge_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text'),
                    ),
                ),
                array(
                    'key' => 'field_footer_legal_links',
                    'label' => 'Legal Links',
                    'name' => 'footer_legal_links',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_footer_legal_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
                        array('key' => 'field_footer_legal_href', 'label' => 'Href', 'name' => 'href', 'type' => 'text'),
                    ),
                ),
                array(
                    'key' => 'field_footer_support_links',
                    'label' => 'Support Links',
                    'name' => 'footer_support_links',
                    'type' => 'repeater',
                    'sub_fields' => array(
                        array('key' => 'field_footer_support_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'),
                        array('key' => 'field_footer_support_href', 'label' => 'Href', 'name' => 'href', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_footer_copyright', 'label' => 'Copyright', 'name' => 'footer_copyright', 'type' => 'text'),
            ),
            'location' => $default_location,
        ),
    );
}

// ---------------------------------------------------------------------------
// 4. REST API ENDPOINT
// ---------------------------------------------------------------------------

add_action('rest_api_init', 'ccspro_register_rest_routes');

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
}

function ccspro_rest_get_site_config($request) {
    $coming_soon = get_option('ccspro_coming_soon', '0') === '1';
    return rest_ensure_response(array('comingSoon' => $coming_soon));
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

    $pricing_plans_raw = get_field('pricing_plans', $post_id) ?: array();
    $pricing_plans = array();
    foreach ($pricing_plans_raw as $plan) {
        $feats = isset($plan['features']) && is_array($plan['features']) ? $plan['features'] : array();
        $feats = array_map(function ($f) {
            return isset($f['feature_text']) ? $f['feature_text'] : '';
        }, $feats);
        $pricing_plans[] = array(
            'name' => isset($plan['name']) ? $plan['name'] : '',
            'price' => isset($plan['price']) ? $plan['price'] : '',
            'period' => isset($plan['period']) ? $plan['period'] : '',
            'description' => isset($plan['description']) ? $plan['description'] : '',
            'features' => $feats,
            'cta' => isset($plan['cta']) ? $plan['cta'] : '',
            'highlighted' => !empty($plan['highlighted']),
            'badge' => isset($plan['badge']) ? $plan['badge'] : null,
            'yearlyPrice' => isset($plan['yearly_price']) ? $plan['yearly_price'] : null,
            'yearlyLabel' => isset($plan['yearly_label']) ? $plan['yearly_label'] : null,
        );
    }

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

    $footer_legal = get_field('footer_legal_links', $post_id) ?: array();
    $footer_legal = array_map(function ($row) {
        return array('label' => isset($row['label']) ? $row['label'] : '', 'href' => isset($row['href']) ? $row['href'] : '#');
    }, $footer_legal);

    $footer_support = get_field('footer_support_links', $post_id) ?: array();
    $footer_support = array_map(function ($row) {
        return array('label' => isset($row['label']) ? $row['label'] : '', 'href' => isset($row['href']) ? $row['href'] : '#');
    }, $footer_support);

    $footer_trust = get_field('footer_trust_badges', $post_id) ?: array();
    $footer_trust = array_map(function ($row) {
        return array('icon' => isset($row['icon']) ? $row['icon'] : 'Circle', 'text' => isset($row['text']) ? $row['text'] : '');
    }, $footer_trust);

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
            'readinessNote' => array(
                'label' => get_field('how_readiness_label', $post_id) ?: '5 Readiness States:',
                'states' => $readiness_states,
            ),
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
        'pricingContent' => array(
            'sectionTitle' => get_field('pricing_title', $post_id) ?: 'Simple, transparent pricing',
            'sectionSubtitle' => get_field('pricing_subtitle', $post_id) ?: '',
            'plans' => $pricing_plans,
            'additionalInfo' => array(
                'updatePrice' => get_field('pricing_update_price', $post_id) ?: '',
                'refundPolicy' => get_field('pricing_refund_policy', $post_id) ?: '',
                'refundLink' => array(
                    'label' => get_field('pricing_refund_label', $post_id) ?: '',
                    'href' => get_field('pricing_refund_href', $post_id) ?: '#',
                ),
            ),
        ),
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
        'footerContent' => array(
            'brand' => array(
                'name' => get_field('footer_brand_name', $post_id) ?: 'CCS Pro',
                'description' => get_field('footer_brand_description', $post_id) ?: '',
            ),
            'trustBadges' => $footer_trust,
            'links' => array('legal' => $footer_legal, 'support' => $footer_support),
            'copyright' => get_field('footer_copyright', $post_id) ?: 'CCS Pro. All rights reserved.',
        ),
    );
}
