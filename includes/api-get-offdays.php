<?php
add_action('rest_api_init', function () {
    register_rest_route('booking/v1', '/get-offdays', [
        'methods'  => 'GET',
        'callback' => 'bksh_get_offdays',
        'permission_callback' => '__return_true' // only same-site access
    ]);
});

function bksh_get_offdays(WP_REST_Request $request) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'booking_offdays';

    $today = current_time('Y-m-d');

    // Get weekly_schedule and future custom_date
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE slot_type = %s OR (slot_type = %s AND date_or_day >= %s) ORDER BY date_or_day ASC",
            'weekly_schedule',
            'custom_date',
            $today
        ),
        ARRAY_A
    );

    $offdays = [];
    foreach ($results as $row) {
        if ($row['slot_type'] === 'custom_date') {
            $offdays[] = $row['date_or_day']; // just date string
        } else {
            // weekly_schedule → convert weekday name to number (1=Mon ... 7=Sun)
            $weekday = date('N', strtotime($row['date_or_day']));
            $offdays[] = (int)$weekday;
        }
    }

    return new WP_REST_Response([
        'status'  => 'success',
        'offdays' => $offdays
    ], 200);
}
