<?php
/**
 * Plugin Name: Booking Plugin 
 * Plugin URI: https://bijoy.dev/
 * Description: Plugin For booking functionality.
 * Version: 1.0.1
 * Author: Bijoy
 * Author URI: https://bijoy.dev/
 * License: GPL2
 */

// Exit if accessed directly
if(!defined('ABSPATH')) exit; 


if (!function_exists('bksh_on_activate') && !function_exists('bksh_on_deactivate')) {

    // Plugin Acttivation Hook
    function bksh_on_activate(){
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Table 1: booking_slots
        $table_slots = $wpdb->prefix . 'booking_slots';
        $sql1 = "CREATE TABLE IF NOT EXISTS $table_slots (
            slot_id INT(11) NOT NULL AUTO_INCREMENT,
            slot_type VARCHAR(20) NOT NULL,
            date_or_day VARCHAR(20) NOT NULL,
            start_time TIME NOT NULL,
            end_time TIME NOT NULL,
            PRIMARY KEY (slot_id)
        ) $charset_collate;";

        // Table 2: booking_offdays
        $table_offdays = $wpdb->prefix . 'booking_offdays';
        $sql2 = "CREATE TABLE IF NOT EXISTS $table_offdays (
            offday_id INT(11) NOT NULL AUTO_INCREMENT,
            slot_type VARCHAR(20) NOT NULL,
            date_or_day VARCHAR(20) NOT NULL,
            offday_reason TEXT NOT NULL,
            PRIMARY KEY (offday_id)
        ) $charset_collate;";

        // Table 3: booking_list
        $table_bookinglist = $wpdb->prefix . 'booking_list';
        $sql3 = "CREATE TABLE IF NOT EXISTS $table_bookinglist (
            booking_id INT(11) NOT NULL AUTO_INCREMENT,
            slot_id VARCHAR(20) NOT NULL,
            booking_date VARCHAR(20) NOT NULL,
            user_id VARCHAR(20),
            PRIMARY KEY (booking_id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql1);
        dbDelta($sql2);
        dbDelta($sql3);
    }
    register_activation_hook( __FILE__, "bksh_on_activate");

    // Plugin Deactivation Hook
    function bksh_on_deactivate(){

    }
    register_deactivation_hook( __FILE__, "bksh_on_deactivate");

}


// Include the main plugin file
require_once plugin_dir_path(__FILE__) . 'admin/includes/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'admin/includes/booking-page.php';

// backend apis
require_once plugin_dir_path(__FILE__) . 'admin/includes/api-insert-slot.php';
require_once plugin_dir_path(__FILE__) . 'admin/includes/api-update-slot.php';
require_once plugin_dir_path(__FILE__) . 'admin/includes/api-delete-slot.php';
require_once plugin_dir_path(__FILE__) . 'admin/includes/api-get-slot.php';
require_once plugin_dir_path(__FILE__) . 'admin/includes/api-offday-handle.php';

require_once plugin_dir_path(__FILE__) . 'includes/booking-admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/datepicker-shortcode.php';

// Frontend apis
require_once plugin_dir_path(__FILE__) . 'includes/api-get-offdays.php';
require_once plugin_dir_path(__FILE__) . 'includes/api-book-slot.php';
require_once plugin_dir_path(__FILE__) . 'includes/api-get-slots.php';

// Woocommerce
require_once plugin_dir_path(__FILE__) . 'includes/connect-with-woocommerce.php';


// Enqueue admin styles
function bksh_enqueue_admin_styles() {
    if (isset($_GET['page']) && $_GET['page'] === 'booking-times') {
        wp_enqueue_style('bksh-admin-setting-style', plugin_dir_url(__FILE__) . 'admin/css/setting-admin.css', [], '1.0', 'all');
        wp_enqueue_script( 'bksh-admin-setting-script', plugin_dir_url(__FILE__) . 'admin/js/setting-admin.js', [], '1.0', true);
        wp_localize_script('bksh-admin-setting-script', 'bksh_admin', [
            'nonce' => wp_create_nonce('wp_rest'),
            'api_insert_url'  => esc_url_raw(rest_url('booking/v1/')) . 'add-slot',
            'api_update_url'  => esc_url_raw(rest_url('booking/v1/')) . 'update-slot',
            'api_delete_url'  => esc_url_raw(rest_url('booking/v1/')) . 'delete-slot',
            'api_get_url'  => esc_url_raw(rest_url('booking/v1/')) . 'get-slot',
            'api_add_offday_url'  => esc_url_raw(rest_url('booking/v1/')) . 'add-offday',
            'api_delete_offday_url'  => esc_url_raw(rest_url('booking/v1/')) . 'delete-offday',
        ]);
    }
}
add_action('admin_enqueue_scripts', 'bksh_enqueue_admin_styles', 20);


// Check shoortcode has
function bksh_has_shortcode($post_id, $shortcode) {
    $post = get_post($post_id);
    if (!$post) return false;

    // Check in normal post content
    if (has_shortcode($post->post_content, $shortcode)) {
        return true;
    }

    // Check Elementor meta content
    $elementor_data = get_post_meta($post_id, '_elementor_data', true);
    if ($elementor_data && strpos($elementor_data, $shortcode) !== false) {
        return true;
    }

    return false;
}


function bksh_enqueue_front_end_styles(){
    global $post;
    if(!is_a($post, 'WP_Post')) return;

    if(bksh_has_shortcode(get_the_ID(), 'date_picker')){
        wp_enqueue_style( "bksh-front-end-style", plugin_dir_url( __FILE__ ) . 'assets/css/datepicker.css', [], '1.0', 'all' );

        wp_enqueue_script( 'bksh-fullcalendar-script', plugin_dir_url(__FILE__) . 'assets/js/fullcalendar@6.1.14.min.js', [], '1.0', false);
        wp_enqueue_script( 'bksh-frond-end-script', plugin_dir_url(__FILE__) . 'assets/js/datepicker-shortcode.js', [], '1.0', true);
        wp_localize_script( 'bksh-frond-end-script', 'bksh_frontend', [
            'nonce' => wp_create_nonce('wp_rest'),
            'api_get_offdays_url'  => esc_url_raw(rest_url('booking/v1/')) . 'get-offdays',
            'api_add_booking_url'  => esc_url_raw(rest_url('booking/v1/')) . 'add-booking',
            'api_get_slots_url'  => esc_url_raw(rest_url('booking/v1/')) . 'get-slots',
        ]);
    }
}
add_action( "wp_enqueue_scripts", "bksh_enqueue_front_end_styles");



