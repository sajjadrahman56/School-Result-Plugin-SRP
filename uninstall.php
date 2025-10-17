<?php
/**
 * Uninstall script for School Result Plugin
 * 
 * This file is executed when the plugin is uninstalled (deleted) from WordPress
 */

// Exit if uninstall not called from WordPress
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Include database class
require_once plugin_dir_path(__FILE__) . 'includes/class-srp-database.php';

// Drop all database tables
SRP_Database::drop_tables();

// Delete plugin options
delete_option('srp_version');
delete_option('srp_gpa_scale');
delete_option('srp_passing_marks');

// Delete uploaded student photos
$upload_dir = wp_upload_dir();
$srp_upload_dir = $upload_dir['basedir'] . '/srp-student-photos';

if (is_dir($srp_upload_dir)) {
    $files = glob($srp_upload_dir . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    rmdir($srp_upload_dir);
}
