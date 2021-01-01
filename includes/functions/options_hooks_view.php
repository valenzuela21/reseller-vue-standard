<?php

function woocommerce_before_cart_view()
{
    $cart = WC()->cart->get_cart();
    $id_reseller = WC()->session->get('id_session');
    $count = WC()->cart->cart_contents_count;
    if ($count > 0) {
		$validate = true;
        foreach ($cart as $key => $cart_item) {
            if ($validate) {
				$validate = false;
                if (isset($cart_item['custom_data']['id_reseller']) && !empty($cart_item['custom_data']['id_reseller'])) {
                    $args = array(
                        'post_status' => 'publish',
                        'post_type' => 'tienda',
                        'p' => $id_reseller, // ID Shop Reseller
                    );

                    $consult = get_posts($args);
				
                    foreach ($consult as $item) {
                        $image_id = get_post_meta($item->ID, '_thumbnail_id', true);
                        $category = getCategory($item->ID);
					
                        ?>
                        <div class="container-reseller-cart">
                            <div class="row">
                                <div class="col-4 col-sm-3 col-md-3 col-lg-2 col-xl-2">
                                    <?php if ($image_id != false) { ?>
                                        <figure class="image is-128x128">
                                            <img class="is-rounded" src="<?php echo wp_get_attachment_url($image_id); ?>" alt="<?php echo $consult[0]->post_title; ?>">
                                        </figure>
                                    <?php } else {
                                        echo "<figure class='image is-128x128' > <img src='https://via.placeholder.com/250x250' alt='not-image'  class='is-rounded' /> </figure>";
                                    } ?>
                                </div>
                                <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <h2 style="margin: 0px"><?php echo $consult[0]->post_title; ?></h2>
                                    <?php

                                    foreach ($category as $item) {

                                        $url = $item['url'];
                                        $title = $item['title'];

                                        echo '<div><a href=' . $url . ' >' . $title . '</a></div>';
                                    }
                                    ?>
                                    <a class="btn_background_go" href="<?php echo get_permalink($id_reseller); ?>"> Ir a Tienda </a>
                                </div>
                            </div>
                        </div>

                        <?php
                    }

                }
		
            }
        }
    } else {
        WC()->session->__unset('id_session');
    }
}

add_action('woocommerce_before_cart_table', 'woocommerce_before_cart_view', 10);

function getCategory($product_id)
{
    $categoryGeneral = [];
    $category = get_the_terms($product_id, 'type-tendero');
    foreach ($category as $list) {
        $arrayItem['title'] = $list->name;
        $arrayItem['url'] = get_category_link($list->term_id);
        array_push($categoryGeneral, $arrayItem);
    }
    return $categoryGeneral;
}

/**
 * @param $cart_item
 * @param $cart_item_key
 */
function isa_woo_cart_attributes($cart_item, $cart_item_key)
{
    $item_data = $cart_item_key['data'];
    $post = get_post($item_data->id);
    $content = $post->post_title;
	if ( $item_data->post_type === "product_variation" ){
    	$attributes = $item_data->attributes;
    	$content .= "<div class='variables-list'>";
    	if(is_array($attributes)) {
        	foreach ($attributes as $attribute) {
           			$content .= "<span class='list-item'> " . $attribute . " </span>";
        	}
   		 }else{
        $content .= $attributes;
    	}
   		 $content .= "</div>";
	}

    $res = get_post_meta($item_data->id, 'meta_reseller_shopping', true);
	
    if (is_array($res)) {
        $content .= "<div class='product-list'>";
        $content .= "<p style='font-size: 12px; margin: 0px' >Tiendas Disponibles: </p>";
        $content .= "<ul class='list-cart-general'>";
        foreach ($res as $item) {
            $args = array(
                'p' => $item,
                'post_status' => 'publish',
                'post_type' => 'tienda',
            );

            $consult = get_posts($args);

            foreach ($consult as $value) {
                $content .= "<li class='list-item-cart'>" . $value->post_title . "</li>";
            }

        }
        $content .= "</ul>";
        $content .= "</div>";
    } else {
        $content = "<p style='font-size: 12px; margin: 0px' >No hay tiendas disponibles.</p>";
    }
    echo $content;
}

add_filter('woocommerce_cart_item_name', 'isa_woo_cart_attributes', 10, 2);

/**
 * @param $name
 * @param $item
 * @return string
 */

function kia_woocommerce_order_item_name($name, $item)
{

    $product_id = $item['product_id'];
    $tax = 'product_cat';

    $terms = wp_get_post_terms($product_id, $tax, array('fields' => 'names'));

    if ($terms && !is_wp_error($terms)) {
        $res = get_post_meta($product_id, 'meta_reseller_shopping', true);
		
        if (!empty($res)) {
            $name .= "<p style='font-size: 12px; margin: 0px'>Tiendas Disponibles: </p>";
            $name .= "<ul class='list-cart-general' >";
            foreach ($res as $item) {
                $args = array(
                    'p' => $item,
                    'post_status' => 'publish',
                    'post_type' => 'tienda',
                );

                $consult = get_posts($args);

                foreach ($consult as $value) {
                    $name .= "<li class='list-item-cart'>" . $value->post_title . "</li>";
                }

            }
            $name .= "</ul>";
        } else {
            $name .= "<p>No hay tiendas disponibles.</p>";
        }


    }

    return $name;
}

add_filter('woocommerce_order_item_name', 'kia_woocommerce_order_item_name', 10, 2);

function action_woocommerce_admin_order_item_headers()
{ ?>
    <th class="item sortable" colspan="2" data-sort="string-ins"><?php _e('Tiendas Disponibles', 'woocommerce'); ?></th>
    <?php
}

;

/**
 * @param $_product
 * @param $item
 * @param $item_id
 */
function action_woocommerce_admin_order_item_values($_product, $item, $item_id)
{ ?>
    <td class="name" colspan="2">
        <?php
  		$post = $item->get_data();
        $res = get_post_meta($post["product_id"], 'meta_reseller_shopping', true);
		
        if (!empty($res)) {
            echo "<ul class='list-cart-general'>";
            foreach ($res as $item) {
                $args = array(
                    'p' => $item,
                    'post_status' => 'publish',
                    'post_type' => 'tienda',
                );

                $consult = get_posts($args);

                foreach ($consult as $value) {
                    echo "<li class='list-item-cart'>" . $value->post_title . "</li>";
                }

            }
            echo "</ul>";
        } else {
            echo "<p style='font-size: 12px; margin: 0px' >No hay tiendas disponibles.</p>";
        }


        ?>
    </td>
    <?php
}

;

// add the action
add_action('woocommerce_admin_order_item_values', 'action_woocommerce_admin_order_item_values', 10, 3);
add_action('woocommerce_admin_order_item_headers', 'action_woocommerce_admin_order_item_headers', 10, 0);

function _view_cart_reseller(){

    $cart = WC()->cart->get_cart();
    $id_reseller = WC()->session->get('id_session');
    $count = WC()->cart->cart_contents_count;
    if ($count > 0) {
		$validate = true;
        foreach ($cart as $key => $cart_item) {
            if($validate) {
				$validate = false;
                if (isset($cart_item['custom_data']['id_reseller']) && !empty($cart_item['custom_data']['id_reseller'])) {
                    $args = array(
                        'post_status' => 'publish',
                        'post_type' => 'tienda',
                        'p' => $id_reseller, // ID Shop Reseller
                    );

                    $consult = get_posts($args);

                    foreach ($consult as $item) {
                        $image_id = get_post_meta($item->ID, '_thumbnail_id', true);
                        $category = getCategory($item->ID);
                        ?>
                        <div class="container-reseller-cart">
                            <div class="row">
                                <div class="col-4 col-sm-3 col-md-3 col-lg-2 col-xl-2">

                                    <?php if ($image_id != false) { ?>
                                        <figure class="image is-128x128">
                                            <img class="is-rounded" src="<?php echo wp_get_attachment_url($image_id); ?>" alt="<?php echo $consult[0]->post_title; ?>">
                                        </figure>
                                    <?php } else {
                                        echo "<figure class='image is-128x128' > <img src='https://via.placeholder.com/250x250' alt='not-image'  class='is-rounded' /> </figure>";
                                    } ?>
                                </div>
                                <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <h2 style="margin: 0px"><?php echo $consult[0]->post_title; ?></h2>
                                    <?php

                                    foreach ($category as $item) {

                                        $url = $item['url'];
                                        $title = $item['title'];

                                        echo '<div><a href=' . $url . ' >' . $title . '</a></div>';
                                    }
                                    ?>
                                    <a class="btn-go-reseller" href="<?php echo get_permalink($id_reseller); ?>"> Ir Tienda </a>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                }

            }
        }
    } else {
        WC()->session->__unset('id_session');
    }

}
add_action('woocommerce_before_checkout_form', '_view_cart_reseller', 10, 3);




