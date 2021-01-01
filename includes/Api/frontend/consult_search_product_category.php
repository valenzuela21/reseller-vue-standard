<?php
header('Access-Control-Allow-Origin: *');
include(dirname(__FILE__) . "./../load.php");

class consult_productos_reseller{

    public function consult_product(){

        $data = json_decode(file_get_contents("php://input"), true);
        $term = $data['term'];
        $id_product = $data['id_shop'];
        $paged = $data['paged']-1;
        $posts_per_page = $data['post_por_page'];
        
            $args = array(
                'post_status'    => 'publish',
                'post_type'   => 'tienda',
                 'p'         => $id_product, // ID Shop Reseller
            );
  
        $shopReseller = get_posts($args);

            $args = array(
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'post_type'   => 'product',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat', // Nombre de la taxonomía creada
                        'field' => 'slug', // Como pasaremos el parámetro, en este caso como el slug
                        'terms' => $term, // Slug de la taxonomía
                     )
                )
            );


        $post = get_posts($args);

        $arrayGeneral = [];

        foreach ($post as $item) {

            $id = $item->ID;

            $products = get_post_meta($id, 'meta_reseller_shopping', true);

            $price = get_post_meta( $id, '_regular_price', true);
            $sale = get_post_meta( $id, '_sale_price', true);


            if(is_array($products)){

                if(in_array($id_product, $products)){
                    $product = new WC_Product_Variable($item->ID);
                    $product_type = get_the_terms($item->ID, 'product_type');
                    $arrayItem['id'] = $item->ID;
                    $arrayItem['title'] = $item->post_title;
                    $arrayItem['guid'] = get_permalink($item->ID);
                    $image_id = get_post_meta( $id, '_thumbnail_id', true );
                    $arrayItem['image'] = wp_get_attachment_url($image_id);
                    $arrayItem['category'] = $this->getCategory($id);
                    $arrayItem['product_type'] = $product_type[0]->slug;
                    $arrayItem['product_type_price'] =$product->get_price_html();
                    $arrayItem['reseller_id'] = $shopReseller[0]->ID;
                    $arrayItem['reseller'] = $shopReseller[0]->post_title;
                    $image_reselller_id = get_post_meta( $shopReseller[0]->ID, '_thumbnail_id', true );
                    $arrayItem['reseller_image'] =  wp_get_attachment_url($image_reselller_id);
                    $arrayItem['pice'] = $price;
                    $arrayItem['sale'] = $sale;
                    $arrayItem['qurency'] = [ 'price' =>  wc_price($price), 'sale' => wc_price($sale)]; 
                    $arrayItem['category_reseller'] = $this->getCategoryReseller($shopReseller[0]->ID);
                    array_push( $arrayGeneral , $arrayItem);

                }

            }

        }

        $arrayInfo["quanty"] = count($arrayGeneral);
        $arrayInfo["posts_per_page"] = $posts_per_page;
        $arrayInfo["paged"] = $paged;

        //How View Number a Count
        $result = array_chunk($arrayGeneral,$posts_per_page);
        $result = array_reverse($result[$paged]);

        $result = [$result, $arrayInfo];


        header("Content-type: application/json");
        echo json_encode($result) ;
        die();

    }

    public function getCategoryReseller($product_id)
    {
        $categoryGeneral = array();
        $category = get_the_terms($product_id, 'type-tendero');
        foreach ($category as $list) {
            $arrayItem['title'] = $list->name;
            $arrayItem['url'] = get_category_link($list->term_id);
            array_push($categoryGeneral, $arrayItem);
        }
        return $categoryGeneral;
    }

    public function getCategory($product_id){
        $categoryGeneral = array();
        $category = get_the_terms( $product_id, 'product_cat' );
        foreach ($category as $list) {
            $arrayItem['title'] = $list->name;
            $arrayItem['url'] = get_category_link($list->term_id);
            array_push($categoryGeneral, $arrayItem);
        }
        return  $categoryGeneral;
    }


}
$app = new consult_productos_reseller();
$app->consult_product();