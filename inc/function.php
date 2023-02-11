<?php
require_once(str_replace('//', '/', dirname(__FILE__) . '/') . '../../../../wp-config.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
global $wpdb;
if (isset($_POST['submit_job'])) {
    // Get the form data
    $job_title = sanitize_text_field($_POST['applab_job_title']);
    $job_desc = sanitize_textarea_field($_POST['applab_job_desc']);
    $job_type = sanitize_textarea_field($_POST['applab_job_type']);
    $job_cate = sanitize_textarea_field($_POST['applab_job_category']);
    $comp_name = sanitize_textarea_field($_POST['applab_company_name']);
    $job_country = sanitize_textarea_field($_POST['applab_job_country']);
    $job_startDate = sanitize_textarea_field($_POST['applab_start_date']);
    $job_endDate = sanitize_textarea_field($_POST['applab_end_date']);
    $job_featured = $_POST['applab_is_feaured'] ? $_POST['applab_is_feaured'] : 0;
    $job_taken = $_POST['applab_is_taken'] ? $_POST['applab_is_taken'] : 0;

    if (!empty($_FILES['applab_company_logo']['tmp_name'])) {

        $file = $_FILES['applab_company_logo'];
        // Check if type
        $supported_types = array('image/jpeg', 'image/jpg', 'image/png');
        if (!in_array($file['type'], $supported_types)) {
            // The uploaded file is not a supported type
            echo 'Error: file type must be jpg|jpeg|png';
            return;
        }

        // proccess furthure if file type is correct
        $uploads = array('test_form' => false);

        $movefile = wp_handle_upload($file, $uploads);

        if ($movefile && !isset($movefile['error'])) {
            // Save the image data
            $url = $movefile['url'];
            $type = $movefile['type'];
            $file = $movefile['file'];
            $title = $file['title'];
            $content = $file['content'];

            $attachment = array(
                'post_mime_type' => $type,
                'post_title' => $title,
                'post_content' => $content,
                'post_status' => 'inherit'
            );

            $attachment_id = wp_insert_attachment($attachment, $file);
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $file);
            wp_update_attachment_metadata($attachment_id, $attachment_data);
        } else {
            // Error uploading the image
            echo "Error uploading image";
            echo $movefile['error'];
        }
    }

    // Table name
    $table_name = $wpdb->prefix . 'applab_jobs';

    // Insert the data into the table
    $save_post =  $wpdb->insert(
        $table_name,
        array(
            'job_title' => $job_title,
            'job_desc' => $job_desc,
            'job_type' => $job_type,
            'job_category' => $job_cate,
            'company_name' => $comp_name,
            'company_logo' => $url,
            'country' => $job_country,
            'startDate' => $job_startDate,
            'endDate' => $job_endDate,
            'is_featured' => $job_featured,
            'is_taken' => $job_taken,
        )
    );

    if ($save_post) {
        wp_redirect(admin_url('admin.php?page=jobs-form'));
    } else {
        echo "not submited";
    }
}
