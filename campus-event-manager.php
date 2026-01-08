<?php

if (!defined('ABSPATH')) {
    exit;
}

define('CEM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CEM_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once CEM_PLUGIN_DIR . 'admin.php';
require_once CEM_PLUGIN_DIR . 'shortcode.php';

function cem_register_event_post_type() {
    $labels = array(
        'name'               => _x('Events', 'post type general name', 'campus-event-manager'),
        'singular_name'      => _x('Event', 'post type singular name', 'campus-event-manager'),
        'menu_name'          => _x('Events', 'admin menu', 'campus-event-manager'),
        'name_admin_bar'     => _x('Event', 'add new on admin bar', 'campus-event-manager'),
        'add_new'            => _x('Add New', 'event', 'campus-event-manager'),
        'add_new_item'       => __('Add New Event', 'campus-event-manager'),
        'new_item'           => __('New Event', 'campus-event-manager'),
        'edit_item'          => __('Edit Event', 'campus-event-manager'),
        'view_item'          => __('View Event', 'campus-event-manager'),
        'all_items'          => __('All Events', 'campus-event-manager'),
        'search_items'       => __('Search Events', 'campus-event-manager'),
        'parent_item_colon'  => __('Parent Events:', 'campus-event-manager'),
        'not_found'          => __('No events found.', 'campus-event-manager'),
        'not_found_in_trash' => __('No events found in Trash.', 'campus-event-manager'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'event'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'show_in_rest'       => true,
    );

    register_post_type('event', $args);
}
add_action('init', 'cem_register_event_post_type');

function cem_add_event_meta_boxes() {
    add_meta_box(
        'event_details',
        __('Event Details', 'campus-event-manager'),
        'cem_event_meta_box_callback',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'cem_add_event_meta_boxes');

function cem_event_meta_box_callback($post) {
    wp_nonce_field('cem_event_meta_box', 'cem_event_meta_box_nonce');

    $event_date = get_post_meta($post->ID, '_event_date', true);
    $event_time = get_post_meta($post->ID, '_event_time', true);
    $event_location = get_post_meta($post->ID, '_event_location', true);

    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th><label for="event_date">' . __('Event Date', 'campus-event-manager') . '</label></th>';
    echo '<td><input type="date" id="event_date" name="event_date" value="' . esc_attr($event_date) . '" /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th><label for="event_time">' . __('Event Time', 'campus-event-manager') . '</label></th>';
    echo '<td><input type="time" id="event_time" name="event_time" value="' . esc_attr($event_time) . '" /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th><label for="event_location">' . __('Event Location', 'campus-event-manager') . '</label></th>';
    echo '<td><input type="text" id="event_location" name="event_location" value="' . esc_attr($event_location) . '" class="regular-text" /></td>';
    echo '</tr>';
    echo '</table>';
}

function cem_save_event_meta_box_data($post_id) {
    if (!isset($_POST['cem_event_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['cem_event_meta_box_nonce'], 'cem_event_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['event_date'])) {
        update_post_meta($post_id, '_event_date', sanitize_text_field($_POST['event_date']));
    }

    if (isset($_POST['event_time'])) {
        update_post_meta($post_id, '_event_time', sanitize_text_field($_POST['event_time']));
    }

    if (isset($_POST['event_location'])) {
        update_post_meta($post_id, '_event_location', sanitize_text_field($_POST['event_location']));
    }
}
add_action('save_post', 'cem_save_event_meta_box_data');

function cem_enqueue_scripts() {
    if (is_admin()) {
        wp_enqueue_style('cem-admin-style', CEM_PLUGIN_URL . 'assets/css/admin.css', array(), '1.0.0');
    } else {
        wp_enqueue_style('cem-frontend-style', CEM_PLUGIN_URL . 'assets/css/frontend.css', array(), '1.0.0');
    }
}
add_action('wp_enqueue_scripts', 'cem_enqueue_scripts');
add_action('admin_enqueue_scripts', 'cem_enqueue_scripts');

function cem_activate() {
    cem_register_event_post_type();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cem_activate');

function cem_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'cem_deactivate');
?>
