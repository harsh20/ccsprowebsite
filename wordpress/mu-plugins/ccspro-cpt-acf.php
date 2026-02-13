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
    // Form submitted: nonce is always sent; checkbox is only sent when checked
    if (isset($_POST['_wpnonce']) && current_user_can('manage_options')) {
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
add_action('acf/init', 'ccspro_register_acf_options_pages');

function ccspro_register_acf_options_pages() {
    if (!function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page(array(
        'page_title' => 'CCS Pro Pricing Settings',
        'menu_title' => 'CCS Pro Pricing',
        'menu_slug' => 'ccspro-pricing-settings',
        'capability' => 'manage_options',
        'redirect' => false,
        'position' => 61,
    ));
}

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

    // Single consolidated field group with tabs for clean UX
    return array(
        array(
            'key' => 'group_ccspro_landing_page',
            'title' => 'Landing Page Content',
            'fields' => array(
                // =====================================================================
                // TAB: General
                // =====================================================================
                array(
                    'key' => 'field_tab_general',
                    'label' => 'General',
                    'type' => 'tab',
                    'placement' => 'top',
                ),
                // ----- Site Config -----
                array(
                    'key' => 'field_accordion_site_config',
                    'label' => 'Site Config',
                    'type' => 'accordion',
                    'open' => 1,
                    'multi_expand' => 1,
                ),
                array(
                    'key' => 'field_site_name',
                    'label' => 'Site Name',
                    'name' => 'site_name',
                    'type' => 'text',
                    'default_value' => 'CCS Pro',
                    'wrapper' => array('width' => '33'),
                ),
                array(
                    'key' => 'field_site_tagline',
                    'label' => 'Tagline',
                    'name' => 'site_tagline',
                    'type' => 'text',
                    'wrapper' => array('width' => '67'),
                ),
                array(
                    'key' => 'field_site_description',
                    'label' => 'Description',
                    'name' => 'site_description',
                    'type' => 'textarea',
                    'rows' => 2,
                ),
                // ----- Navigation -----
                array(
                    'key' => 'field_accordion_navigation',
                    'label' => 'Navigation',
                    'type' => 'accordion',
                    'open' => 0,
                    'multi_expand' => 1,
                ),
                array(
                    'key' => 'field_nav_links',
                    'label' => 'Nav Links',
                    'name' => 'nav_links',
                    'type' => 'repeater',
                    'instructions' => 'Menu items shown in the header navigation.',
                    'layout' => 'table',
                    'button_label' => 'Add Link',
                    'sub_fields' => array(
                        array('key' => 'field_nav_link_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                        array('key' => 'field_nav_link_href', 'label' => 'Href', 'name' => 'href', 'type' => 'text', 'placeholder' => '#section', 'wrapper' => array('width' => '50')),
                    ),
                ),
                array(
                    'key' => 'field_nav_ctas_message',
                    'label' => '',
                    'type' => 'message',
                    'message' => '<strong>Header Buttons</strong>',
                ),
                array('key' => 'field_nav_primary_label', 'label' => 'Primary CTA Label', 'name' => 'nav_primary_label', 'type' => 'text', 'wrapper' => array('width' => '50'), 'placeholder' => 'Start free'),
                array('key' => 'field_nav_primary_href', 'label' => 'Primary CTA Href', 'name' => 'nav_primary_href', 'type' => 'text', 'wrapper' => array('width' => '50'), 'placeholder' => '#pricing'),
                array('key' => 'field_nav_secondary_label', 'label' => 'Secondary CTA Label', 'name' => 'nav_secondary_label', 'type' => 'text', 'wrapper' => array('width' => '50'), 'placeholder' => 'Book a demo'),
                array('key' => 'field_nav_secondary_href', 'label' => 'Secondary CTA Href', 'name' => 'nav_secondary_href', 'type' => 'text', 'wrapper' => array('width' => '50'), 'placeholder' => '#demo'),
                array('key' => 'field_nav_signin_label', 'label' => 'Sign In Label', 'name' => 'nav_signin_label', 'type' => 'text', 'wrapper' => array('width' => '50'), 'placeholder' => 'Sign in'),
                array('key' => 'field_nav_signin_href', 'label' => 'Sign In Href', 'name' => 'nav_signin_href', 'type' => 'text', 'wrapper' => array('width' => '50'), 'placeholder' => '/login'),
                array(
                    'key' => 'field_accordion_navigation_end',
                    'label' => 'Navigation End',
                    'type' => 'accordion',
                    'endpoint' => 1,
                ),

                // =====================================================================
                // TAB: Hero
                // =====================================================================
                array(
                    'key' => 'field_tab_hero',
                    'label' => 'Hero',
                    'type' => 'tab',
                    'placement' => 'top',
                ),
                // ----- Hero Content -----
                array(
                    'key' => 'field_accordion_hero_content',
                    'label' => 'Hero Content',
                    'type' => 'accordion',
                    'open' => 1,
                    'multi_expand' => 1,
                ),
                array('key' => 'field_hero_headline', 'label' => 'Headline', 'name' => 'hero_headline', 'type' => 'text', 'wrapper' => array('width' => '60')),
                array('key' => 'field_hero_headline_highlight', 'label' => 'Highlight Word', 'name' => 'hero_headline_highlight', 'type' => 'text', 'instructions' => 'Word to highlight in the headline', 'wrapper' => array('width' => '40')),
                array('key' => 'field_hero_subheadline', 'label' => 'Subheadline', 'name' => 'hero_subheadline', 'type' => 'textarea', 'rows' => 2),
                array(
                    'key' => 'field_hero_ctas_message',
                    'label' => '',
                    'type' => 'message',
                    'message' => '<strong>Hero Buttons</strong>',
                ),
                array('key' => 'field_hero_primary_label', 'label' => 'Primary CTA Label', 'name' => 'hero_primary_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_hero_primary_href', 'label' => 'Primary CTA Href', 'name' => 'hero_primary_href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_hero_secondary_label', 'label' => 'Secondary CTA Label', 'name' => 'hero_secondary_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_hero_secondary_href', 'label' => 'Secondary CTA Href', 'name' => 'hero_secondary_href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_hero_tertiary_label', 'label' => 'Tertiary CTA Label', 'name' => 'hero_tertiary_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_hero_tertiary_href', 'label' => 'Tertiary CTA Href', 'name' => 'hero_tertiary_href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array(
                    'key' => 'field_hero_trust_indicators',
                    'label' => 'Trust Indicators',
                    'name' => 'hero_trust_indicators',
                    'type' => 'repeater',
                    'instructions' => 'Small trust badges below hero buttons (e.g., "HIPAA Compliant").',
                    'layout' => 'table',
                    'button_label' => 'Add Indicator',
                    'sub_fields' => array(
                        array('key' => 'field_hero_trust_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'Shield', 'wrapper' => array('width' => '30')),
                        array('key' => 'field_hero_trust_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text', 'wrapper' => array('width' => '70')),
                    ),
                ),
                // ----- Hero Dashboard -----
                array(
                    'key' => 'field_accordion_hero_dashboard',
                    'label' => 'Dashboard Preview',
                    'type' => 'accordion',
                    'open' => 0,
                    'multi_expand' => 1,
                ),
                array('key' => 'field_hero_dashboard_title', 'label' => 'Dashboard Title', 'name' => 'hero_dashboard_title', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_hero_dashboard_subtitle', 'label' => 'Dashboard Subtitle', 'name' => 'hero_dashboard_subtitle', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_hero_dashboard_completion', 'label' => 'Completion %', 'name' => 'hero_dashboard_completion', 'type' => 'number', 'default_value' => 92, 'wrapper' => array('width' => '33')),
                array('key' => 'field_hero_dashboard_state', 'label' => 'State Value', 'name' => 'hero_dashboard_state', 'type' => 'text', 'placeholder' => 'California', 'wrapper' => array('width' => '33')),
                array('key' => 'field_hero_dashboard_npi', 'label' => 'NPI Value', 'name' => 'hero_dashboard_npi', 'type' => 'text', 'placeholder' => '1234567890', 'wrapper' => array('width' => '34')),
                array(
                    'key' => 'field_hero_dashboard_documents',
                    'label' => 'Dashboard Documents',
                    'name' => 'hero_dashboard_documents',
                    'type' => 'repeater',
                    'instructions' => 'Sample documents shown in the dashboard preview.',
                    'layout' => 'table',
                    'button_label' => 'Add Document',
                    'sub_fields' => array(
                        array('key' => 'field_hero_doc_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text', 'wrapper' => array('width' => '40')),
                        array('key' => 'field_hero_doc_status', 'label' => 'Status', 'name' => 'status', 'type' => 'text', 'placeholder' => 'Complete', 'wrapper' => array('width' => '30')),
                        array('key' => 'field_hero_doc_color', 'label' => 'Color', 'name' => 'status_color', 'type' => 'select', 'choices' => array('green' => 'Green', 'blue' => 'Blue', 'orange' => 'Orange', 'red' => 'Red', 'gray' => 'Gray'), 'default_value' => 'gray', 'wrapper' => array('width' => '30')),
                    ),
                ),
                array('key' => 'field_hero_dashboard_btn_primary', 'label' => 'Button Primary', 'name' => 'hero_dashboard_btn_primary', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_hero_dashboard_btn_secondary', 'label' => 'Button Secondary', 'name' => 'hero_dashboard_btn_secondary', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array(
                    'key' => 'field_accordion_hero_end',
                    'label' => 'Hero End',
                    'type' => 'accordion',
                    'endpoint' => 1,
                ),

                // =====================================================================
                // TAB: Story
                // =====================================================================
                array(
                    'key' => 'field_tab_story',
                    'label' => 'Story',
                    'type' => 'tab',
                    'placement' => 'top',
                ),
                // ----- Verification / Logo Strip -----
                array(
                    'key' => 'field_accordion_verification',
                    'label' => 'Verification / Logo Strip',
                    'type' => 'accordion',
                    'open' => 1,
                    'multi_expand' => 1,
                ),
                array('key' => 'field_verification_headline', 'label' => 'Headline', 'name' => 'verification_headline', 'type' => 'text'),
                array(
                    'key' => 'field_verification_items',
                    'label' => 'Verification Items',
                    'name' => 'verification_items',
                    'type' => 'repeater',
                    'instructions' => 'Logos/badges shown below the hero (e.g., DEA, State Medical Board).',
                    'layout' => 'table',
                    'button_label' => 'Add Item',
                    'sub_fields' => array(
                        array('key' => 'field_verification_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'Award', 'wrapper' => array('width' => '30')),
                        array('key' => 'field_verification_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text', 'wrapper' => array('width' => '70')),
                    ),
                ),
                // ----- Founder Spotlight -----
                array(
                    'key' => 'field_accordion_founder',
                    'label' => 'Founder Spotlight',
                    'type' => 'accordion',
                    'open' => 0,
                    'multi_expand' => 1,
                ),
                array('key' => 'field_founder_name', 'label' => 'Name', 'name' => 'founder_name', 'type' => 'text', 'wrapper' => array('width' => '40')),
                array('key' => 'field_founder_title', 'label' => 'Title', 'name' => 'founder_title', 'type' => 'text', 'wrapper' => array('width' => '40')),
                array('key' => 'field_founder_initials', 'label' => 'Initials', 'name' => 'founder_initials', 'type' => 'text', 'wrapper' => array('width' => '20'), 'placeholder' => 'DR'),
                array('key' => 'field_founder_quote', 'label' => 'Quote', 'name' => 'founder_quote', 'type' => 'textarea', 'rows' => 3),
                array(
                    'key' => 'field_founder_bullets',
                    'label' => 'Bullets',
                    'name' => 'founder_bullets',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Bullet',
                    'sub_fields' => array(
                        array('key' => 'field_founder_bullet_text', 'label' => 'Text', 'name' => 'bullet_text', 'type' => 'text'),
                    ),
                ),
                // ----- Problem / Outcome -----
                array(
                    'key' => 'field_accordion_problem_outcome',
                    'label' => 'Problem / Outcome',
                    'type' => 'accordion',
                    'open' => 0,
                    'multi_expand' => 1,
                ),
                array(
                    'key' => 'field_problems',
                    'label' => 'Problems',
                    'name' => 'problems',
                    'type' => 'repeater',
                    'instructions' => 'Pain points that CCS Pro solves.',
                    'layout' => 'block',
                    'button_label' => 'Add Problem',
                    'sub_fields' => array(
                        array('key' => 'field_problem_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'AlertTriangle', 'wrapper' => array('width' => '20')),
                        array('key' => 'field_problem_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text', 'wrapper' => array('width' => '80')),
                        array('key' => 'field_problem_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2),
                    ),
                ),
                array(
                    'key' => 'field_outcome_message',
                    'label' => '',
                    'type' => 'message',
                    'message' => '<strong>Outcome Statement</strong> — builds a sentence: "[Prefix] [Middle] [Suffix]"',
                ),
                array('key' => 'field_outcome_prefix', 'label' => 'Prefix', 'name' => 'outcome_prefix', 'type' => 'text', 'wrapper' => array('width' => '33')),
                array('key' => 'field_outcome_middle', 'label' => 'Middle (highlighted)', 'name' => 'outcome_middle', 'type' => 'text', 'wrapper' => array('width' => '34')),
                array('key' => 'field_outcome_suffix', 'label' => 'Suffix', 'name' => 'outcome_suffix', 'type' => 'text', 'wrapper' => array('width' => '33')),
                array(
                    'key' => 'field_accordion_story_end',
                    'label' => 'Story End',
                    'type' => 'accordion',
                    'endpoint' => 1,
                ),

                // =====================================================================
                // TAB: How It Works
                // =====================================================================
                array(
                    'key' => 'field_tab_how_it_works',
                    'label' => 'How It Works',
                    'type' => 'tab',
                    'placement' => 'top',
                ),
                array('key' => 'field_how_title', 'label' => 'Section Title', 'name' => 'how_it_works_title', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_how_subtitle', 'label' => 'Section Subtitle', 'name' => 'how_it_works_subtitle', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array(
                    'key' => 'field_how_steps',
                    'label' => 'Steps',
                    'name' => 'how_it_works_steps',
                    'type' => 'repeater',
                    'instructions' => 'The step-by-step process.',
                    'layout' => 'block',
                    'button_label' => 'Add Step',
                    'sub_fields' => array(
                        array('key' => 'field_how_step_number', 'label' => 'Step #', 'name' => 'step_number', 'type' => 'text', 'placeholder' => '01', 'wrapper' => array('width' => '15')),
                        array('key' => 'field_how_step_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'Upload', 'wrapper' => array('width' => '20')),
                        array('key' => 'field_how_step_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text', 'wrapper' => array('width' => '65')),
                        array('key' => 'field_how_step_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2),
                    ),
                ),
                array('key' => 'field_how_readiness_label', 'label' => 'Readiness Note Label', 'name' => 'how_readiness_label', 'type' => 'text', 'placeholder' => '5 Readiness States:'),
                array(
                    'key' => 'field_how_readiness_states',
                    'label' => 'Readiness States',
                    'name' => 'how_readiness_states',
                    'type' => 'repeater',
                    'instructions' => 'Status indicators for document readiness.',
                    'layout' => 'table',
                    'button_label' => 'Add State',
                    'sub_fields' => array(
                        array('key' => 'field_how_state_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text', 'wrapper' => array('width' => '60')),
                        array('key' => 'field_how_state_color', 'label' => 'Color', 'name' => 'color', 'type' => 'select', 'choices' => array('red' => 'Red', 'orange' => 'Orange', 'blue' => 'Blue', 'green' => 'Green', 'gray' => 'Gray'), 'wrapper' => array('width' => '40')),
                    ),
                ),

                // =====================================================================
                // TAB: Features
                // =====================================================================
                array(
                    'key' => 'field_tab_features',
                    'label' => 'Features',
                    'type' => 'tab',
                    'placement' => 'top',
                ),
                // ----- Features Grid -----
                array(
                    'key' => 'field_accordion_features',
                    'label' => 'Features Grid',
                    'type' => 'accordion',
                    'open' => 1,
                    'multi_expand' => 1,
                ),
                array('key' => 'field_features_title', 'label' => 'Section Title', 'name' => 'features_title', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_features_subtitle', 'label' => 'Section Subtitle', 'name' => 'features_subtitle', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array(
                    'key' => 'field_features_items',
                    'label' => 'Features',
                    'name' => 'features_items',
                    'type' => 'repeater',
                    'layout' => 'block',
                    'button_label' => 'Add Feature',
                    'sub_fields' => array(
                        array('key' => 'field_feature_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'FileCheck', 'wrapper' => array('width' => '20')),
                        array('key' => 'field_feature_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text', 'wrapper' => array('width' => '40')),
                        array('key' => 'field_feature_link', 'label' => 'Link', 'name' => 'link', 'type' => 'text', 'placeholder' => '#', 'wrapper' => array('width' => '40')),
                        array('key' => 'field_feature_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2),
                    ),
                ),
                // ----- Packet Preview -----
                array(
                    'key' => 'field_accordion_packet',
                    'label' => 'Packet Preview',
                    'type' => 'accordion',
                    'open' => 0,
                    'multi_expand' => 1,
                ),
                array('key' => 'field_packet_title', 'label' => 'Section Title', 'name' => 'packet_title', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_packet_subtitle', 'label' => 'Section Subtitle', 'name' => 'packet_subtitle', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_packet_filename', 'label' => 'File Name', 'name' => 'packet_filename', 'type' => 'text', 'placeholder' => 'credential-packet.pdf'),
                array(
                    'key' => 'field_packet_checklist',
                    'label' => 'Checklist',
                    'name' => 'packet_checklist',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Item',
                    'sub_fields' => array(
                        array('key' => 'field_packet_check_item', 'label' => 'Item', 'name' => 'item_text', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_packet_cta_label', 'label' => 'CTA Label', 'name' => 'packet_cta_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_packet_cta_href', 'label' => 'CTA Href', 'name' => 'packet_cta_href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array(
                    'key' => 'field_accordion_features_end',
                    'label' => 'Features End',
                    'type' => 'accordion',
                    'endpoint' => 1,
                ),

                // =====================================================================
                // TAB: Security
                // =====================================================================
                array(
                    'key' => 'field_tab_security',
                    'label' => 'Security',
                    'type' => 'tab',
                    'placement' => 'top',
                ),
                // ----- Security Section -----
                array(
                    'key' => 'field_accordion_security',
                    'label' => 'Security Section',
                    'type' => 'accordion',
                    'open' => 1,
                    'multi_expand' => 1,
                ),
                array('key' => 'field_security_badge', 'label' => 'Badge', 'name' => 'security_badge', 'type' => 'text', 'wrapper' => array('width' => '33')),
                array('key' => 'field_security_title', 'label' => 'Title', 'name' => 'security_title', 'type' => 'text', 'wrapper' => array('width' => '67')),
                array('key' => 'field_security_subtitle', 'label' => 'Subtitle', 'name' => 'security_subtitle', 'type' => 'textarea', 'rows' => 2),
                array(
                    'key' => 'field_security_features',
                    'label' => 'Features',
                    'name' => 'security_features',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Feature',
                    'sub_fields' => array(
                        array('key' => 'field_security_feat_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'Shield', 'wrapper' => array('width' => '30')),
                        array('key' => 'field_security_feat_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text', 'wrapper' => array('width' => '70')),
                    ),
                ),
                array('key' => 'field_security_cta_label', 'label' => 'CTA Label', 'name' => 'security_cta_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_security_cta_href', 'label' => 'CTA Href', 'name' => 'security_cta_href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array(
                    'key' => 'field_security_floating_badges',
                    'label' => 'Floating Badges',
                    'name' => 'security_floating_badges',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Badge',
                    'sub_fields' => array(
                        array('key' => 'field_security_badge_text', 'label' => 'Badge Text', 'name' => 'badge_text', 'type' => 'text'),
                    ),
                ),
                // ----- CAQH Concierge -----
                array(
                    'key' => 'field_accordion_caqh',
                    'label' => 'CAQH Concierge',
                    'type' => 'accordion',
                    'open' => 0,
                    'multi_expand' => 1,
                ),
                array('key' => 'field_caqh_badge', 'label' => 'Badge', 'name' => 'caqh_badge', 'type' => 'text', 'wrapper' => array('width' => '33')),
                array('key' => 'field_caqh_title', 'label' => 'Title', 'name' => 'caqh_title', 'type' => 'text', 'wrapper' => array('width' => '67')),
                array('key' => 'field_caqh_subtitle', 'label' => 'Subtitle', 'name' => 'caqh_subtitle', 'type' => 'textarea', 'rows' => 2),
                array('key' => 'field_caqh_benefits_title', 'label' => 'Benefits Title', 'name' => 'caqh_benefits_title', 'type' => 'text'),
                array(
                    'key' => 'field_caqh_benefits',
                    'label' => 'Benefits',
                    'name' => 'caqh_benefits',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Benefit',
                    'sub_fields' => array(
                        array('key' => 'field_caqh_benefit_text', 'label' => 'Text', 'name' => 'benefit_text', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_caqh_cta_label', 'label' => 'CTA Label', 'name' => 'caqh_cta_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_caqh_cta_href', 'label' => 'CTA Href', 'name' => 'caqh_cta_href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_caqh_consent_title', 'label' => 'Consent Title', 'name' => 'caqh_consent_title', 'type' => 'text'),
                array(
                    'key' => 'field_caqh_consent_modes',
                    'label' => 'Consent Modes',
                    'name' => 'caqh_consent_modes',
                    'type' => 'repeater',
                    'layout' => 'block',
                    'button_label' => 'Add Mode',
                    'sub_fields' => array(
                        array('key' => 'field_caqh_consent_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text', 'wrapper' => array('width' => '20')),
                        array('key' => 'field_caqh_consent_title_f', 'label' => 'Title', 'name' => 'title', 'type' => 'text', 'wrapper' => array('width' => '80')),
                        array('key' => 'field_caqh_consent_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2),
                    ),
                ),
                array(
                    'key' => 'field_caqh_always_message',
                    'label' => '',
                    'type' => 'message',
                    'message' => '<strong>Always Included Note</strong>',
                ),
                array('key' => 'field_caqh_always_icon', 'label' => 'Icon', 'name' => 'caqh_always_icon', 'type' => 'text', 'wrapper' => array('width' => '20')),
                array('key' => 'field_caqh_always_title', 'label' => 'Title', 'name' => 'caqh_always_title', 'type' => 'text', 'wrapper' => array('width' => '80')),
                array('key' => 'field_caqh_always_description', 'label' => 'Description', 'name' => 'caqh_always_description', 'type' => 'text'),
                array(
                    'key' => 'field_accordion_security_end',
                    'label' => 'Security End',
                    'type' => 'accordion',
                    'endpoint' => 1,
                ),

                // =====================================================================
                // TAB: Pricing
                // =====================================================================
                array(
                    'key' => 'field_tab_pricing',
                    'label' => 'Pricing',
                    'type' => 'tab',
                    'placement' => 'top',
                ),
                array(
                    'key' => 'field_pricing_global_notice',
                    'label' => '',
                    'type' => 'message',
                    'message' => '<strong>Pricing is managed globally.</strong><br/>Use <em>CCS Pro Pricing</em> in the WordPress admin menu to update plans once for all landing pages.',
                ),

                // =====================================================================
                // TAB: Support
                // =====================================================================
                array(
                    'key' => 'field_tab_support',
                    'label' => 'Support',
                    'type' => 'tab',
                    'placement' => 'top',
                ),
                // ----- Support -----
                array(
                    'key' => 'field_accordion_support',
                    'label' => 'Support Section',
                    'type' => 'accordion',
                    'open' => 1,
                    'multi_expand' => 1,
                ),
                array('key' => 'field_support_title', 'label' => 'Section Title', 'name' => 'support_title', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_support_subtitle', 'label' => 'Section Subtitle', 'name' => 'support_subtitle', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array(
                    'key' => 'field_support_features',
                    'label' => 'Features',
                    'name' => 'support_features',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Feature',
                    'sub_fields' => array(
                        array('key' => 'field_support_feat_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'MessageCircle', 'wrapper' => array('width' => '30')),
                        array('key' => 'field_support_feat_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text', 'wrapper' => array('width' => '70')),
                    ),
                ),
                array(
                    'key' => 'field_support_links',
                    'label' => 'Links',
                    'name' => 'support_links',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Link',
                    'sub_fields' => array(
                        array('key' => 'field_support_link_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                        array('key' => 'field_support_link_href', 'label' => 'Href', 'name' => 'href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                    ),
                ),
                // ----- Team -----
                array(
                    'key' => 'field_accordion_team',
                    'label' => 'Team',
                    'type' => 'accordion',
                    'open' => 0,
                    'multi_expand' => 1,
                ),
                array('key' => 'field_team_title', 'label' => 'Section Title', 'name' => 'team_title', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_team_subtitle', 'label' => 'Section Subtitle', 'name' => 'team_subtitle', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array(
                    'key' => 'field_team_members',
                    'label' => 'Members',
                    'name' => 'team_members',
                    'type' => 'repeater',
                    'layout' => 'block',
                    'button_label' => 'Add Member',
                    'sub_fields' => array(
                        array('key' => 'field_team_member_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'User', 'wrapper' => array('width' => '15')),
                        array('key' => 'field_team_member_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text', 'wrapper' => array('width' => '35')),
                        array('key' => 'field_team_member_role', 'label' => 'Role', 'name' => 'role', 'type' => 'text', 'wrapper' => array('width' => '50')),
                        array('key' => 'field_team_member_bio', 'label' => 'Bio', 'name' => 'bio', 'type' => 'textarea', 'rows' => 2),
                    ),
                ),
                array(
                    'key' => 'field_accordion_support_end',
                    'label' => 'Support End',
                    'type' => 'accordion',
                    'endpoint' => 1,
                ),

                // =====================================================================
                // TAB: FAQ & CTA
                // =====================================================================
                array(
                    'key' => 'field_tab_faq_cta',
                    'label' => 'FAQ & CTA',
                    'type' => 'tab',
                    'placement' => 'top',
                ),
                // ----- FAQ -----
                array(
                    'key' => 'field_accordion_faq',
                    'label' => 'FAQ',
                    'type' => 'accordion',
                    'open' => 1,
                    'multi_expand' => 1,
                ),
                array('key' => 'field_faq_title', 'label' => 'Section Title', 'name' => 'faq_title', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_faq_subtitle', 'label' => 'Section Subtitle', 'name' => 'faq_subtitle', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array(
                    'key' => 'field_faq_items',
                    'label' => 'FAQ Items',
                    'name' => 'faq_items',
                    'type' => 'repeater',
                    'layout' => 'block',
                    'button_label' => 'Add FAQ',
                    'sub_fields' => array(
                        array('key' => 'field_faq_question', 'label' => 'Question', 'name' => 'question', 'type' => 'text'),
                        array('key' => 'field_faq_answer', 'label' => 'Answer', 'name' => 'answer', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0),
                    ),
                ),
                // ----- Final CTA -----
                array(
                    'key' => 'field_accordion_final_cta',
                    'label' => 'Final CTA',
                    'type' => 'accordion',
                    'open' => 0,
                    'multi_expand' => 1,
                ),
                array('key' => 'field_final_cta_headline', 'label' => 'Headline', 'name' => 'final_cta_headline', 'type' => 'text'),
                array('key' => 'field_final_cta_subheadline', 'label' => 'Subheadline', 'name' => 'final_cta_subheadline', 'type' => 'text'),
                array('key' => 'field_final_cta_primary_label', 'label' => 'Primary CTA Label', 'name' => 'final_cta_primary_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_final_cta_primary_href', 'label' => 'Primary CTA Href', 'name' => 'final_cta_primary_href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_final_cta_secondary_label', 'label' => 'Secondary CTA Label', 'name' => 'final_cta_secondary_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_final_cta_secondary_href', 'label' => 'Secondary CTA Href', 'name' => 'final_cta_secondary_href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array(
                    'key' => 'field_accordion_faq_cta_end',
                    'label' => 'FAQ CTA End',
                    'type' => 'accordion',
                    'endpoint' => 1,
                ),

                // =====================================================================
                // TAB: Footer
                // =====================================================================
                array(
                    'key' => 'field_tab_footer',
                    'label' => 'Footer',
                    'type' => 'tab',
                    'placement' => 'top',
                ),
                array('key' => 'field_footer_brand_name', 'label' => 'Brand Name', 'name' => 'footer_brand_name', 'type' => 'text', 'wrapper' => array('width' => '30')),
                array('key' => 'field_footer_copyright', 'label' => 'Copyright', 'name' => 'footer_copyright', 'type' => 'text', 'wrapper' => array('width' => '70')),
                array('key' => 'field_footer_brand_description', 'label' => 'Brand Description', 'name' => 'footer_brand_description', 'type' => 'textarea', 'rows' => 2),
                array(
                    'key' => 'field_footer_trust_badges',
                    'label' => 'Trust Badges',
                    'name' => 'footer_trust_badges',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Badge',
                    'sub_fields' => array(
                        array('key' => 'field_footer_badge_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'Shield', 'wrapper' => array('width' => '30')),
                        array('key' => 'field_footer_badge_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text', 'wrapper' => array('width' => '70')),
                    ),
                ),
                array(
                    'key' => 'field_footer_legal_links',
                    'label' => 'Legal Links',
                    'name' => 'footer_legal_links',
                    'type' => 'repeater',
                    'instructions' => 'Links like Privacy Policy, Terms of Service.',
                    'layout' => 'table',
                    'button_label' => 'Add Link',
                    'sub_fields' => array(
                        array('key' => 'field_footer_legal_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                        array('key' => 'field_footer_legal_href', 'label' => 'Href', 'name' => 'href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                    ),
                ),
                array(
                    'key' => 'field_footer_support_links',
                    'label' => 'Support Links',
                    'name' => 'footer_support_links',
                    'type' => 'repeater',
                    'instructions' => 'Links like Contact, Help Center.',
                    'layout' => 'table',
                    'button_label' => 'Add Link',
                    'sub_fields' => array(
                        array('key' => 'field_footer_support_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                        array('key' => 'field_footer_support_href', 'label' => 'Href', 'name' => 'href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                    ),
                ),
            ),
            'location' => $default_location,
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
        ),
        array(
            'key' => 'group_ccspro_global_pricing',
            'title' => 'Global Pricing Settings',
            'fields' => array(
                array('key' => 'field_pricing_title', 'label' => 'Section Title', 'name' => 'pricing_title', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_pricing_subtitle', 'label' => 'Section Subtitle', 'name' => 'pricing_subtitle', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array(
                    'key' => 'field_pricing_plans',
                    'label' => 'Credentialing Packs',
                    'name' => 'pricing_plans',
                    'type' => 'repeater',
                    'instructions' => 'Add pack-based pricing cards. Set billing type to one_time for packs and subscription for annual renewing plans.',
                    'layout' => 'block',
                    'min' => 1,
                    'max' => 4,
                    'button_label' => 'Add Plan',
                    'sub_fields' => array(
                        array('key' => 'field_plan_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text', 'wrapper' => array('width' => '30')),
                        array('key' => 'field_plan_price', 'label' => 'Price', 'name' => 'price', 'type' => 'number', 'wrapper' => array('width' => '20')),
                        array('key' => 'field_plan_badge', 'label' => 'Badge', 'name' => 'badge', 'type' => 'text', 'placeholder' => 'Most Popular', 'wrapper' => array('width' => '30')),
                        array('key' => 'field_plan_highlighted', 'label' => 'Highlighted', 'name' => 'highlighted', 'type' => 'true_false', 'ui' => 1, 'wrapper' => array('width' => '20')),
                        array('key' => 'field_plan_apps', 'label' => 'Applications Included', 'name' => 'applications_included', 'type' => 'number', 'wrapper' => array('width' => '25')),
                        array('key' => 'field_plan_validity', 'label' => 'Validity Period', 'name' => 'validity_period', 'type' => 'text', 'default_value' => '1 year', 'wrapper' => array('width' => '25')),
                        array(
                            'key' => 'field_plan_billing_type',
                            'label' => 'Billing Type',
                            'name' => 'billing_type',
                            'type' => 'select',
                            'choices' => array('one_time' => 'One-Time', 'subscription' => 'Subscription'),
                            'default_value' => 'one_time',
                            'wrapper' => array('width' => '25'),
                        ),
                        array(
                            'key' => 'field_plan_type',
                            'label' => 'Plan Type',
                            'name' => 'plan_type',
                            'type' => 'select',
                            'choices' => array('pack' => 'Pack', 'unlimited' => 'Unlimited'),
                            'default_value' => 'pack',
                            'wrapper' => array('width' => '25'),
                        ),
                        array(
                            'key' => 'field_plan_allow_additional_payers',
                            'label' => 'Allow Additional Payers',
                            'name' => 'allow_additional_payers',
                            'type' => 'true_false',
                            'ui' => 1,
                            'wrapper' => array('width' => '50'),
                        ),
                        array(
                            'key' => 'field_plan_additional_payer_price',
                            'label' => 'Additional Payer Price',
                            'name' => 'additional_payer_price',
                            'type' => 'number',
                            'wrapper' => array('width' => '50'),
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_plan_allow_additional_payers',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                        ),
                        array('key' => 'field_plan_grace_period_days', 'label' => 'Grace Period Days', 'name' => 'grace_period_days', 'type' => 'number', 'default_value' => 30, 'wrapper' => array('width' => '25')),
                        array('key' => 'field_plan_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2, 'wrapper' => array('width' => '75')),
                        array(
                            'key' => 'field_plan_features',
                            'label' => 'Features',
                            'name' => 'features',
                            'type' => 'repeater',
                            'layout' => 'table',
                            'button_label' => 'Add Feature',
                            'sub_fields' => array(
                                array('key' => 'field_plan_feature_text', 'label' => 'Feature', 'name' => 'feature_text', 'type' => 'text'),
                            ),
                        ),
                        array('key' => 'field_plan_cta', 'label' => 'CTA Button Text', 'name' => 'cta', 'type' => 'text', 'placeholder' => 'Get Started'),
                    ),
                ),
                array(
                    'key' => 'field_pricing_post_year_message',
                    'label' => '',
                    'type' => 'message',
                    'message' => '<strong>Post-Year Behavior Copy (shown only when one-time plans are present)</strong>',
                ),
                array('key' => 'field_post_year_title', 'label' => 'Post-Year Title', 'name' => 'post_year_title', 'type' => 'text', 'default_value' => 'What happens after 1 year?'),
                array(
                    'key' => 'field_post_year_items',
                    'label' => 'Post-Year Items',
                    'name' => 'post_year_items',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Item',
                    'sub_fields' => array(
                        array('key' => 'field_post_year_item_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text', 'wrapper' => array('width' => '80')),
                        array(
                            'key' => 'field_post_year_item_kind',
                            'label' => 'Kind',
                            'name' => 'kind',
                            'type' => 'select',
                            'choices' => array('positive' => 'Positive', 'negative' => 'Negative'),
                            'default_value' => 'positive',
                            'wrapper' => array('width' => '20'),
                        ),
                    ),
                ),
                array('key' => 'field_post_year_renewal_note', 'label' => 'Post-Year Renewal Note', 'name' => 'post_year_renewal_note', 'type' => 'text'),
                array(
                    'key' => 'field_pricing_footer_message',
                    'label' => '',
                    'type' => 'message',
                    'message' => '<strong>Pricing Footer Note</strong>',
                ),
                array('key' => 'field_pricing_footer_note', 'label' => 'Footer Note', 'name' => 'pricing_footer_note', 'type' => 'text', 'default_value' => 'Prices shown before sales tax.'),
            ),
            'location' => array(
                array(
                    array('param' => 'options_page', 'operator' => '==', 'value' => 'ccspro-pricing-settings'),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
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
    register_rest_route('ccspro/v1', '/pricing', array(
        'methods' => 'GET',
        'callback' => 'ccspro_rest_get_pricing',
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

function ccspro_rest_get_pricing($request) {
    if (!function_exists('get_field')) {
        return new WP_Error('acf_missing', 'ACF is required', array('status' => 500));
    }

    $pricing = ccspro_get_pricing_content('option', 0);
    return rest_ensure_response($pricing);
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

function ccspro_map_pricing_packs($pricing_plans_raw) {
    $pricing_packs = array();
    $has_highlight = false;

    foreach ((array) $pricing_plans_raw as $plan) {
        $feats = isset($plan['features']) && is_array($plan['features']) ? $plan['features'] : array();
        $feats = array_filter(array_map(function ($f) {
            return isset($f['feature_text']) ? $f['feature_text'] : '';
        }, $feats), function ($f) {
            return $f !== '';
        });

        $plan_type = isset($plan['plan_type']) ? $plan['plan_type'] : 'pack';
        $billing_type = isset($plan['billing_type']) && $plan['billing_type'] === 'subscription' ? 'subscription' : 'one_time';
        $allow_additional_payers = !empty($plan['allow_additional_payers']);
        $is_highlighted = !empty($plan['highlighted']) && !$has_highlight;
        if ($is_highlighted) {
            $has_highlight = true;
        }

        $applications_included = isset($plan['applications_included']) && $plan['applications_included'] !== ''
            ? (int) $plan['applications_included']
            : null;
        if ($plan_type === 'unlimited') {
            $applications_included = null;
        }

        $pricing_packs[] = array(
            'name' => isset($plan['name']) ? $plan['name'] : '',
            'price' => isset($plan['price']) && $plan['price'] !== '' ? (float) $plan['price'] : 0,
            'badge' => isset($plan['badge']) ? $plan['badge'] : null,
            'description' => isset($plan['description']) ? $plan['description'] : '',
            'applicationsIncluded' => $applications_included,
            'validityPeriod' => !empty($plan['validity_period']) ? $plan['validity_period'] : '1 year',
            'billingType' => $billing_type,
            'planType' => $plan_type === 'unlimited' ? 'unlimited' : 'pack',
            'allowAdditionalPayers' => $allow_additional_payers,
            'additionalPayerPrice' => $allow_additional_payers && isset($plan['additional_payer_price']) && $plan['additional_payer_price'] !== ''
                ? (float) $plan['additional_payer_price']
                : null,
            'features' => $feats,
            'cta' => isset($plan['cta']) ? $plan['cta'] : '',
            'highlighted' => $is_highlighted,
            'gracePeriodDays' => isset($plan['grace_period_days']) && $plan['grace_period_days'] !== '' ? (int) $plan['grace_period_days'] : 30,
        );
    }

    return array_slice($pricing_packs, 0, 4);
}

function ccspro_get_pricing_content($scope = 'option', $post_id = 0) {
    $target = $scope === 'option' ? 'option' : $post_id;

    $pricing_plans_raw = get_field('pricing_plans', $target) ?: array();
    $pricing_packs = ccspro_map_pricing_packs($pricing_plans_raw);
    $post_year_items_raw = get_field('post_year_items', $target) ?: array();
    $post_year_items = array_values(array_filter(array_map(function ($row) {
        if (!isset($row['text']) || $row['text'] === '') {
            return null;
        }
        return array(
            'text' => $row['text'],
            'kind' => isset($row['kind']) && $row['kind'] === 'negative' ? 'negative' : 'positive',
        );
    }, $post_year_items_raw)));

    if ($scope === 'option' && empty($pricing_packs) && $post_id) {
        return ccspro_get_pricing_content('post', $post_id);
    }

    return array(
        'sectionTitle' => get_field('pricing_title', $target) ?: 'Simple, transparent pricing',
        'sectionSubtitle' => get_field('pricing_subtitle', $target) ?: '',
        'packs' => $pricing_packs,
        'postYearBehavior' => array(
            'title' => get_field('post_year_title', $target) ?: 'What happens after 1 year?',
            'items' => $post_year_items,
            'renewalNote' => get_field('post_year_renewal_note', $target) ?: '',
        ),
        'footerNote' => get_field('pricing_footer_note', $target) ?: '',
    );
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
    $pricing_content = ccspro_get_pricing_content('option', $post_id);

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
