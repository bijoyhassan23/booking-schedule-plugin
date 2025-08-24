<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('rest_api_init', function () {
    register_rest_route('booking/v1', '/update-slot', [
        'methods'  => 'POST',
        'callback' => 'bksh_update_slot',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        }
    ]);
});

function bksh_update_slot(WP_REST_Request $request) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'booking_slots';

    $slot_id = intval($request->get_param('slot_id'));

    $data = [];

    // Only add fields that are actually sent in the request
    if ($request->get_param('slot_type') !== null) {
        $data['slot_type'] = sanitize_text_field($request->get_param('slot_type'));
    }
    if ($request->get_param('date_or_day') !== null) {
        $data['date_or_day'] = sanitize_text_field($request->get_param('date_or_day'));
    }
    if ($request->get_param('start_time') !== null) {
        $data['start_time'] = sanitize_text_field($request->get_param('start_time'));
    }
    if ($request->get_param('end_time') !== null) {
        $data['end_time'] = sanitize_text_field($request->get_param('end_time'));
    }
    if ($request->get_param('booked_status') !== null) {
        $data['booked_status'] = intval($request->get_param('booked_status'));
    }

    $updated = $wpdb->update(
        $table_name,
        $data,
        ['slot_id' => $slot_id]
    );

    if ( $updated === false ) {
        return new WP_REST_Response([
            'status'  => 'error',
            'message' => 'Failed to update slot'
        ], 500);
    }

    return new WP_REST_Response([
        'status'  => 'success',
        'message' => 'Slot updated successfully',
        'slot_id' => $slot_id
    ], 200);
}
