<?php
// Hook into the admin menu
add_action('admin_menu', 'bksh_booking_admin_page');

function bksh_booking_admin_page() {
    add_menu_page(
        'Booking Settings',  
        'Booking',
        'manage_options',
        'booking',
        'bksh_booking_page_html',
        'dashicons-calendar',
        20
    );

    add_submenu_page(
        'booking',
        'Booking Times',
        'Settings',
        'manage_options',
        'booking-times',
        'bksh_booking_times_page_html'
    );
}



