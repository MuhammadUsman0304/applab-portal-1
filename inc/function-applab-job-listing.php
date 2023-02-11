<?php

/**
 * job listing form
 * @package applab-career
 */

// Add this code to your plugin file






// Create the custom form
function display_jobs_form()
{
    // enque styles
    wp_enqueue_style('applab_wp_register_style', APPLAB_CAREER_PLUGIN_URL . '/css/bootstrap.min.css');
    wp_enqueue_style('applab_wp_register_bootstrap', APPLAB_CAREER_PLUGIN_URL . '/css/style.css');
    // enueque scipts
    wp_enqueue_script('applab_wp_register_script', APPLAB_CAREER_PLUGIN_URL . '/js/bootstrap.bundle.min.js', 'jquery', false, true);
    wp_enqueue_script('applab_wp_tinymce', 'https://cdn.tiny.cloud/1/04zmoeb523lq2you3ov2crv0klmp3nyme12pamyr48d3bx5l/tinymce/6/tinymce.min.js', 'jquery');
    wp_enqueue_script('applab_wp_main_js', APPLAB_CAREER_PLUGIN_URL . '/js/main.js');


    add_action('wp_enqueue_scripts', 'applab_wp_register_style');
    add_action('wp_enqueue_scripts', 'applab_wp_register_bootstrap');
    add_action('wp_enqueue_scripts', 'applab_wp_register_script');
    add_action('wp_enqueue_scripts', 'applab_wp_tinymce');
    add_action('wp_enqueue_scripts', 'applab_wp_main_js');

    // Check if form was submitted

?>
    <div class="wrap applab-form-wrap">
        <form action="<?php echo esc_url(plugins_url('function.php', __FILE__)); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
            <div class="card applab-form-card">
                <div class="card-header bg-white">
                    <h5 class="card-title">Job Information</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="applab_job_title">Job Title </label>
                        <input type="text" name="applab_job_title" placeholder="Job title" class='form-control' id="applab_job_title">
                    </div>

                    <div class="form-group">
                        <label for="applab_job_desc">Job Descripation</label>
                        <textarea name="applab_job_desc" id="applab_job_type" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="applab_job_type">Job Type</label>
                        <select name="applab_job_type" id="applab_job_type" class="form-control">
                            <option value="full-time">Full Time</option>
                            <option value="part-time">Part Time</option>
                            <option value="remote">Remote</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="applab_job_category">Job Category</label>
                        <select name="applab_job_category" id="applab_job_category" class="form-control">
                            <option value="Programing">Programming</option>
                            <option value="grafic designing">Graphic Designing</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card applab-form-card">
                <div class="card-header bg-white">
                    <h5 class="card-title">Company Information</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="applab_company_name">Company Name </label>
                        <input type="text" name="applab_company_name" placeholder="Job title" class='form-control' id="applab_company_name">
                    </div>
                    <div class="form-group">
                        <label for="applab_company_logo">Company logo </label>
                        <input type="file" name="applab_company_logo" placeholder="Job title" class='form-control' id="applab_company_logo">
                    </div>
                </div>
            </div>

            <div class="card applab-form-card">
                <div class="card-header bg-white">
                    <h5 class="card-title">Other Information</h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="applab_job_country">Country</label>
                        <select name="applab_job_country" id="applab_job_country" class="form-control">
                            <option value="Pakistan">Pakistan</option>
                            <option value="India">India</option>
                            <option value="Qatar">Qatar</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="applab_start_date">Start Date</label>
                                <input type="date" class="form-control" name="applab_start_date" id="applab_start_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="applab_end_date">End Date</label>
                                <input type="date" class="form-control" name="applab_end_date" id="applab_end_date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <input type="checkbox" name="applab_is_feaured" id="applab_is_feaured" value="1"> Mark this as feaured?
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" name="applab_is_taken" id="applab_is_taken" value="1"> This position is already taken?
                        </div>
                    </div>

                </div>
            </div>

            <input type="submit" class="btn btn-info" name="submit_job" value="Submit Job">
        </form>

    </div>
<?php
}
add_action('admin_menu', 'add_jobs_form_to_menu');
// Add the form to the WordPress admin menu
function add_jobs_form_to_menu()
{
    add_menu_page(
        'Job Manager',
        'Job Manager',
        'manage_options',
        'add-new-job',
        'display_jobs_form',
        'dashicons-list-view',
        6
    );
    add_submenu_page(
        'add-new-job',
        'All Jobs',
        'All Jobs',
        'manage_options',
        'all-jobs',
        'display_job_listings'
    );

    add_submenu_page(
        'add-new-job',
        'Applications',
        'Applications',
        'manage_options',
        'applications',
        'all_application'
    );
}

function display_job_listings()
{
    global $wpdb;
    $applab_jobs_table = $wpdb->prefix . 'applab_jobs';
    $applab_app_table = $wpdb->prefix . 'applab_applications';
    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        $job_id = $_GET['job_id'];
        $wpdb->delete('myjobs', array('id' => $job_id));
    }

    $jobs = $wpdb->get_results("SELECT * FROM $applab_jobs_table");
?>
    <div class="wrap">
        <h1 class="wp-heading-inline">My Jobs</h1>
        <table class="wp-list-table widefat fixed striped posts">
            <thead>
                <tr>
                    <th scope="col" class="manage-column">Title</th>
                    <th scope="col" class="manage-column">Company Name</th>
                    <th scope="col" class="manage-column">Is Featured</th>
                    <th scope="col" class="manage-column">Job Type</th>
                    <th scope="col" class="manage-column">Category</th>
                    <th scope="col" class="manage-column">Expires</th>
                    <th scope="col" class="manage-column">Applications</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($jobs as $job) :
                    $app_count = $wpdb->get_var("SELECT COUNT(*) FROM $applab_app_table WHERE `app_job_id` = $job->job_id");

                ?>
                    <tr>
                        <td><?php echo $job->job_title; ?></td>
                        <td><?php echo $job->company_name; ?></td>
                        <td><?php echo $job->is_featured; ?></td>
                        <td><?php echo $job->job_type; ?></td>
                        <td><?php echo $job->job_category; ?></td>
                        <td><?php echo $job->endDate; ?></td>
                        <td><?php echo $app_count; ?></td>
                        <td>
                            <a href="?page=my-jobs&action=edit&job_id=<?php echo $job->job_id; ?>">Edit</a> |
                            <a href="?page=my-jobs&action=delete&job_id=<?php echo $job->job_id; ?>" onclick="return confirm('Are you sure you want to delete this job?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php
}

function all_application()
{
    global $wpdb;
    $applab_jobs_table = $wpdb->prefix . 'applab_jobs';
    $applab_app_table = $wpdb->prefix . 'applab_applications';
    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        $job_id = $_GET['job_id'];
        $wpdb->delete('myjobs', array('id' => $job_id));
    }

    $applications = $wpdb->get_results("SELECT * FROM $applab_app_table JOIN $applab_jobs_table ON $applab_jobs_table.job_id = $applab_app_table.app_job_id");
?>
    <div class="wrap">
        <h1 class="wp-heading-inline">My Jobs</h1>
        <table class="wp-list-table widefat fixed striped posts">
            <thead>
                <tr>
                    <th scope="col" class="manage-column">Applicant Name</th>
                    <th scope="col" class="manage-column">Applicant Email</th>
                    <th scope="col" class="manage-column">Job</th>
                    <th scope="col" class="manage-column">Resume</th>
                    <th scope="col" class="manage-column">Message</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($applications as $application) :

                ?>
                    <tr>
                        <td><?php echo $application->app_name; ?></td>
                        <td><?php echo $application->app_email; ?></td>
                        <td><?php echo $application->job_title; ?></td>
                        <td><a download href="<?php echo $application->app_cv ?>">Download</a></td>
                        <td><?php echo $application->app_msg; ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

<?php
}
