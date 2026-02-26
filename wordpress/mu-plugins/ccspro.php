<?php
/**
 * Plugin Name: CCS Pro
 * Description: Headless CMS plugin for CCS Pro marketing site.
 * Version: 1.1.0
 * Author: CCS Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

$modules = array('cpt', 'admin', 'cors', 'acf', 'rest-api');
foreach ($modules as $module) {
    require_once __DIR__ . '/ccspro/' . $module . '.php';
}
