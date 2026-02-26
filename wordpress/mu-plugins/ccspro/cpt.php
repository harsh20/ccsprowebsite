<?php

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

add_action('init', 'ccspro_register_contact_submission_cpt');

function ccspro_register_contact_submission_cpt() {
    register_post_type('contact_submission', array(
        'labels' => array(
            'name'               => 'Contact Submissions',
            'singular_name'      => 'Contact Submission',
            'edit_item'          => 'View Submission',
            'view_item'          => 'View Submission',
            'search_items'       => 'Search Submissions',
            'not_found'          => 'No submissions found',
            'not_found_in_trash' => 'No submissions found in Trash',
        ),
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'show_in_rest'  => false,
        'supports'      => array('title'),
        'menu_icon'     => 'dashicons-email-alt',
        'capabilities'  => array(
            'create_posts' => 'do_not_allow',
        ),
        'map_meta_cap'  => true,
    ));
}

// ---------------------------------------------------------------------------
// contact_submission admin table columns
// ---------------------------------------------------------------------------

add_filter('manage_contact_submission_posts_columns', 'ccspro_contact_submission_columns');
add_action('manage_contact_submission_posts_custom_column', 'ccspro_contact_submission_column_content', 10, 2);
add_filter('manage_edit-contact_submission_sortable_columns', 'ccspro_contact_submission_sortable_columns');
add_action('admin_head', 'ccspro_contact_submission_admin_styles');
add_action('the_post', 'ccspro_contact_submission_mark_read');
add_filter('bulk_actions-edit-contact_submission', 'ccspro_contact_submission_remove_bulk_actions');
add_filter('post_row_actions', 'ccspro_contact_submission_row_actions', 10, 2);

function ccspro_contact_submission_columns($columns) {
    return array(
        'cb'        => $columns['cb'],
        'status'    => '',
        'title'     => 'Name',
        'email'     => 'Email',
        'role'      => 'Role',
        'message'   => 'Message',
        'date'      => 'Submitted',
    );
}

function ccspro_contact_submission_column_content($column, $post_id) {
    switch ($column) {
        case 'status':
            $read = get_post_meta($post_id, '_ccspro_read', true);
            if (!$read) {
                echo '<span title="Unread" style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#2271b1;margin-top:2px;"></span>';
            }
            break;
        case 'email':
            $email = get_post_meta($post_id, '_ccspro_email', true);
            echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
            break;
        case 'role':
            echo esc_html(get_post_meta($post_id, '_ccspro_role', true));
            break;
        case 'message':
            $msg = get_post_meta($post_id, '_ccspro_message', true);
            echo '<span title="' . esc_attr($msg) . '">' . esc_html(wp_trim_words($msg, 12, '…')) . '</span>';
            break;
    }
}

function ccspro_contact_submission_sortable_columns($columns) {
    $columns['role'] = 'role';
    return $columns;
}

function ccspro_contact_submission_admin_styles() {
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'contact_submission') {
        return;
    }
    echo '<style>
        .column-status { width: 20px; }
        .column-email  { width: 20%; }
        .column-role   { width: 12%; }
        .column-message{ width: 30%; }
        .column-date   { width: 12%; }
    </style>';
}

function ccspro_contact_submission_mark_read($post) {
    if (!is_admin() || !$post || $post->post_type !== 'contact_submission') {
        return;
    }
    if (!get_post_meta($post->ID, '_ccspro_read', true)) {
        update_post_meta($post->ID, '_ccspro_read', '1');
    }
}

function ccspro_contact_submission_remove_bulk_actions($actions) {
    unset($actions['edit']);
    return $actions;
}

function ccspro_contact_submission_row_actions($actions, $post) {
    if ($post->post_type === 'contact_submission') {
        unset($actions['inline hide-if-no-js']);
        unset($actions['edit']);
    }
    return $actions;
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
