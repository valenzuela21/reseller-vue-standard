<?php
header('Access-Control-Allow-Origin: *');
include(dirname(__FILE__) . "./../load.php");

class consult_products_relations{


    public function consult_relation(){
        $data = json_decode(file_get_contents("php://input"), true);
        $id_product = $data['id_product'];
        $terms = get_the_terms($id_product, 'product_cat' );

        foreach ($terms as $term) {
            $product_cat_id = $term->term_id;
            break;
        }

        $args = [
            'post_type' => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'exclude' => [$id_product],
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'terms' => $product_cat_id,
                    'include_children' => false // Remove if you need posts from term 7 child terms
                ],
            ],
            // Rest of your arguments
        ];


        $related_posts = get_posts($args);

        $IdProducts = [];

        foreach ($related_posts as $related_post) {
            $id=$related_post->ID;
            array_push($IdProducts,$id);
        }

        $args = array(
            'include'   => $IdProducts,
            'post_type' => 'product',
            'orderby' => 'rand',
            'posts_per_page' => 4,
        );

        $posts = get_posts($args);

        $arrayGeneral = [];
        $i = 0;
        foreach ($posts as $key => $item) {
            if($i <= 3) {
                $price = get_post_meta($item->ID, '_regular_price', true);
                $sale = get_post_meta($item->ID, '_sale_price', true);
                $product_type = get_the_terms($item->ID, 'product_type');
                $image_id = get_post_meta($item->ID, '_thumbnail_id', true);

                $product = new WC_Product_Variable($item->ID);

                $arrayItem['id'] = $item->ID;
                $arrayItem['title'] = $item->post_title;
                $arrayItem['category'] = $this->getCategory($item->ID);
                $arrayItem['url_image'] = wp_get_attachment_url($image_id);
                $arrayItem['guid'] = get_permalink($item->ID);
                $arrayItem['product_type'] = $product_type[0]->slug;
                $arrayItem['product_type_price'] = $product->get_price_html();
                $arrayItem['pice'] = $price;
                $arrayItem['sale'] = $sale;
                $arrayItem['qurency'] = ['price' => wc_price($price), 'sale' => wc_price($sale)];
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
            $i++;

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

$relation = new consult_products_relations();
$relation -> consult_relation();


