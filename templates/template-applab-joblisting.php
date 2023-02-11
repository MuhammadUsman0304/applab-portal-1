<?php

/**
 * Template Name: Jobs Listing
 * 
 * @package applab-portal
 */

get_header();


global $wpdb;
$table_name = $wpdb->prefix . 'applab_jobs';
$jobs = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

if ($jobs) {
    echo '
    <div class="container">
    <div class="class="row">
    <div class="class="col-md-8 mx-auto">
    <ul class="list-style-none">
    ';
    foreach ($jobs as $job) :

?>
        <li class="my-3">
            <div class="list-group">
                <a href="<?php echo site_url('/tempaltes/apply-job/?job_id=' . $job['job_id']) ?>" class="list-group-item list-group-item-action active" aria-current="true">
                    <div class="d-flex w-100 justify-content-between">
                        <h3 class="mb-1"><?php echo $job['job_title'] ?></h3>
                        <small>End Date: <?php echo $job['endDate'] ?></small>
                    </div>
                    <p class="mb-1"><?php echo $job['company_name'] ?></p>

                    <small><i class="dashicons dashicons-location"></i> <?php echo $job['country'] ?> |<i class="dashicons dashicons-clock"></i> <?php echo $job['job_type'] ?> |<i class="dashicons dashicons-category"></i> <?php echo $job['job_category'] ?> </small>
                </a>
            </div>
        </li>


<?php


    endforeach;
    echo '
    </div>
    </div>
    </div>
    </ul>
    ';
} else {
    echo 'No jobs found.';
}
?>






<?php get_footer() ?>