<?php
// Add custom field on single product page
add_action( 'woocommerce_before_add_to_cart_button', 'my_custom_product_option_price' );
function my_custom_product_option_price() {
    ?>
        <div class="my-extra-option">
            <label for="slot_infos">Booking Details:</label>
            <textarea id="slot_infos" name="slot_infos" rows="4" cols="50" readonly></textarea>

            <label for="extra_price">Extra Price:</label>
            <input type="number" id="extra_price" name="extra_price" step="0.01" min="0" value="0" readonly/>
        </div>
    <?php
}

add_filter( 'woocommerce_add_cart_item_data', 'save_slot_infos_price_to_cart', 10, 2 );
function save_slot_infos_price_to_cart( $cart_item_data, $product_id ) {
    if( isset($_POST['slot_infos']) && !empty($_POST['slot_infos']) ) {
        $cart_item_data['slot_infos'] = stripslashes($_POST['slot_infos']); // keep raw JSON
    }
    if( isset($_POST['extra_price']) && is_numeric($_POST['extra_price']) ) {
        $cart_item_data['extra_price'] = floatval($_POST['extra_price']);
    }
    return $cart_item_data;
}

add_filter( 'woocommerce_get_item_data', 'display_slot_infos_price_in_cart', 10, 2 );
function display_slot_infos_price_in_cart( $item_data, $cart_item ) {
    if( isset($cart_item['slot_infos']) ) {
        $decoded = json_decode($cart_item['slot_infos'], true);

        if( is_array($decoded) ) {
            // If multiple slots
            $slot_number = 0;
            foreach ($decoded as $slot) {
                $slot_number++;
                $value  = "Date: " . $slot['date'];
                if (!empty($slot['time'])) {
                    $value .= " | Time: " . $slot['time'];
                }
                if (isset($slot['extra']) && $slot['extra']) {
                    $value .= " | Extra: Yes";
                }

                $item_data[] = [
                    'name'  => "Booking Details {$slot_number}",
                    'value' => $value
                ];
            }
        } else {
            // Fallback if not valid JSON
            $item_data[] = array(
                'name'  => 'Booking Details',
                'value' => $cart_item['slot_infos']
            );
        }
    }

    if( isset($cart_item['extra_price']) && $cart_item['extra_price'] > 0 ) {
        $item_data[] = array(
            'name' => 'Extra Price',
            'value' => wc_price($cart_item['extra_price'])
        );
    }
    return $item_data;
}

add_action( 'woocommerce_before_calculate_totals', 'add_extra_price_to_cart_item', 20, 1 );
function add_extra_price_to_cart_item( $cart ) {
    if ( is_admin() && ! defined('DOING_AJAX') ) return;

    foreach ( $cart->get_cart() as $cart_item ) {
        if( isset($cart_item['extra_price']) ) {
            $cart_item['data']->set_price( $cart_item['data']->get_price() + $cart_item['extra_price'] );
        }
    }
}

add_action( 'woocommerce_add_order_item_meta', 'save_slot_infos_price_to_order', 10, 2 );
function save_slot_infos_price_to_order( $item_id, $values ) {
    if ( isset($values['slot_infos']) ) {
        // Save raw JSON for backend reference
        // wc_add_order_item_meta( $item_id, '_slot_infos_raw', $values['slot_infos'] );

        // Also save human-readable for emails/admin
        $decoded = json_decode($values['slot_infos'], true);
        if( is_array($decoded) ) {
            foreach ($decoded as $slot) {
                $readable = "Date: " . $slot['date'];
                if (!empty($slot['time'])) {
                    $readable .= " | Time: " . $slot['time'];
                }
                if (!empty($slot['slotId'])) {
                    $readable .= " | Slot ID: " . $slot['slotId'];
                }
                if (isset($slot['extra']) && $slot['extra']) {
                    $readable .= " | Extra: Yes";
                }
                wc_add_order_item_meta( $item_id, 'Booking Details', $readable );
            }
        } else {
            wc_add_order_item_meta( $item_id, 'Booking Details', $values['slot_infos'] );
        }
    }

    if ( isset($values['extra_price']) ) {
        wc_add_order_item_meta( $item_id, 'Extra Price', $values['extra_price'] );
    }

    bksh_insert_booking($slot['slotId'], $slot['date']);
}
