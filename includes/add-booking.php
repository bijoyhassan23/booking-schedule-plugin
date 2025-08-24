<?php
update_post_meta(100, '_bijoy_test_meta', "okay");
function bksh_register_post_type() {
    $labels =[
        'name'               => 'Services',
        'singular_name'      => 'Service',
        'add_new'            => 'Add New Service',
        'add_new_item'       => 'Add New Service',
        'new_item'           => 'New Service',
        'edit_item'          => 'Edit Service',
        'view_item'          => 'View Service',
        'all_items'          => 'All Service',
    ];

    $args = array(
        'labels'             => $labels,
        'menu_icon'          => 'dashicons-book',
        'supports' => ['title'], 
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
    );

    register_post_type( 'services', $args );
}

add_action( "init",  "bksh_register_post_type" );




add_action('add_meta_boxes', 'bh_add_book_custom_meta_box');

function bh_add_book_custom_meta_box() {
    add_meta_box(
        'bh_book_details',           // Meta box ID (used in HTML)
        'Book Details',              // Title shown on the meta box
        'bh_book_meta_box_callback', // Callback to output fields
        'services',                      // Post type slug
        'normal',                    // Context: 'normal', 'side', etc.
        'high'                       // Priority
    );
}


function bh_book_meta_box_callback($post) {
    // Retrieve current value from DB
    $author = get_post_meta($post->ID, '_bh_book_author', true);

    // Nonce for security
    wp_nonce_field('bh_save_book_author', 'bh_book_author_nonce');

    echo '<label for="bh_book_author">Author Name:</label>';
    echo '<input type="text" id="bh_book_author" name="bh_book_author" value="' . esc_attr($author) . '" style="width:100%;">';
}


add_action('save_post', 'bh_save_book_custom_meta');

function bh_save_book_custom_meta($post_id) {
    // Check nonce
    if (!isset($_POST['bh_book_author_nonce']) || !wp_verify_nonce($_POST['bh_book_author_nonce'], 'bh_save_book_author')) {
        return;
    }

    // Check for autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Sanitize and save
    if (isset($_POST['bh_book_author'])) {
        update_post_meta($post_id, '_bh_book_author', sanitize_text_field($_POST['bh_book_author']));
    }
}














// Update cart item price and store plan details
add_filter( 'woocommerce_add_cart_item_data', 'my_custom_cart_item_data', 10, 3 );

function my_custom_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
    // Add custom data to the cart item
    $cart_item_data['my_custom_key'] = 'some_value';

    return $cart_item_data;
}


// Display custom cart item data in the cart
add_filter( 'woocommerce_get_item_data', 'display_custom_cart_item_data', 10, 2 );
function display_custom_cart_item_data( $item_data, $cart_item ) {
    if ( isset( $cart_item['my_custom_key'] ) ) {
        $item_data[] = array(
            'key'   => 'Custom Data',
            'value' => wc_clean( $cart_item['my_custom_key'] ),
        );
    }
    return $item_data;
}


add_action( 'woocommerce_add_order_item_meta', 'add_custom_data_to_order_items', 10, 2 );
function add_custom_data_to_order_items( $item_id, $values ) {
    if ( isset( $values['my_custom_key'] ) ) {
        wc_add_order_item_meta( $item_id, 'Custom Data', $values['my_custom_key'] );
    }
}


add_action('woocommerce_before_calculate_totals', 'update_cart_price');
function update_cart_price($cart) {
    foreach ($cart->get_cart() as $cart_item) {
        $cart_item['data']->set_price('300');
    }
}