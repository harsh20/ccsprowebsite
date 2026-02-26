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
