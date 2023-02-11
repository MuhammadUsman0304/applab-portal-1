<?php

/**
 * crating api routes
 * @package applab-career
 */


// get data from db
//http://localhost/applab/wp-json/applab-jobs/myjobs => get jobs
//http://localhost/applab/wp-json/applab-jobs/apply => apply jobs



function applab_api_route_get()
{
    register_rest_route('applab-jobs/', '/myjobs', array(
        'methods' => 'GET',
        'callback' => 'applab_get_callback',
        'args' => array(
            'featured' => array(
                'validate_callback' => function ($param, $request, $key) {
                    return in_array($param, array('is_featured'));
                },
                'description' => __('Sort jobs by expiry date or featured status'),
                'default' => 'is_featured'
            )
        ),
    ));

    // registering apply api route
    register_rest_route('applab-jobs/', '/apply', array(
        'methods' => 'POST',
        'callback' => 'applab_post_callback',
        'args' => array(
            'app_name' => array(
                'validate_callback' => function ($param, $request, $key) {
                    return !empty($param);
                },
                'required' => true
            ),
            'app_job_id' => array(
                'validate_callback' => function ($param, $request, $key) {
                    return !empty($param);
                },
                'required' => true
            ),
            'app_email' => array(
                'validate_callback' => function ($param, $request, $key) {
                    return !empty($param);
                },
                'required' => true
            ),
            'app_msg' => array(
                'validate_callback' => function ($param, $request, $key) {
                    return !empty($param);
                },
                'required' => true
            ),
            'app_cv' => array(
                'validate_callback' => function ($param, $request, $key) {
                    return !empty($param);
                },
                'required' => true
            )
        )
    ));
}


// getting jobs data through api
function applab_get_callback($request)
{
    global $wpdb;
    $applab_jobs_table = $wpdb->prefix . 'applab_jobs';

    // param to filter the jobs

    $featured = $request->get_param('is_featured');
    $today = date("Y-m-d");

    //query generator
    $applab_qry = "SELECT * FROM $applab_jobs_table WHERE endDate >= $today ";


    if ($featured == 1) {
        $applab_qry .= " AND is_featured = 1";
    }

    $applab_get_jobs = $wpdb->get_results($applab_qry);

    $applab_get_response = array();
    foreach ($applab_get_jobs as $job) {
        $applab_get_response[] = array(
            'job_id' => $job->job_id,
            'job_title' => $job->job_title,
            'job_desc' => $job->job_desc,
            'job_type' => $job->job_type,
            'job_category' => $job->job_category,
            'company_name' => $job->company_name,
            'company_logo' => $job->company_logo,
            'country' => $job->country,
            'startDate' => $job->startDate,
            'endDate' => $job->endDate,
            'is_featured' => $job->is_featured,

        );
    }
    // Return the response data as a JSON object
    return rest_ensure_response(array("data" => $applab_get_response));
}



// applying to jobs through api

function applab_post_callback($request)
{
    global $wpdb;
    $params = $request->get_params();
    $applab_app_table = $wpdb->prefix . 'applab_applications';
    $name = sanitize_text_field($params['app_name']);
    $job_id = intval($params['app_job_id']);
    $email = sanitize_text_field($params['app_email']);
    $message = sanitize_text_field($params['app_msg']);
    $resume = sanitize_text_field($params['app_cv']);
    if (empty($name) || empty($job_id) || empty($email) || empty($message) || empty($resume)) {
        return new WP_Error('field_error', 'All fields are required', array('status' => 400));
    } else {
        $apply_data =  $wpdb->insert("$applab_app_table", array(
            'app_name' => $name,
            'app_job_id' => $job_id,
            'app_email' => $email,
            'app_msg' => $message,
            'app_cv' => $resume
        ));
        // return $apply_data;
        return array('success' => true);
    }
}
