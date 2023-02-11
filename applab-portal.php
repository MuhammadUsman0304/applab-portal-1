<?php

/*
  Plugin Name: Applab portal 
  Plugin URI: https://github.com/MuhammadUsman0304/applab-portal-1
  Description: Post jobs on your WordPress site. User can apply and attach resume for the jobs, user can display jobs on other websites with the help of api
  Author: Muhammad Usman
  Version: 1.0.0
  Author URI: https://www.linkedin.com/in/muhammad-usman-b3439218b/
  Text Domain: applab-portal
  Domain Path: /languages
 */

define('APPLAB_CAREER_PLUGIN_URL', plugin_dir_url(__FILE__) . 'assets');
define('APPLAB_CAREER_PLUGIN_DIR', plugin_dir_path(__FILE__) . 'assets/');

require_once  plugin_dir_path(__FILE__) . 'applab-files.php';
$applab_uploads = wp_upload_dir();
$applab_plugin_name = "applab_career";


function applab_wp_enqueue_styles()
{
  // register style
  wp_register_style('applab_wp_register_bootstrap', APPLAB_CAREER_PLUGIN_URL . '/css/bootstrap.min.css');

  // enueing styles
  wp_enqueue_style('applab_wp_register_bootstrap');
}

add_action('wp_enqueue_scripts', 'applab_wp_enqueue_styles');

function applab_wp_enqueue_scripts()
{
  // register  bs/js 
  wp_register_script('applab_wp_register_script', APPLAB_CAREER_PLUGIN_URL . '/js/bootstrap.bundle.min.js', 'jquery', false, true);

  // enueque scipts
  wp_enqueue_script('applab_wp_register_script');
}
add_action('wp_enqueue_scripts', 'applab_wp_enqueue_scripts');

// bootstraping the plugin
function applab_plugin_activation()
{

  applab_create_jobs_tbl();
  applab_create_app_tbl();
  applab_job_listing_pg();
  applab_apply_job_pg();

  flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'applab_plugin_activation');


//add_action('init', 'applab_register_job_post_type');
//add_action('init', 'applab_job_categories', 0);
add_action('admin_menu', 'add_jobs_form_to_menu');
// job listing 
add_action('rest_api_init', 'applab_api_route_get');
function include_job_listing_template($template)
{
  if (is_page('job-listing')) {
    $new_template = plugin_dir_path(__FILE__) . 'templates/template-applab-joblisting.php';
    if ('' != $new_template) {
      return $new_template;
    }
  }
  return $template;
}
add_filter('template_include', 'include_job_listing_template', 99);

function applab_template_redirect()
{
  if (is_page('apply-job')) {
    include(plugin_dir_path(__FILE__) . 'templates/template-applab-apply-job.php');
    exit;
  }
}
add_action('template_redirect', 'applab_template_redirect');
register_deactivation_hook(__FILE__, function () {
  // Delete pages
  $page1_id = get_page_by_title('Job Listing');
  $page2_id = get_page_by_title('Apply Job');
  wp_delete_post($page1_id, true);
  wp_delete_post($page2_id, true);
  flush_rewrite_rules();
});
