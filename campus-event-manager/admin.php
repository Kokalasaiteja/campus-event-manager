<?php

if (!defined('ABSPATH')) {
    exit;
}

function cem_add_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=event', // Parent slug for Events CPT
        __('Manage Events', 'campus-event-manager'), // Page title
        __('Manage Events', 'campus-event-manager'), // Menu title
        'manage_options', // Capability
        'campus-event-manager', // Menu slug
        'cem_admin_page' // Callback function
    );
}
add_action('admin_menu', 'cem_add_admin_menu');

function cem_admin_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // Handle form submission
    if (isset($_POST['submit_event'])) {
        cem_handle_event_submission();
    }

    // Display the admin page
    ?>
    <div class="wrap">
        <h1><?php _e('Manage Campus Events', 'campus-event-manager'); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('cem_event_nonce', 'cem_event_nonce_field'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="event_name"><?php _e('Event Name', 'campus-event-manager'); ?></label></th>
                    <td><input type="text" name="event_name" id="event_name" class="regular-text" required /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="event_date"><?php _e('Event Date', 'campus-event-manager'); ?></label></th>
                    <td><input type="date" name="event_date" id="event_date" required /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="event_time"><?php _e('Event Time', 'campus-event-manager'); ?></label></th>
                    <td><input type="time" name="event_time" id="event_time" required /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="event_location"><?php _e('Event Location', 'campus-event-manager'); ?></label></th>
                    <td><input type="text" name="event_location" id="event_location" class="regular-text" required /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="event_description"><?php _e('Event Description', 'campus-event-manager'); ?></label></th>
                    <td><textarea name="event_description" id="event_description" rows="5" cols="50" required></textarea></td>
                </tr>
            </table>
            <?php submit_button(__('Add Event', 'campus-event-manager'), 'primary', 'submit_event'); ?>
        </form>
    </div>
    <?php
}

function cem_handle_event_submission() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['cem_event_nonce_field'], 'cem_event_nonce')) {
        wp_die(__('Security check failed.', 'campus-event-manager'));
    }

    // Sanitize and validate input
    $event_name = sanitize_text_field($_POST['event_name']);
    $event_date = sanitize_text_field($_POST['event_date']);
    $event_time = sanitize_text_field($_POST['event_time']);
    $event_location = sanitize_text_field($_POST['event_location']);
    $event_description = sanitize_textarea_field($_POST['event_description']);

    // Create new event post
    $event_id = wp_insert_post(array(
        'post_title'   => $event_name,
        'post_content' => $event_description,
        'post_type'    => 'event',
        'post_status'  => 'publish',
    ));

    if ($event_id) {
        // Save custom meta fields
        update_post_meta($event_id, '_event_date', $event_date);
        update_post_meta($event_id, '_event_time', $event_time);
        update_post_meta($event_id, '_event_location', $event_location);

        // Success message
        add_action('admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Event added successfully!', 'campus-event-manager') . '</p></div>';
        });
    } else {
        // Error message
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error is-dismissible"><p>' . __('Failed to add event.', 'campus-event-manager') . '</p></div>';
        });
    }
}
?>
