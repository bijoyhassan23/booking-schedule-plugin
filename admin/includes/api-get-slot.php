<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('rest_api_init', function () {
    register_rest_route('booking/v1', '/get-slot', [
        'methods'  => 'POST',
        'callback' => 'bksh_get_slots',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        }
    ]);
});

function bksh_get_slots(WP_REST_Request $request) {
    global $wpdb;

    $table_slots   = $wpdb->prefix . 'booking_slots';
    $table_offdays = $wpdb->prefix . 'booking_offdays';

    $slot_type   = sanitize_text_field($request->get_param('slot_type'));
    $date_or_day = sanitize_text_field($request->get_param('date_or_day'));

    if (empty($slot_type) || empty($date_or_day)) {
        return new WP_REST_Response([
            'status'  => 'error',
            'message' => 'Missing required parameters'
        ], 400);
    }

    // 1️⃣ Check Offday Table First
    $offday = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table_offdays WHERE date_or_day = %s",
            $date_or_day
        ),
        ARRAY_A
    );

    if ($offday) {
        // Offday Found
        return new WP_REST_Response([
            'status'         => 'success',
            'offday_status'  => true,
            'offday_reason'  => $offday['offday_reason'],
            'slots'          => []
        ], 200);
    }

    // 2️⃣ If no offday, get the slots
    $slots = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_slots WHERE slot_type = %s AND date_or_day = %s",
            $slot_type,
            $date_or_day
        ),
        ARRAY_A
    );

    return new WP_REST_Response([
        'status'         => 'success',
        'offday_status'  => false,
        'offday_reason'  => '',
        'slots'          => $slots
    ], 200);
}