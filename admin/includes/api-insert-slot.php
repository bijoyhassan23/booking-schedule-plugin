<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('rest_api_init', function () {
    register_rest_route('booking/v1', '/add-slot', [
        'methods'  => 'POST',
        'callback' => 'bksh_insert_slot',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        }
    ]);
});


function bksh_insert_slot(WP_REST_Request $request) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'booking_slots';

    $data = [
        'slot_type'     => sanitize_text_field($request->get_param('slot_type')),
        'date_or_day'   => sanitize_text_field($request->get_param('date_or_day')),
        'start_time'    => sanitize_text_field($request->get_param('start_time')),
        'end_time'      => sanitize_text_field($request->get_param('end_time')),
    ];

    $inserted = $wpdb->insert($table_name, $data);

    if ( $inserted === false ) {
        return new WP_REST_Response([
            'status'  => 'error',
            'message' => 'Failed to insert slot'
        ], 500);
    }

    return new WP_REST_Response([
        'status'  => 'success',
        'message' => 'Slot inserted successfully',
        'slot_id' => $wpdb->insert_id
    ], 200);
}


