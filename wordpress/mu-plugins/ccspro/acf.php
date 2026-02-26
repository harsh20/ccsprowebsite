<?php

if (!defined('ABSPATH')) {
    exit;
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

    acf_add_options_sub_page(array(
        'page_title' => 'Pricing Page',
        'menu_title' => 'Pricing Page',
        'menu_slug' => 'ccspro-pricing-page',
        'parent_slug' => 'ccspro-settings',
        'capability' => 'manage_options',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'About Page',
        'menu_title' => 'About Page',
        'menu_slug' => 'ccspro-about-page',
        'parent_slug' => 'ccspro-settings',
        'capability' => 'manage_options',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Contact Page',
        'menu_title' => 'Contact Page',
        'menu_slug' => 'ccspro-contact-page',
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
                array('key' => 'field_hero_headline', 'label' => 'Headline', 'name' => 'hero_headline', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_hero_headline_highlight', 'label' => 'Highlight Word', 'name' => 'hero_headline_highlight', 'type' => 'text', 'instructions' => 'Word to highlight in the headline', 'wrapper' => array('width' => '30')),
                array('key' => 'field_hero_headline_suffix', 'label' => 'Headline Suffix', 'name' => 'hero_headline_suffix', 'type' => 'text', 'instructions' => 'Text appended after the highlight word (e.g. "Ready Always.")', 'default_value' => 'Ready Always.', 'wrapper' => array('width' => '20')),
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
                array(
                    'key' => 'field_story_legacy_notice',
                    'label' => '',
                    'type' => 'message',
                    'message' => '<div style="background:#fef3c7;border:1px solid #f59e0b;padding:10px 14px;border-radius:6px;margin-bottom:12px;"><strong>Legacy sections — not used on the current homepage</strong>. "Verification / Logo Strip" and "Founder Spotlight" are retained for backward compatibility with <code>/:slug</code> routes. "Problem / Outcome" remains active.</div>',
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
                // TAB: Ecosystem
                // =====================================================================
                array(
                    'key' => 'field_tab_ecosystem',
                    'label' => 'Ecosystem',
                    'type' => 'tab',
                    'placement' => 'top',
                ),
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
                array('key' => 'field_pricing_section_title', 'label' => 'Section headline', 'name' => 'pricing_section_title', 'type' => 'text', 'default_value' => 'Simple pricing. No surprises.'),
                array('key' => 'field_pricing_section_subtitle', 'label' => 'Section subheadline', 'name' => 'pricing_section_subtitle', 'type' => 'text', 'default_value' => "Whether you're a solo provider or managing a 50-person group..."),
                array(
                    'key' => 'field_tab_pricing_provider',
                    'label' => 'Provider Card',
                    'type' => 'tab',
                    'placement' => 'left',
                ),
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
                array(
                    'key' => 'field_tab_pricing_group',
                    'label' => 'Group Card',
                    'type' => 'tab',
                    'placement' => 'left',
                ),
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
        // =====================================================================
        // PRICING PAGE
        // =====================================================================
        array(
            'key' => 'group_ccspro_pricing_page',
            'title' => 'Pricing Page Content',
            'fields' => array(
                array('key' => 'field_pricing_tab_hero', 'label' => 'Hero', 'type' => 'tab', 'placement' => 'top'),
                array('key' => 'field_pricing_hero_headline', 'label' => 'Headline', 'name' => 'pricing_hero_headline', 'type' => 'text'),
                array('key' => 'field_pricing_hero_subheadline', 'label' => 'Subheadline', 'name' => 'pricing_hero_subheadline', 'type' => 'text'),

                array('key' => 'field_pricing_tab_provider', 'label' => 'Provider Card', 'type' => 'tab', 'placement' => 'top'),
                array('key' => 'field_pricing_provider_badge', 'label' => 'Badge', 'name' => 'pricing_provider_badge', 'type' => 'text', 'wrapper' => array('width' => '33')),
                array('key' => 'field_pricing_provider_price', 'label' => 'Price', 'name' => 'pricing_provider_price', 'type' => 'text', 'wrapper' => array('width' => '33')),
                array('key' => 'field_pricing_provider_price_sub', 'label' => 'Price Subtext', 'name' => 'pricing_provider_price_sub', 'type' => 'text', 'wrapper' => array('width' => '34')),
                array('key' => 'field_pricing_provider_highlighted', 'label' => 'Highlighted', 'name' => 'pricing_provider_highlighted', 'type' => 'true_false', 'ui' => 1),
                array(
                    'key' => 'field_pricing_provider_bullets',
                    'label' => 'Bullets',
                    'name' => 'pricing_provider_bullets',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Bullet',
                    'sub_fields' => array(
                        array('key' => 'field_pricing_provider_bullet_text', 'label' => 'Text', 'name' => 'bullet_text', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_pricing_provider_cta_label', 'label' => 'CTA Label', 'name' => 'pricing_provider_cta_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_pricing_provider_cta_href', 'label' => 'CTA Href', 'name' => 'pricing_provider_cta_href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_pricing_provider_fine_print', 'label' => 'Fine Print', 'name' => 'pricing_provider_fine_print', 'type' => 'text'),
                array(
                    'key' => 'field_pricing_provider_extras',
                    'label' => 'Extras',
                    'name' => 'pricing_provider_extras',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Extra',
                    'sub_fields' => array(
                        array('key' => 'field_pricing_provider_extra_text', 'label' => 'Text', 'name' => 'extra_text', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_pricing_provider_secondary_link_label', 'label' => 'Secondary Link Label', 'name' => 'pricing_provider_secondary_link_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_pricing_provider_secondary_link_href', 'label' => 'Secondary Link Href', 'name' => 'pricing_provider_secondary_link_href', 'type' => 'text', 'wrapper' => array('width' => '50')),

                array('key' => 'field_pricing_tab_group', 'label' => 'Group Card', 'type' => 'tab', 'placement' => 'top'),
                array('key' => 'field_pricing_group_badge', 'label' => 'Badge', 'name' => 'pricing_group_badge', 'type' => 'text', 'wrapper' => array('width' => '33')),
                array('key' => 'field_pricing_group_price', 'label' => 'Price', 'name' => 'pricing_group_price', 'type' => 'text', 'wrapper' => array('width' => '33')),
                array('key' => 'field_pricing_group_price_sub', 'label' => 'Price Subtext', 'name' => 'pricing_group_price_sub', 'type' => 'text', 'wrapper' => array('width' => '34')),
                array('key' => 'field_pricing_group_highlighted', 'label' => 'Highlighted', 'name' => 'pricing_group_highlighted', 'type' => 'true_false', 'ui' => 1),
                array(
                    'key' => 'field_pricing_group_bullets',
                    'label' => 'Bullets',
                    'name' => 'pricing_group_bullets',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Bullet',
                    'sub_fields' => array(
                        array('key' => 'field_pricing_group_bullet_text', 'label' => 'Text', 'name' => 'bullet_text', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_pricing_group_cta_label', 'label' => 'CTA Label', 'name' => 'pricing_group_cta_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_pricing_group_cta_href', 'label' => 'CTA Href', 'name' => 'pricing_group_cta_href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_pricing_group_fine_print', 'label' => 'Fine Print', 'name' => 'pricing_group_fine_print', 'type' => 'text'),
                array(
                    'key' => 'field_pricing_group_extras',
                    'label' => 'Extras',
                    'name' => 'pricing_group_extras',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Extra',
                    'sub_fields' => array(
                        array('key' => 'field_pricing_group_extra_text', 'label' => 'Text', 'name' => 'extra_text', 'type' => 'text'),
                    ),
                ),
                array('key' => 'field_pricing_group_secondary_link_label', 'label' => 'Secondary Link Label', 'name' => 'pricing_group_secondary_link_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_pricing_group_secondary_link_href', 'label' => 'Secondary Link Href', 'name' => 'pricing_group_secondary_link_href', 'type' => 'text', 'wrapper' => array('width' => '50')),

                array('key' => 'field_pricing_tab_features', 'label' => 'Feature Comparison', 'type' => 'tab', 'placement' => 'top'),
                array(
                    'key' => 'field_pricing_feature_table',
                    'label' => 'Feature Table',
                    'name' => 'pricing_feature_table',
                    'type' => 'repeater',
                    'layout' => 'block',
                    'button_label' => 'Add Category',
                    'sub_fields' => array(
                        array('key' => 'field_pricing_ft_category', 'label' => 'Category', 'name' => 'category', 'type' => 'text'),
                        array(
                            'key' => 'field_pricing_ft_rows',
                            'label' => 'Rows',
                            'name' => 'rows',
                            'type' => 'repeater',
                            'layout' => 'table',
                            'button_label' => 'Add Row',
                            'sub_fields' => array(
                                array('key' => 'field_pricing_ft_row_feature', 'label' => 'Feature', 'name' => 'feature', 'type' => 'text'),
                                array('key' => 'field_pricing_ft_row_provider', 'label' => 'Provider', 'name' => 'provider', 'type' => 'true_false', 'ui' => 1),
                                array('key' => 'field_pricing_ft_row_group', 'label' => 'Group', 'name' => 'group', 'type' => 'true_false', 'ui' => 1),
                            ),
                        ),
                    ),
                ),

                array('key' => 'field_pricing_tab_faq', 'label' => 'FAQ', 'type' => 'tab', 'placement' => 'top'),
                array('key' => 'field_pricing_faq_title', 'label' => 'Section Title', 'name' => 'pricing_faq_title', 'type' => 'text'),
                array('key' => 'field_pricing_faq_subtitle', 'label' => 'Section Subtitle', 'name' => 'pricing_faq_subtitle', 'type' => 'text'),
                array(
                    'key' => 'field_pricing_faq_items',
                    'label' => 'FAQ Items',
                    'name' => 'pricing_faq_items',
                    'type' => 'repeater',
                    'layout' => 'block',
                    'button_label' => 'Add FAQ',
                    'sub_fields' => array(
                        array('key' => 'field_pricing_faq_question', 'label' => 'Question', 'name' => 'question', 'type' => 'text'),
                        array('key' => 'field_pricing_faq_answer', 'label' => 'Answer', 'name' => 'answer', 'type' => 'wysiwyg', 'tabs' => 'all', 'toolbar' => 'basic', 'media_upload' => 0),
                    ),
                ),

                array('key' => 'field_pricing_tab_final_cta', 'label' => 'Final CTA', 'type' => 'tab', 'placement' => 'top'),
                array('key' => 'field_pricing_final_cta_headline', 'label' => 'Headline', 'name' => 'pricing_final_cta_headline', 'type' => 'text'),
                array('key' => 'field_pricing_final_cta_provider_label', 'label' => 'Provider CTA Label', 'name' => 'pricing_final_cta_provider_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_pricing_final_cta_provider_href', 'label' => 'Provider CTA Href', 'name' => 'pricing_final_cta_provider_href', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_pricing_final_cta_group_label', 'label' => 'Group CTA Label', 'name' => 'pricing_final_cta_group_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_pricing_final_cta_group_href', 'label' => 'Group CTA Href', 'name' => 'pricing_final_cta_group_href', 'type' => 'text', 'wrapper' => array('width' => '50')),
            ),
            'location' => array(
                array(
                    array('param' => 'options_page', 'operator' => '==', 'value' => 'ccspro-pricing-page'),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
        ),
        // =====================================================================
        // ABOUT PAGE
        // =====================================================================
        array(
            'key' => 'group_ccspro_about_page',
            'title' => 'About Page Content',
            'fields' => array(
                array('key' => 'field_about_tab_hero', 'label' => 'Hero', 'type' => 'tab', 'placement' => 'top'),
                array('key' => 'field_about_hero_headline', 'label' => 'Headline', 'name' => 'about_hero_headline', 'type' => 'text'),
                array('key' => 'field_about_hero_subheadline', 'label' => 'Subheadline', 'name' => 'about_hero_subheadline', 'type' => 'text'),

                array('key' => 'field_about_tab_mission', 'label' => 'Mission', 'type' => 'tab', 'placement' => 'top'),
                array('key' => 'field_about_mission', 'label' => 'Mission Statement', 'name' => 'about_mission', 'type' => 'textarea', 'rows' => 4),

                array('key' => 'field_about_tab_why_texas', 'label' => 'Why Texas', 'type' => 'tab', 'placement' => 'top'),
                array('key' => 'field_about_why_texas_paragraph', 'label' => 'Paragraph', 'name' => 'about_why_texas_paragraph', 'type' => 'textarea', 'rows' => 4),
                array(
                    'key' => 'field_about_why_texas_stats',
                    'label' => 'Stats',
                    'name' => 'about_why_texas_stats',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Stat',
                    'sub_fields' => array(
                        array('key' => 'field_about_stat_value', 'label' => 'Value', 'name' => 'value', 'type' => 'text', 'wrapper' => array('width' => '40')),
                        array('key' => 'field_about_stat_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text', 'wrapper' => array('width' => '60')),
                    ),
                ),

                array('key' => 'field_about_tab_differentiators', 'label' => 'Differentiators', 'type' => 'tab', 'placement' => 'top'),
                array(
                    'key' => 'field_about_differentiators',
                    'label' => 'Differentiators',
                    'name' => 'about_differentiators',
                    'type' => 'repeater',
                    'layout' => 'block',
                    'button_label' => 'Add Differentiator',
                    'sub_fields' => array(
                        array('key' => 'field_about_diff_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'),
                        array('key' => 'field_about_diff_description', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 3),
                    ),
                ),

                array('key' => 'field_about_tab_cta', 'label' => 'CTA', 'type' => 'tab', 'placement' => 'top'),
                array('key' => 'field_about_cta_text', 'label' => 'CTA Text', 'name' => 'about_cta_text', 'type' => 'text'),
                array('key' => 'field_about_cta_link_label', 'label' => 'Link Label', 'name' => 'about_cta_link_label', 'type' => 'text', 'wrapper' => array('width' => '50')),
                array('key' => 'field_about_cta_link_href', 'label' => 'Link Href', 'name' => 'about_cta_link_href', 'type' => 'text', 'wrapper' => array('width' => '50')),
            ),
            'location' => array(
                array(
                    array('param' => 'options_page', 'operator' => '==', 'value' => 'ccspro-about-page'),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
        ),
        // =====================================================================
        // CONTACT PAGE
        // =====================================================================
        array(
            'key' => 'group_ccspro_contact_page',
            'title' => 'Contact Page Content',
            'fields' => array(
                array('key' => 'field_contact_tab_hero', 'label' => 'Hero', 'type' => 'tab', 'placement' => 'top'),
                array('key' => 'field_contact_hero_headline', 'label' => 'Headline', 'name' => 'contact_hero_headline', 'type' => 'text'),
                array('key' => 'field_contact_hero_subheadline', 'label' => 'Subheadline', 'name' => 'contact_hero_subheadline', 'type' => 'text'),

                array('key' => 'field_contact_tab_form', 'label' => 'Form Settings', 'type' => 'tab', 'placement' => 'top'),
                array(
                    'key' => 'field_contact_role_options',
                    'label' => 'Role Options',
                    'name' => 'contact_role_options',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Role',
                    'sub_fields' => array(
                        array('key' => 'field_contact_role_option_text', 'label' => 'Option Text', 'name' => 'option_text', 'type' => 'text'),
                    ),
                ),

                array('key' => 'field_contact_tab_info', 'label' => 'Contact Info', 'type' => 'tab', 'placement' => 'top'),
                array('key' => 'field_contact_email', 'label' => 'Email', 'name' => 'contact_email', 'type' => 'email'),
                array('key' => 'field_contact_response_time', 'label' => 'Response Time', 'name' => 'contact_response_time', 'type' => 'text'),
                array('key' => 'field_contact_business_hours', 'label' => 'Business Hours', 'name' => 'contact_business_hours', 'type' => 'text'),

                array('key' => 'field_contact_tab_group_callout', 'label' => 'Group Callout', 'type' => 'tab', 'placement' => 'top'),
                array('key' => 'field_contact_group_callout_headline', 'label' => 'Headline', 'name' => 'contact_group_callout_headline', 'type' => 'text'),
                array('key' => 'field_contact_group_callout_body', 'label' => 'Body', 'name' => 'contact_group_callout_body', 'type' => 'textarea', 'rows' => 4),
            ),
            'location' => array(
                array(
                    array('param' => 'options_page', 'operator' => '==', 'value' => 'ccspro-contact-page'),
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

