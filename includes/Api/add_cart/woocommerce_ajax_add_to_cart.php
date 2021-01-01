<?php
defined('ABSPATH') || exit;

class woocommerce_ajax_add_to_cart
{

    public function __construct()
    {
        add_action('wp_ajax_woocommerce_ajax_add_to_cart_custom', [$this, 'woocommerce_ajax_add_to_cart_custom']);
        add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart_custom', [$this, 'woocommerce_ajax_add_to_cart_custom']);
    }

    public function woocommerce_ajax_add_to_cart_custom()
    {
        $id_session_reseller = WC()->session->get('id_session');
        $reseller_id = absint($_POST['reseller_id']);

        $count = WC()->cart->cart_contents_count;

        if (!empty($reseller_id)) {
            if($id_session_reseller === $reseller_id || $count <= 0 ) {
                $cart_item_data = [];
			
                $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
                $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
                $variation_id = absint($_POST['variation_id']);
                $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
                $product_status = get_post_status($product_id);
                $cart_item_data['custom_data']['id_reseller'] = $reseller_id;
				$_product = wc_get_product($product_id);
				$pressing = $_product->get_price();
				
				/*if($pressing <= 0){
					$data = array(
                        'error' => true,
                        'message' => "No se puede adquirir este producto. Su valor es ".$pressing );

                    echo wp_send_json($data);
					return;
				}*/

                WC()->session->set('id_session', $reseller_id);

                if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id, wc_get_product_variation_attributes($variation_id), $cart_item_data) && 'publish' === $product_status) {

                    do_action('woocommerce_ajax_added_to_cart', $product_id);

                    if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {

                        wc_add_to_cart_message(array($product_id => $quantity), true);

                    }

                    WC_AJAX:: get_refreshed_fragments();


                } else {

                    $data = array(
                        'error' => true,
                        'message' => "No se pudo añadir al carrito de compras.");

                    echo wp_send_json($data);
                }

                wp_die();

            }else{

                $data = array(
                    'error' => true,
                    'message' => "Solo se permite un proveedor o marca en el carrito.");

                echo wp_send_json($data);

                wp_die();

            }

        } else {
            $data = array(
                'error' => true,
                'message' => "No hay proveedor en este producto, por favor asignar uno.");
            echo wp_send_json($data);

            wp_die();
        }
    }

}

$add_cart = new woocommerce_ajax_add_to_cart();