<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('rest_api_init', function () {
    register_rest_route('booking/v1', '/delete-slot', [
        'methods'  => 'POST',
        'callback' => 'bksh_delete_slot',
        'permission_callback' => function () {
            return current_user_can('manage_options'); // Only admin
        }
    ]);
});

function bksh_delete_slot(WP_REST_Request $request) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'booking_slots';
    $slot_id = intval($request->get_param('slot_id'));

    if ( ! $slot_id ) {
        return new WP_REST_Response([
            'status'  => 'error',
            'message' => 'Invalid slot ID'
        ], 400);
    }

    $deleted = $wpdb->delete($table_name, ['slot_id' => $slot_id]);

    if ( $deleted === false ) {
        return new WP_REST_Response([
            'status'  => 'error',
            'message' => 'Failed to delete slot'
        ], 500);
    }

    return new WP_REST_Response([
        'status'  => 'success',
        'message' => 'Slot deleted successfully'
    ], 200);
}
