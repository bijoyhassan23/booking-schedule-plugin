<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Register REST routes
add_action('rest_api_init', function () {
    // Add offday
    register_rest_route('booking/v1', '/add-offday', [
        'methods'  => 'POST',
        'callback' => 'bksh_add_offday',
        'permission_callback' => function () {
            return current_user_can('manage_options'); // Admin only
        }
    ]);

    // Delete offday
    register_rest_route('booking/v1', '/delete-offday', [
        'methods'  => 'POST',
        'callback' => 'bksh_delete_offday',
        'permission_callback' => function () {
            return current_user_can('manage_options'); // Admin only
        }
    ]);
});

// Add offday
function bksh_add_offday(WP_REST_Request $request) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'booking_offdays';

    $data = [
        'slot_type'   => sanitize_text_field($request->get_param('slot_type')),
        'date_or_day'   => sanitize_text_field($request->get_param('date_or_day')),
        'offday_reason' => sanitize_textarea_field($request->get_param('offday_reason'))
    ];

    $inserted = $wpdb->insert($table_name, $data);

    if ($inserted === false) {
        return new WP_REST_Response([
            'status'  => 'error',
            'message' => 'Failed to add offday'
        ], 500);
    }

    return new WP_REST_Response([
        'status'  => 'success',
        'message' => 'Offday added successfully',
        'offday_id' => $wpdb->insert_id
    ], 200);
}

// Delete offday
function bksh_delete_offday(WP_REST_Request $request) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'booking_offdays';

    $date_or_day = $request->get_param('date_or_day');

    if (!$date_or_day) {
        return new WP_REST_Response([
            'status'  => 'error',
            'message' => 'Invalid offday ID'
        ], 400);
    }

    $deleted = $wpdb->delete($table_name, ['date_or_day' => $date_or_day], ['%d']);

    if ($deleted === false) {
        return new WP_REST_Response([
            'status'  => 'error',
            'message' => 'Failed to delete offday'
        ], 500);
    }

    return new WP_REST_Response([
        'status'  => 'success',
        'message' => 'Offday deleted successfully'
    ], 200);
}
