<?php

/**
 * all functions files includs
 * @package applab-portal
 */

// Check if plugin is being deleted
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die();
}

global $wpdb;
$applab_table_1 = $wpdb->prefix . 'applab_jobs';
$applab_table_2 = $wpdb->prefix . 'applab_applications';
// Delete database tables
$wpdb->query("DROP TABLE IF EXISTS $applab_table_1");
$wpdb->query("DROP TABLE IF EXISTS $applab_table_2");

// Delete pages
$page1_id = get_page_by_title('Job Listing');
$page2_id = get_page_by_title('Apply Job');
wp_delete_post($page1_id, true);
wp_delete_post($page2_id, true);
