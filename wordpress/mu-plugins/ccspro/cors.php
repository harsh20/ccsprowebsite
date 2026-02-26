<?php

if (!defined('ABSPATH')) {
    exit;
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
