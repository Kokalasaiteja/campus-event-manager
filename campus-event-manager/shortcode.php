<?php
/**
 * Shortcode functions for Campus Event Manager plugin.
 *
 * This file defines the [campus_events] shortcode to display upcoming events.
 *
 * @package CampusEventManager
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the [campus_events] shortcode.
 *
 * Displays a list of upcoming events in chronological order.
 */
function cem_register_shortcode() {
    add_shortcode('campus_events', 'cem_display_events');
}
add_action('init', 'cem_register_shortcode');

/**
 * Display upcoming events using the shortcode.
 *
 * Retrieves and displays events sorted by date and time.
 *
 * @return string HTML output of the events list.
 */
function cem_display_events() {
    // Query for upcoming events
    $args = array(
        'post_type'      => 'event',
        'posts_per_page' => -1, // Get all events
        'meta_key'       => '_event_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => '_event_date',
                'value'   => date('Y-m-d'),
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        ),
    );

    $events_query = new WP_Query($args);

    // Start output buffering
    ob_start();

    if ($events_query->have_posts()) {
        echo '<div class="campus-events-list">';
        while ($events_query->have_posts()) {
            $events_query->the_post();
            $event_date = get_post_meta(get_the_ID(), '_event_date', true);
            $event_time = get_post_meta(get_the_ID(), '_event_time', true);
            $event_location = get_post_meta(get_the_ID(), '_event_location', true);

            // Apply filter to event content
            $event_content = apply_filters('cem_event_content', get_the_content());

            echo '<div class="campus-event-item">';
            echo '<h3>' . esc_html(get_the_title()) . '</h3>';
            echo '<p><strong>Date:</strong> ' . esc_html($event_date) . '</p>';
            echo '<p><strong>Time:</strong> ' . esc_html($event_time) . '</p>';
            echo '<p><strong>Location:</strong> ' . esc_html($event_location) . '</p>';
            echo '<div class="event-description">' . wp_kses_post($event_content) . '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>' . __('No upcoming events found.', 'campus-event-manager') . '</p>';
    }

    wp_reset_postdata();

    // Return the buffered output
    return ob_get_clean();
}
?>
