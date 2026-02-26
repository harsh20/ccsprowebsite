<?php

if (!defined('ABSPATH')) {
    exit;
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
