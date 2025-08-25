<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Register API
add_action('rest_api_init', function () {
    register_rest_route('booking/v1', '/get-slots', [
        'methods'             => 'GET',
        'callback'            => 'bksh_get_slots_api',
        'permission_callback' => '__return_true',
    ]);
});

/**
 * API: Fetch slots with booking count
 */
function bksh_get_slots_api(WP_REST_Request $request) {
    global $wpdb;
    $slots_table   = $wpdb->prefix . 'booking_slots';
    $booking_table = $wpdb->prefix . 'booking_list';

    $date = sanitize_text_field($request->get_param('date'));
    if (empty($date)) {
        return new WP_REST_Response([
            'status'  => 'error',
            'message' => 'Missing date parameter'
        ], 400);
    }

    // Determine weekday (monday, tuesday, etc.)
    $weekday = strtolower(date('l', strtotime($date)));

    // First check for custom_date slots
    $slots = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $slots_table WHERE slot_type = %s AND date_or_day = %s",
            'custom_date', $date
        ), ARRAY_A
    );

    // If no slots for that date, fallback to weekday slots
    if (empty($slots)) {
        $slots = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $slots_table WHERE slot_type = %s AND date_or_day = %s",
                'weekly_schedule', $weekday
            ), ARRAY_A
        );
    }

    $response = [];

    foreach ($slots as $slot) {
        // Count bookings for this slot_id and date
        $booked = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $booking_table WHERE slot_id = %s AND booking_date = %s",
                $slot['slot_id'], $date
            )
        );

        $response[] = [
            'slot_id'    => $slot['slot_id'],
            'start_time' => $slot['start_time'],
            'end_time'   => $slot['end_time'],
            'date'       => $date,
            'booked'     => intval($booked)
        ];
    }

    return new WP_REST_Response($response, 200);
}

