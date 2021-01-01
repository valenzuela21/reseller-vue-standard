<?php
header('Access-Control-Allow-Origin: *');
include(dirname(__FILE__) . "./../load.php");

class consult_category_product
{

    public $taxonomy;
    public $term;
    public $paged;
    public $per_paged;

    public function _consultCategory()
    {
   
        $args = array(
            'posts_per_page' => $this->per_paged,
            'paged'=>  $this->paged,
            'post_type' => 'product',
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => $this->taxonomy, // Nombre de la taxonomía creada
                    'field' => 'slug', // Como pasaremos el parámetro, en este caso como el slug
                    'terms' => $this->term, // Slug de la taxonomía
                )
            )
        );

        $consult = get_posts($args);


        $arrayGeneral = [];

        foreach ($consult as $key => $item) {

            $price = get_post_meta( $item->ID, '_regular_price', true);
            $sale = get_post_meta( $item->ID, '_sale_price', true);
            $product_type = get_the_terms( $item->ID, 'product_type');
            $image_id = get_post_meta($item->ID, '_thumbnail_id', true);
            $product = new WC_Product_Variable($item->ID);
            $arrayItem['id'] = $item->ID;
            $arrayItem['title'] = $item->post_title;
            $arrayItem['category'] = $this->getCategory($item->ID);
            $arrayItem['url_image'] = wp_get_attachment_url($image_id);
            $arrayItem['guid'] = get_permalink($item->ID);
            $arrayItem['product_type'] = $product_type[0]->slug;
            $arrayItem['product_type_price'] =$product->get_price_html();
            $arrayItem['pice'] = $price;
            $arrayItem['sale'] = $sale;
            $arrayItem['qurency'] = [ 'price' =>  wc_price($price), 'sale' => wc_price($sale)]; 
            $arrayItem['number_result'] = $this->numberRow();
            $arrayItem['ratingproduct'] = get_post_meta($item->ID, 'input_options_rating_product', true);
            $reseller_id = get_post_meta($item->ID, 'meta_reseller_shopping', true);

            if (is_array($reseller_id) && !empty($reseller_id)) {
                $arrayItem['reseller'] = $this->resellerConsult($reseller_id[0]);
            } else {
                $arrayItem['reseller'] = [
                    [
                        'title' => '',
                        'category' => [
                            ['title' => '', 'url' => '']
                        ]
                    ]
                ];
            }


            array_push($arrayGeneral, $arrayItem);
        }

        header("Content-type: application/json");
        echo json_encode($arrayGeneral);
        die();

    }


    public function resellerConsult($id)
    {
        $args = array(
            'p' => $id,
            'post_status' => 'publish',
            'post_type' => 'tienda',
        );

        $consult = get_posts($args);

        foreach ($consult as $item) {
            $image_id = get_post_meta($item->ID, '_thumbnail_id', true);
            $arrayReseller[0]= [
                'id' => $item->ID,
                'title' => $item->post_title,
                'guid' => get_permalink($item->ID),
                'category' => $this->getCategoryReseller($item->ID),
                'url_image' => wp_get_attachment_url($image_id)
            ];
        }

        return $arrayReseller;
    }


     public function numberRow(){

       $args = array(
            'posts_per_page' => -1,
            'post_type' => 'product',
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => $this->taxonomy, // Nombre de la taxonomía creada
                    'field' => 'slug', // Como pasaremos el parámetro, en este caso como el slug
                    'terms' => $this->term, // Slug de la taxonomía
                )
            )
        );

        $query = get_posts( $args );
        return count($query);

    }



    public function getCategory($product_id)
    {
        $categoryGeneral = [];
        $category = get_the_terms($product_id, 'product_cat');
        foreach ($category as $list) {
            $arrayItem['title'] = $list->name;
            $arrayItem['url'] = get_category_link($list->term_id);
            array_push($categoryGeneral, $arrayItem);
        }
        return $categoryGeneral;
    }

    public function getCategoryReseller($product_id)
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


}
$data = json_decode(file_get_contents("php://input"), true);
$app = new consult_category_product();
$app->taxonomy = $data['taxonomy'];
$app->term = $data['term'];
$app->paged = $data['paged'];
$app->per_paged = $data['per_paged'];
$app->_consultCategory();