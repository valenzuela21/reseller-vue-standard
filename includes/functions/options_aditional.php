<?php

class options_aditional
{

    function __construct()
    {
        add_action('init', [$this, 'remove_cart']);
        add_action('woocommerce_single_product_summary', [$this, 'add_cart_btnShopping'], 25, 20);
        add_action('woocommerce_before_single_product', [$this, 'shopping_master_reseller'], 5);
        add_action('init', [$this, 'require_field']);
        add_action('woocommerce_order_status_processing', [$this, 'wc_processing_finish']);
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
        add_filter( 'woocommerce_after_single_product_summary', [$this,'view_products_relations_hook'], 20 );
        add_action( 'woocommerce_single_product_summary', [$this,'woocommerce_template_single_title_hook'], 5 );
    }

    public function remove_cart()
    {
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    }

    function require_field()
    {
        require_once plugin_dir_path(__FILE__) . 'upload_image.php';
        require_once plugin_dir_path(__FILE__) . 'upload_image_product.php';
        require_once plugin_dir_path(__FILE__) . '../Api/add_cart/woocommerce_ajax_add_to_cart.php';
    }

    public function shopping_master_reseller()
    {

        $reseller_id = get_post_meta(get_the_ID(), 'meta_reseller_shopping', true);
        $id = $reseller_id;

        if (!empty($id) || $id != "") {

            $args = array(
                'p' => $id[0],
                'post_status' => 'publish',
                'post_type' => 'tienda',
            );

            $consult = get_posts($args);
            $image_id = get_post_meta($id[0], '_thumbnail_id', true);
            $category = $this->getCategory($id[0]);
            $bgcolor = get_post_meta($id[0], 'color_banner_first_reseller', true);

            if(empty($bgcolor)){
                $bgcolor = "transparent";
                $color="#000";
            }else{
                $bgcolor = $bgcolor;
                $color="#fff";
            }

            ?>
            <div class="container-reseller" style="background-color: <?php echo $bgcolor; ?>; " >
                <div class="row">
                    <div class="col-4 col-sm-3 col-md-3 col-lg-2 col-xl-2">
                        <?php if ($image_id != false) { ?>
                            <figure class="image is-128x128">
                                <img class="is-rounded" alt="<?php echo $consult[0]->post_title; ?>" src="<?php echo wp_get_attachment_url($image_id); ?>">
                            </figure>
                        <?php } else {
                            echo " <img src='https://via.placeholder.com/250x250' alt='not-image' />";
                        } ?>
                    </div>
                    <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                        <div class="section-title-reseller">
                            <h3 style="margin: 0px; color: <?php echo $color;?>" ><?php echo $consult[0]->post_title; ?></h3>
                                 <?php
                                    foreach ($category as $item) {

                                            $url = $item['url'];
                                            $title = $item['title'];

                                            echo '<h6><a style="color:'.$color.'" href=' . $url . ' >' . $title . '</a></h6>';
                                        }
                                 ?>
                                 <a class="btn-go-reseller"  href="<?php echo get_permalink($id[0]); ?>"> Ir a Tienda </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            echo " ";
        }
    }

    public function add_cart_btnShopping()
    {

        wp_enqueue_style('baseplugin-frontend-buttom-payment');
        wp_enqueue_script('baseplugin-frontend-buttom-payment');

        echo "<div id='vue-frontend-payment-btn'></div>";

    }

    public function getCategory($product_id)
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

    public function wc_processing_finish($order)
    {
        $order = wc_get_order($order);
		// get the ID of the order
        $order_id = $order->get_id();

        $data = $order->get_data();

        if ($data['status'] == 'processing') {
        $payment_method = $data['payment_method_title'];
        $date_order = $data['date_created']->date('Y-m-d H:i:s');


        /*=====Details Client ======*/
        $email_billing = $data['billing']['email'];
		$shipping_address = get_post_meta($order_id, '_billing_address', true );
		$address_city = get_post_meta($order_id, 'billing_shipping_city', true);
        $phone_billing = $data['billing']['phone'];
		$shipping_billing = $order->get_shipping_total();
        $subtotal_shipping = $order->get_subtotal();
        $total_shipping = $order->get_total();

        
        $order_items = $order->get_items();

        $id_reseller = WC()->session->get('id_session');
        
        $email   = get_post_meta($id_reseller, 'input_options_product_email_tendero', true);
			
		if(empty($email) || $email == "" ){
				
			return;
		
		}else{
			
        $heading = 'From:tupautaempresarial<info@tupautaempresarial.com>' . "\r\n\\"; 
        $subject = "Nuevo Pedido Orden No." . $order_id;

        $message = '<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body" style="font-family: Arial, sans-serif;
        line-height: 1.3em; color: #919191;">
          <tbody><tr style="font-family: Arial, sans-serif; line-height:
        1.3em;">
          <td valign="top" class="body_content" style="line-height: 1.3em; font-family: Arial, sans-serif;
        color: #919191; background-color: #ffffff;">
          
          
          <!-- Content -->';

		$message .= '<table border="0" cellspacing="0" width="100%" style="font-family: Arial, sans-serif; line-height: 1.3em;
        color: #919191;">
          <tbody><tr style="font-family: Arial,
        sans-serif; line-height: 1.3em;">
          <td valign="top" class="body_content_inner" style="line-height: 1.3em;
        font-family: Arial; text-align: left; padding-left: 55px;
        padding-right: 55px; padding-top: 45px; padding-bottom:
        45px;">
          
         <div class="top_heading" style="font-family:
        Arial, sans-serif; font-size: 22px; text-align: left;
        font-weight: bold;">
             <p style="margin: .6em 0;">'.__('Nuevo Pedido', 'master-tienda').'</p> </div>
         
         <p style="margin: .6em 0;">'.__('Tienes un nuevo pedido: ', 'master-tienda' ).' <span class="ec_shortcode
        ec_firstname">'.$data['billing']['first_name'].'</span> <span class="ec_shortcode
        ec_lastname">'.$data['billing']['last_name'].'</span>.</p> <p style="margin: .6em 0;">'.__('Su orden es el siguiente','').'<span class="ec_shortcode
        ec_order"> #'.$order_id.' <span class="ec_datetime">(<time>'.$date_order.'</time>)</span></span></p> 
         
         
         <table cellpadding="0" cellspacing="0" border="0" width="100%" style="font-family:
        Arial, sans-serif; line-height: 1.3em; color: #919191;">
         
        <tbody><tr style="font-family: Arial, sans-serif; line-height:
        1.3em;">
          <td class="top_content_container" style="font-family: Arial, sans-serif; line-height: 1.3em;
        padding: 10px 0 22px 0;">
          
          <table class="special-title-holder" width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial,
        sans-serif; line-height: 1.3em; color: #919191;">
          <tbody><tr style="font-family: Arial, sans-serif; line-height:
        1.3em;">
          <td style="font-family: Arial, sans-serif;
        line-height: 1.3em; font-size: 1px;">
          
          <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; line-height: 1.3em;
        color: #919191;">
          <tbody><tr style="font-family: Arial,
        sans-serif; line-height: 1.3em;">
          <td class="header_content_h2_space_before" style="font-family:
        Arial, sans-serif; line-height: 1.3em; height: 26px;
        font-size: 0px;"></td>
          </tr>
          </tbody></table>
          
          <!-- Heading
        with lines on either side -->
          <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; line-height: 1.3em;
        color: #919191;">
          <tbody><tr style="font-family: Arial,
        sans-serif; line-height: 1.3em;">
          <td width="50%" style="font-family: Arial, sans-serif; line-height: 1.3em;
        font-size: 1px;">
          <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; line-height: 1.3em;
        color: #919191;">
          <tbody><tr height="50%" style="font-family:
        Arial, sans-serif; line-height: 1.3em; height: 50%;">
          <td style="font-family: Arial, sans-serif; line-height: 1.3em;
        font-size: 1px;">&nbsp;</td>
          </tr>
          <tr height="50%" style="font-family: Arial, sans-serif; line-height: 1.3em;
        height: 50%;">
          <td class="header_content_h2_border" style="font-family: Arial, sans-serif; line-height: 1.3em;
        font-size: 1px; border-top: 2px solid #282828;"></td>
         
        </tr>
          </tbody></table>
          </td>
          <td width="100%" class="header_content_h2" style="line-height: 1.3em;
        font-family: Arial,sans-serif; font-weight: bold;
        font-style: none; font-size: 14px; color: #919191;
        text-decoration: none; text-transform: uppercase; margin: 0;
        padding: 0px 5px; white-space: nowrap; padding-right: 6px;
        padding-left: 6px;">'.__('Detalles Orden', 'master-tienda').' </td>
          <td width="50%" style="font-family: Arial, sans-serif;
        line-height: 1.3em; font-size: 1px;">
          <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; line-height: 1.3em;
        color: #919191;">
          <tbody><tr height="50%" style="font-family:
        Arial, sans-serif; line-height: 1.3em; height: 50%;">
          <td style="font-family: Arial, sans-serif; line-height: 1.3em;
        font-size: 1px;">&nbsp;</td>
          </tr>
          <tr height="50%" style="font-family: Arial, sans-serif; line-height: 1.3em;
        height: 50%;">
          <td class="header_content_h2_border" style="font-family: Arial, sans-serif; line-height: 1.3em;
        font-size: 1px; border-top: 2px solid #282828;"></td>
         
        </tr>
          </tbody></table>
          </td>
          </tr>
          </tbody></table>
          
          <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, sans-serif; line-height: 1.3em;
        color: #919191;">
          <tbody><tr style="font-family: Arial,
        sans-serif; line-height: 1.3em;">
          <td class="header_content_h2_space_after" style="font-family:
        Arial, sans-serif; line-height: 1.3em; font-size: 1px;
        height: 18px;"></td>
          </tr>
          </tbody></table>
          
          </td>
          </tr>
         
        </tbody></table>
          
          <table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-family: Arial,
        sans-serif; line-height: 1.3em; color: #919191;">
          <tbody><tr style="font-family: Arial, sans-serif; line-height:
        1.3em;">
          <td class="order-table-heading" style="font-family: Arial, sans-serif; line-height: 1.3em;
        padding: 0 0 6px; text-align: left;">
          <span class="highlight" style="color: #2780e7; text-decoration:
        none; font-style: none;">
          '.__('Número Orden', 'master-tienda').' </span>'.$order_id.'</td>
          <td class="order-table-heading" style="font-family: Arial, sans-serif; line-height: 1.3em;
        padding: 0 0 6px; text-align: right;">
          <span class="highlight" style="color: #2780e7; text-decoration:
        none; font-style: none;">
        '.__('Orden Fecha', 'master-tienda').' </span> 
        '.$date_order.'</td>
          </tr>
          </tbody></table>
          
          <div class="order_items_table">
          
          <table cellspacing="0" cellpadding="0" border="0" style="font-family: Arial,
        sans-serif; line-height: 1.3em; margin: 15px 0; overflow:
        hidden; width: 100%; background: #f7f7f7; color: black;
        border-radius: 4px; border-bottom: 1px dotted #c9c9c9;
        border-left: 1px dotted #c9c9c9; border-right: 1px dotted
        #c9c9c9;">
          <thead>
          <tr style="font-family: Arial,
        sans-serif; line-height: 1.3em;">
          <th scope="col" style="vertical-align: middle; word-wrap: break-word;
        padding: 15px 12px; font-family: Arial, sans-serif;
        text-align: left; text-transform: uppercase; font-size:
        10px; font-weight: normal; padding-top: 7px; padding-bottom:
        7px; margin: 0; line-height: .8em; border-top: 1px dotted
        #c9c9c9;">'.__('Producto', 'master-tienda').'</th>
          <th scope="col" style="vertical-align: middle; word-wrap: break-word;
        padding: 15px 12px; font-family: Arial, sans-serif;
        text-align: left; text-transform: uppercase; font-size:
        10px; font-weight: normal; padding-top: 7px; padding-bottom:
        7px; margin: 0; line-height: .8em; border-top: 1px dotted
        #c9c9c9;">'.__('Cantidad', '').'</th>
          <th scope="col" style="vertical-align: middle; word-wrap: break-word;
        padding: 15px 12px; font-family: Arial, sans-serif;
        text-transform: uppercase; font-size: 10px; font-weight:
        normal; padding-top: 7px; padding-bottom: 7px; margin: 0;
        line-height: .8em; border-top: 1px dotted #c9c9c9;
        text-align: right;">'.__('Precio', 'master-tienda').'</th>
          </tr>
          </thead>
         
        <tbody>';
                /**
                 *=========  Info Items product ==========
                 **/
                foreach( $order_items as $item_id => $item ){
                    /*===== Get Item Product Bar ======*/
                    $item_data = $item->get_data();
                    $item_id = $item_data['product_id'];
                    $item_name = $item_data['name'];
                    $image_id = get_post_meta($item_id , '_thumbnail_id', true);
                    $url_image = wp_get_attachment_url($image_id);
                    $item_total = $item_data['total'];
                    $item_quanty = $item_data['quantity'];

        $message .= ' <tr class="order_item" style="font-family:
        Arial, sans-serif; line-height: 1.3em;">
        <td class="order_items_table_td_product" style="line-height:
        1.3em; font-family: Arial, sans-serif; text-align: left;
        word-wrap: break-word; font-size: 14px; padding: 15px 12px;
        border-top: 1px dotted #c9c9c9; vertical-align: top;">
        <table class="order_items_table_product_details_inner" cellpadding="0" cellspacing="0" border="0" width="100%" style="font-family: Arial, sans-serif; line-height: 1.3em;
        font-size: 13px; vertical-align: top; color: black;">
              
                  <tbody><tr style="font-family: Arial, sans-serif;
        line-height: 1.3em;">
                                              
              <td class="order_items_table_product_details_inner_td_image" style="font-family: Arial, sans-serif; line-height: 1.3em;
        padding-right: 15px;">
                                     <img width="70" height="70" src="'.$url_image.'" class="attachment-70x70 size-70x70" alt="" style="border-radius: 3px; padding: 0; margin: 0;">         
                      </td>
                                                
            <td class="order_items_table_product_details_inner_td_text" width="100%" style="font-family: Arial, sans-serif;
        line-height: 1.3em;">
         
                                 <div class="order_items_table_product_details_inner_title" style="font-weight: bold; font-size: 15px; padding-bottom:
        3px;">'.$item_name.'</div>
                        </td>
                    </tr>
                </tbody>
            </table>
         </td>
                
        <td class="order_items_table_td_product" style="line-height:
        1.3em; font-family: Arial, sans-serif; text-align: left;
        word-wrap: break-word; font-size: 14px; padding: 15px 12px;
        border-top: 1px dotted #c9c9c9; vertical-align: top;">'.$item_quanty.'</td>
                 <td class="order_items_table_td_product" style="line-height:
        1.3em; font-family: Arial, sans-serif; word-wrap:
        break-word; font-size: 14px; padding: 15px 12px; border-top:
        1px dotted #c9c9c9; vertical-align: top; text-align:
        right;">
         
		<span class="woocommerce-Price-amount amount">'.wc_price($item_total).'</span>
        
        </td>
		</tr>
		';
                }
                /**
                 *=========  Info Aditional Total ==========
                 **/
              $message .= '
          </tbody>
          <tfoot>
          <tr class="order_items_table_total_row_subtotal" style="font-family: Arial, sans-serif; line-height:
        1.3em;">
          <th scope="row" colspan="2" style="vertical-align: middle; word-wrap: break-word;
        padding: 15px 12px; border-top: 1px dotted #c9c9c9;
        padding-top: 10px; padding-bottom: 10px; font-family: Arial,
        sans-serif; text-align: left; text-transform: uppercase;
        font-size: 14px; line-height: 1em;">'.__('Subtotal', 'master-tienda').'</th>
          <td style="vertical-align: middle; word-wrap: break-word;
        padding: 15px 12px; border-top: 1px dotted #c9c9c9;
        padding-top: 10px; padding-bottom: 10px; font-family: Arial,
        sans-serif; text-transform: uppercase; font-size: 14px;
        line-height: 1em; text-align: right;">
          <span class="woocommerce-Price-amount amount">'.wc_price($subtotal_shipping).'</span>
        </td>
          </tr>
          <tr class="order_items_table_total_row_envio" style="font-family: Arial, sans-serif; line-height:
        1.3em;">
          <th scope="row" colspan="2" style="vertical-align: middle; word-wrap: break-word;
        padding: 15px 12px; border-top: 1px dotted #c9c9c9;
        padding-top: 10px; padding-bottom: 10px; font-family: Arial,
        sans-serif; text-align: left; text-transform: uppercase;
        font-size: 14px; line-height: 1em;">
         '.__('Envio', 'master-tienda').'</th>
          <td style="vertical-align: middle; word-wrap: break-word;
        padding: 15px 12px; border-top: 1px dotted #c9c9c9;
        padding-top: 10px; padding-bottom: 10px; font-family: Arial,
        sans-serif; text-transform: uppercase; font-size: 14px;
        line-height: 1em; text-align: right;">
          <span class="woocommerce-Price-amount amount">
		  '.wc_price($shipping_billing).'
		  </span></td>
          </tr>
          <tr class="order_items_table_total_row_metodo-de-pago" style="font-family: Arial, sans-serif; line-height:
        1.3em;">
          <th scope="row" colspan="2" style="vertical-align: middle; word-wrap: break-word;
        padding: 15px 12px; border-top: 1px dotted #c9c9c9;
        padding-top: 10px; padding-bottom: 10px; font-family: Arial,
        sans-serif; text-align: left; text-transform: uppercase;
        font-size: 14px; line-height: 1em;">
         '.__('Método de pago', 'master-tienda').'
        </th>
          <td style="vertical-align: middle; word-wrap:
        break-word; padding: 15px 12px; border-top: 1px dotted
        #c9c9c9; padding-top: 10px; padding-bottom: 10px;
        font-family: Arial, sans-serif; text-transform: uppercase;
        font-size: 14px; line-height: 1em; text-align: right;">
        '.$payment_method.' 
        </td>
          </tr>
          <tr class="order_items_table_total_row_total" style="font-family: Arial, sans-serif; line-height:
        1.3em;">
          <th scope="row" colspan="2" style="vertical-align: middle; word-wrap: break-word;
        padding: 15px 12px; border-top: 1px dotted #c9c9c9;
        padding-top: 10px; padding-bottom: 10px; font-family: Arial,
        sans-serif; text-align: left; text-transform: uppercase;
        font-size: 14px; line-height: 1em;">
         '.__('Total','master-tienda').' </th>
          <td style="vertical-align: middle; word-wrap: break-word;
        padding: 15px 12px; border-top: 1px dotted #c9c9c9;
        padding-top: 10px; padding-bottom: 10px; font-family: Arial,
        sans-serif; text-transform: uppercase; font-size: 14px;
        line-height: 1em; text-align: right;">
        
          <span class="woocommerce-Price-amount amount">'.wc_price($total_shipping).'</span>
        
        ';
        
        /**
         *======= Details Send and User  ===========
         **/
        
        $message .= '   </td>
                    </tr>
                </tfoot>
          </table>
          </div>
          </td>
        </tr>
        </tbody>
    </table>
      <div class="order_other_table_holder">
         </div>
                      <h2 style="color: $body_text_color;
        font-family: Arial, sans-serif; font-size: 22px; text-align:
        left; font-weight: bold;">'.__('Detalles de envio', 'master-tienda').'</h2>             <p style="margin: .6em 0;"><strong>'.__('Dirección:', 'master-tienda').'</strong> 
		'.$shipping_address.'
		</p>             
        <table id="addresses" cellspacing="0" cellpadding="0" align="center" border="0" style="font-family: Arial, sans-serif; line-height: 1.3em;
        color: #919191; width: 100%; vertical-align: top;">  
        <tbody>
        <tr style="font-family: Arial, sans-serif; line-height: 1.3em;">
         <td width="50%" valign="top" class="addresses-td
        order_items_table_column_pading_first" style="font-family:
        Arial, sans-serif; line-height: 1.3em; padding-left: 0px;
        padding-right: 22.5px;">  
        <p style="margin: .6em 0;
        text-transform: capitalize;">  
        <strong>'.__('Ciudad: ', 'master-tienda').'</strong>
        '.$address_city.'
        </p>  
        <p style="margin: .6em 0;">
        <strong>'.__('Datos envio', 'master-tienda' ).'</strong>
        </p>  <address class="address" style="font-style: normal;">  
       '.$data['billing']['first_name'].'  '.$data['billing']['last_name'].' 
        <br>'.$phone_billing.'
        <br>'.$email_billing.'</address>    
        </td>  
        </tr> 
        </tbody>
        </table> 
            </td>
        </tr>
    </tbody>
</table>

                    </td>
                </tr>
          </tbody>
          </table>';

       /*====== Use Processing state email send =======*/
				$send_email = new sendEmail();
				$send_email->send_email_woocommerce_style($email, $subject, $heading, $message);
			}
        }
    }

    public function view_products_relations_hook() {

        wp_enqueue_style( 'baseplugin-frontend-general-relations' );
        wp_enqueue_script( 'baseplugin-frontend-general-relations' );

        $content = '<div id="vue-frontend-products-relation"></div>';
        echo $content;

    }

    public function woocommerce_template_single_title_hook(){

         wp_enqueue_style( 'baseplugin-frontend-rating-product' );
         wp_enqueue_script( 'baseplugin-frontend-rating-product' );

         $content = '<div id="vue-frontend-rating-product"></div>';
         echo $content;
    }


}

new options_aditional();