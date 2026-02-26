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

add_action('init', 'ccspro_register_menus');

function ccspro_register_menus() {
    register_nav_menus(array(
        'ccspro-primary-nav' => 'Primary Navigation',
        'ccspro-footer-col1' => 'Footer: Product Links',
        'ccspro-footer-col2' => 'Footer: Company Links',
        'ccspro-footer-col3' => 'Footer: Legal & Support',
    ));
}

add_filter('use_block_editor_for_post_type', 'ccspro_disable_block_editor_for_landing_page', 10, 2);
add_action('add_meta_boxes', 'ccspro_customize_landing_page_edit_screen', 100);
add_filter('enter_title_here', 'ccspro_landing_page_title_placeholder', 10, 2);
add_action('edit_form_top', 'ccspro_render_landing_page_edit_notice');
add_action('edit_form_after_title', 'ccspro_render_landing_page_slug_hint');

function ccspro_disable_block_editor_for_landing_page($use_block_editor, $post_type) {
    if ($post_type === 'landing_page') {
        return false;
    }
    return $use_block_editor;
}

function ccspro_customize_landing_page_edit_screen() {
    remove_meta_box('commentstatusdiv', 'landing_page', 'normal');
    remove_meta_box('commentsdiv', 'landing_page', 'normal');
    remove_meta_box('trackbacksdiv', 'landing_page', 'normal');
    remove_meta_box('postcustom', 'landing_page', 'normal');
    remove_meta_box('authordiv', 'landing_page', 'normal');
    remove_meta_box('revisionsdiv', 'landing_page', 'normal');
    remove_meta_box('pageparentdiv', 'landing_page', 'side');
    remove_meta_box('postimagediv', 'landing_page', 'side');
}

function ccspro_landing_page_title_placeholder($text, $post) {
    if ($post && isset($post->post_type) && $post->post_type === 'landing_page') {
        return 'Page Name (internal)';
    }
    return $text;
}

function ccspro_render_landing_page_edit_notice($post) {
    if (!$post || !isset($post->post_type) || $post->post_type !== 'landing_page') {
        return;
    }

    $slug = isset($post->post_name) ? $post->post_name : '';
    $title = isset($post->post_title) && $post->post_title !== '' ? $post->post_title : '(untitled)';
    echo '<div class="notice notice-info inline"><p><strong>Editing: ' . esc_html($title) . '</strong> — This page is live at ccsprocert.com/' . esc_html($slug) . '</p></div>';
}

function ccspro_render_landing_page_slug_hint($post) {
    if (!$post || !isset($post->post_type) || $post->post_type !== 'landing_page') {
        return;
    }

    echo '<p class="description" style="margin:8px 0 14px;">URL path: e.g. <code>groups</code> for <code>ccsprocert.com/groups</code></p>';
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
            'http://localhost:3000',
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
        $allowed = array('https://ccsprocert.com', 'https://www.ccsprocert.com', 'http://localhost:5173', 'http://localhost:3000', 'http://127.0.0.1:5173');
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
        'page_title' => 'CCS Pro Settings',
        'menu_title' => 'CCS Pro',
        'menu_slug' => 'ccspro-settings',
        'capability' => 'manage_options',
        'redirect' => true,
        'icon_url' => 'dashicons-shield-alt',
        'position' => 3,
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Header Settings',
        'menu_title' => 'Header',
        'menu_slug' => 'ccspro-header',
        'parent_slug' => 'ccspro-settings',
        'capability' => 'manage_options',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Footer Settings',
        'menu_title' => 'Footer',
        'menu_slug' => 'ccspro-footer',
        'parent_slug' => 'ccspro-settings',
        'capability' => 'manage_options',
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
                array(
                    'key' => 'field_provider_steps',
                    'label' => 'How It Works: Provider steps',
                    'name' => 'provider_steps',
                    'type' => 'repeater',
                    'layout' => 'block',
                    'button_label' => 'Add Provider Step',
                    'sub_fields' => array(
                        array('key' => 'field_provider_step_number', 'label' => 'Step #', 'name' => 'step_number', 'type' => 'text', 'placeholder' => '01', 'wrapper' => array('width' => '15')),
                        array('key' => 'field_provider_step_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'Upload', 'wrapper' => array('width' => '20')),
                        array('key' => 'field_provider_step_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text', 'wrapper' => array('width' => '65')),
                        array('key' => 'field_provider_step_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2),
                    ),
                ),
                array(
                    'key' => 'field_group_steps',
                    'label' => 'How It Works: Group & Facility steps',
                    'name' => 'group_steps',
                    'type' => 'repeater',
                    'layout' => 'block',
                    'button_label' => 'Add Group Step',
                    'sub_fields' => array(
                        array('key' => 'field_group_step_number', 'label' => 'Step #', 'name' => 'step_number', 'type' => 'text', 'placeholder' => '01', 'wrapper' => array('width' => '15')),
                        array('key' => 'field_group_step_icon', 'label' => 'Icon', 'name' => 'icon', 'type' => 'text', 'placeholder' => 'Users', 'wrapper' => array('width' => '20')),
                        array('key' => 'field_group_step_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text', 'wrapper' => array('width' => '65')),
                        array('key' => 'field_group_step_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2),
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
            'style' => 'seamless',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
        ),
        array(
            'key' => 'group_ccspro_pricing_v2',
            'title' => 'Pricing Section',
            'fields' => array(
                array('key' => 'field_pricing_section_title', 'label' => 'Section headline', 'name' => 'pricing_section_title', 'type' => 'text', 'default_value' => 'Simple pricing. No surprises.'),
                array('key' => 'field_pricing_section_subtitle', 'label' => 'Section subheadline', 'name' => 'pricing_section_subtitle', 'type' => 'text', 'default_value' => "Whether you're a solo provider or managing a 50-person group..."),
                array('key' => 'field_provider_badge', 'label' => 'Provider card: badge label', 'name' => 'provider_badge', 'type' => 'text', 'default_value' => 'For Individual Providers'),
                array('key' => 'field_provider_price', 'label' => 'Provider card: price', 'name' => 'provider_price', 'type' => 'text', 'default_value' => '$99/year', 'wrapper' => array('width' => '33')),
                array('key' => 'field_provider_price_sub', 'label' => 'Provider card: price subtext', 'name' => 'provider_price_sub', 'type' => 'text', 'default_value' => '+ $60 per packet generated', 'wrapper' => array('width' => '34')),
                array('key' => 'field_provider_highlighted', 'label' => 'Provider card: highlighted', 'name' => 'provider_highlighted', 'type' => 'true_false', 'ui' => 1, 'default_value' => 0, 'wrapper' => array('width' => '33')),
                array(
                    'key' => 'field_provider_bullets',
                    'label' => 'Provider card: feature bullets',
                    'name' => 'provider_bullets',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Bullet',
                    'sub_fields' => array(
                        array('key' => 'field_provider_bullet_text', 'label' => 'Bullet', 'name' => 'bullet_text', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_provider_cta_label', 'label' => 'Provider card: button label', 'name' => 'provider_cta_label', 'type' => 'text', 'default_value' => 'Get Started — $99/year', 'wrapper' => array('width' => '50')),
                array('key' => 'field_provider_cta_href', 'label' => 'Provider card: button URL', 'name' => 'provider_cta_href', 'type' => 'text', 'default_value' => '#', 'wrapper' => array('width' => '50')),
                array('key' => 'field_provider_fine_print', 'label' => 'Provider card: fine print below button', 'name' => 'provider_fine_print', 'type' => 'text', 'default_value' => 'No contracts. Cancel anytime.'),
                array('key' => 'field_provider_callout', 'label' => 'Provider card: callout line (below bullets)', 'name' => 'provider_callout', 'type' => 'text', 'default_value' => 'Most providers pay under $600 total in year one.'),
                array('key' => 'field_group_badge', 'label' => 'Group card: badge label', 'name' => 'group_badge', 'type' => 'text', 'default_value' => 'For Groups & Facilities'),
                array('key' => 'field_group_price', 'label' => 'Group card: price', 'name' => 'group_price', 'type' => 'text', 'default_value' => '$1,199/seat/year', 'wrapper' => array('width' => '33')),
                array('key' => 'field_group_price_sub', 'label' => 'Group card: price subtext', 'name' => 'group_price_sub', 'type' => 'text', 'default_value' => 'All payer packet workflows included', 'wrapper' => array('width' => '34')),
                array('key' => 'field_group_highlighted', 'label' => 'Group card: highlighted', 'name' => 'group_highlighted', 'type' => 'true_false', 'ui' => 1, 'default_value' => 1, 'wrapper' => array('width' => '33')),
                array(
                    'key' => 'field_group_bullets',
                    'label' => 'Group card: feature bullets',
                    'name' => 'group_bullets',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Bullet',
                    'sub_fields' => array(
                        array('key' => 'field_group_bullet_text', 'label' => 'Bullet', 'name' => 'bullet_text', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_group_cta_label', 'label' => 'Group card: button label', 'name' => 'group_cta_label', 'type' => 'text', 'default_value' => 'Talk to Us', 'wrapper' => array('width' => '50')),
                array('key' => 'field_group_cta_href', 'label' => 'Group card: button URL', 'name' => 'group_cta_href', 'type' => 'text', 'default_value' => '/contact', 'wrapper' => array('width' => '50')),
                array('key' => 'field_group_fine_print', 'label' => 'Group card: fine print below button', 'name' => 'group_fine_print', 'type' => 'text', 'default_value' => "Up to 50 seats. More than 50? Let's talk."),
                array(
                    'key' => 'field_group_notes',
                    'label' => 'Group card: notes (below bullets)',
                    'name' => 'group_notes',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Note',
                    'default_value' => array(
                        array('note_text' => 'One seat = one provider in your roster.'),
                        array('note_text' => 'All payer workflows included, no packet fees.'),
                        array('note_text' => "Need more than 50 seats? Let's talk."),
                    ),
                    'sub_fields' => array(
                        array('key' => 'field_group_note_text', 'label' => 'Note', 'name' => 'note_text', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_group_secondary_link_label', 'label' => 'Group card: secondary link label', 'name' => 'group_secondary_link_label', 'type' => 'text', 'default_value' => 'See full feature comparison →', 'wrapper' => array('width' => '50')),
                array('key' => 'field_group_secondary_link_href', 'label' => 'Group card: secondary link URL', 'name' => 'group_secondary_link_href', 'type' => 'text', 'default_value' => '/pricing', 'wrapper' => array('width' => '50')),
            ),
            'location' => $default_location,
            'menu_order' => 1,
            'position' => 'normal',
            'style' => 'seamless',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
        ),
        array(
            'key' => 'group_ccspro_ecosystem',
            'title' => 'Ecosystem Section',
            'fields' => array(
                array(
                    'key' => 'field_ecosystem_headline',
                    'label' => 'Ecosystem headline',
                    'name' => 'ecosystem_headline',
                    'type' => 'text',
                    'default_value' => 'One profile. Two sides of credentialing. Finally connected.',
                ),
                array(
                    'key' => 'field_ecosystem_subheadline',
                    'label' => 'Ecosystem subheadline',
                    'name' => 'ecosystem_subheadline',
                    'type' => 'text',
                    'default_value' => 'Providers build it once. Groups use it everywhere.',
                ),
                array(
                    'key' => 'field_ecosystem_pairs',
                    'label' => 'Provider → Group pairs',
                    'name' => 'ecosystem_pairs',
                    'type' => 'repeater',
                    'layout' => 'block',
                    'button_label' => 'Add Pair',
                    'sub_fields' => array(
                        array('key' => 'field_ecosystem_provider_action', 'label' => 'Provider side (left card)', 'name' => 'provider_action', 'type' => 'text', 'wrapper' => array('width' => '40')),
                        array('key' => 'field_ecosystem_connector', 'label' => 'Connector word (enables / means / so / and)', 'name' => 'connector', 'type' => 'text', 'wrapper' => array('width' => '20')),
                        array('key' => 'field_ecosystem_group_outcome', 'label' => 'Group side (right card)', 'name' => 'group_outcome', 'type' => 'text', 'wrapper' => array('width' => '40')),
                    ),
                ),
            ),
            'location' => $default_location,
            'menu_order' => 2,
            'position' => 'normal',
            'style' => 'seamless',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
        ),
        array(
            'key' => 'group_ccspro_header_global',
            'title' => 'Header Settings',
            'fields' => array(
                array(
                    'key' => 'field_site_logo',
                    'label' => 'Site logo',
                    'name' => 'site_logo',
                    'type' => 'image',
                    'instructions' => 'Recommended: SVG or PNG, transparent background',
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ),
                array(
                    'key' => 'field_site_logo_text',
                    'label' => 'Logo text fallback (shown if no logo uploaded)',
                    'name' => 'site_logo_text',
                    'type' => 'text',
                    'default_value' => 'CCS Pro',
                ),
                array(
                    'key' => 'field_header_cta_label',
                    'label' => 'Header CTA button label',
                    'name' => 'header_cta_label',
                    'type' => 'text',
                    'default_value' => 'Get Started',
                    'wrapper' => array('width' => '50'),
                ),
                array(
                    'key' => 'field_header_cta_href',
                    'label' => 'Header CTA button URL',
                    'name' => 'header_cta_href',
                    'type' => 'text',
                    'default_value' => '#',
                    'wrapper' => array('width' => '50'),
                ),
                array(
                    'key' => 'field_header_signin_label',
                    'label' => 'Sign in link label',
                    'name' => 'header_signin_label',
                    'type' => 'text',
                    'default_value' => 'Sign In',
                    'wrapper' => array('width' => '50'),
                ),
                array(
                    'key' => 'field_header_signin_href',
                    'label' => 'Sign in link URL',
                    'name' => 'header_signin_href',
                    'type' => 'text',
                    'default_value' => '#',
                    'wrapper' => array('width' => '50'),
                ),
            ),
            'location' => array(
                array(
                    array('param' => 'options_page', 'operator' => '==', 'value' => 'ccspro-header'),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
        ),
        array(
            'key' => 'group_ccspro_footer_global',
            'title' => 'Footer Settings',
            'fields' => array(
                array(
                    'key' => 'field_footer_brand_name_global',
                    'label' => 'Brand name',
                    'name' => 'footer_brand_name',
                    'type' => 'text',
                    'default_value' => 'CCS Pro',
                ),
                array(
                    'key' => 'field_footer_tagline_global',
                    'label' => 'Brand tagline (one line)',
                    'name' => 'footer_tagline',
                    'type' => 'text',
                    'default_value' => 'Credentialing packets. Done once. Ready always.',
                ),
                array(
                    'key' => 'field_footer_trust_badges_global',
                    'label' => 'Trust badges',
                    'name' => 'footer_trust_badges',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Badge',
                    'default_value' => array(
                        array('icon' => 'Shield', 'text' => 'HIPAA Compliant'),
                        array('icon' => 'FileCheck', 'text' => 'BAA Available'),
                        array('icon' => 'MapPin', 'text' => 'Texas-Based'),
                    ),
                    'sub_fields' => array(
                        array(
                            'key' => 'field_footer_trust_badge_icon_global',
                            'label' => 'Icon',
                            'name' => 'icon',
                            'type' => 'text',
                            'wrapper' => array('width' => '40'),
                        ),
                        array(
                            'key' => 'field_footer_trust_badge_text_global',
                            'label' => 'Text',
                            'name' => 'text',
                            'type' => 'text',
                            'wrapper' => array('width' => '60'),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_footer_copyright_global',
                    'label' => 'Copyright text',
                    'name' => 'footer_copyright',
                    'type' => 'text',
                    'default_value' => '© 2025 CCS Pro. All rights reserved.',
                ),
            ),
            'location' => array(
                array(
                    array('param' => 'options_page', 'operator' => '==', 'value' => 'ccspro-footer'),
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
