<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// Register API route
add_action('rest_api_init', function () {
    register_rest_route('booking/v1', '/add-booking', [
        'methods'             => 'POST',
        'callback'            => 'bksh_add_booking_api',
        'permission_callback' => '__return_true',
    ]);
});

/**
 * API wrapper for add booking
 */
function bksh_add_booking_api(WP_REST_Request $request) {
    $slot_id      = $request->get_param('slot_id');
    $booking_date = $request->get_param('booking_date');
    $user_id      = $request->get_param('user_id');

    $result = bksh_insert_booking($slot_id, $booking_date, $user_id);

    if ($result['success']) {
        return new WP_REST_Response([
            'status'  => 'success',
            'message' => 'Booking added successfully',
            'data'    => $result['booking_info']
        ], 200);
    } else {
        return new WP_REST_Response([
            'status'  => 'error',
            'message' => $result['message'] ?? 'Failed to add booking'
        ], 500);
    }
}

/**
 * Core function: add booking
 * Returns array with success and booking_info
 */
function bksh_insert_booking($slot_id, $booking_date, $user_id = null) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'booking_list';

    // Sanitize
    $slot_id      = sanitize_text_field($slot_id);
    $booking_date = sanitize_text_field($booking_date);
    $user_id      = !empty($user_id) ? sanitize_text_field($user_id) : null;

    if (empty($slot_id) || empty($booking_date)) {
        return [
            'success' => false,
            'message' => 'Missing required fields'
        ];
    }

    $data = [
        'slot_id'      => $slot_id,
        'booking_date' => $booking_date,
    ];
    if (!empty($user_id)) {
        $data['user_id'] = $user_id;
    }

    $inserted = $wpdb->insert($table_name, $data);

    if ($inserted === false) {
        return [
            'success' => false,
            'message' => 'Database insert failed'
        ];
    }

    return [
        'success' => true,
        'booking_info' => [
            'booking_id'   => $wpdb->insert_id,
            'slot_id'      => $slot_id,
            'booking_date' => $booking_date,
            'user_id'      => $user_id
        ]
    ];
}



