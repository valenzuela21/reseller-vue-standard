<?php
include(dirname(__FILE__) . "./../load.php");

class search_product
{
    public $paged;
    public $search;
    public $post_por_page;

    public function consultSearch()
    {

        $search = $this->search;
        $paged = $this->paged - 1;
        $posts_per_page = $this->post_por_page;

        $args = array(
            's' => $search,
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post_type' => array('product', 'product_variation'),
        );

        $post = get_posts($args);

        $arrayGeneral = [];

        foreach ($post as $item) {

            $id = $item->ID;

            $products = get_post_meta($id, 'meta_reseller_shopping', true);

            $price = get_post_meta($id, '_regular_price', true);
            $sale = get_post_meta($id, '_sale_price', true);

            if (is_array($products)) {
                $reseller = $this->getResellerGeneral($id);
                $product_type = get_the_terms($id, 'product_type');
                $product = new WC_Product_Variable($id);
                $arrayItem['product_type'] = $product_type[0]->slug;
                $arrayItem['product_type_price'] =$product->get_price_html();
                $arrayItem['id'] = $item->ID;
                $arrayItem['title'] = $item->post_title;
                $arrayItem['guid'] = get_permalink($item->ID);
                $image_id = get_post_meta($id, '_thumbnail_id', true);
                $arrayItem['image'] = wp_get_attachment_url($image_id);
                $arrayItem['category'] = $this->getCategory($id);
                $arrayItem['reseller_id'] = $reseller["ID"];
                $arrayItem['guidreseller'] = $reseller["guid_reseller"];
                $arrayItem['reseller'] = $reseller["post_title"];
                $image_reselller_id = get_post_meta($reseller["ID"], '_thumbnail_id', true);
                $arrayItem['reseller_image'] = wp_get_attachment_url($image_reselller_id);
                $arrayItem['pice'] = $price;
                $arrayItem['sale'] = $sale;
                $arrayItem['qurency'] = ['price' => wc_price($price), 'sale' => wc_price($sale)];
                $arrayItem['category_reseller'] = $this->getCategoryReseller($reseller["ID"]);
                $arrayItem['ratingproduct'] = get_post_meta($item->ID, 'input_options_rating_product', true);
                array_push($arrayGeneral, $arrayItem);
            }

        }


        $arrayInfo["quanty"] = count($arrayGeneral);
        $arrayInfo["posts_per_page"] = $posts_per_page;
        $arrayInfo["paged"] = $paged;

        //How View Number a Count
        $result = array_chunk($arrayGeneral, $posts_per_page);
        $result = array_reverse($result[$paged]);

        $result = [$result, $arrayInfo];

        header("Content-type: application/json");
        echo json_encode($result);
        die();

    }

    public function getResellerGeneral($product_id)
    {
        $reseller_id = get_post_meta($product_id, 'meta_reseller_shopping', true);
        $args = array(
            'post_status' => 'publish',
            'post_type' => 'tienda',
            'p' => $reseller_id[0],
        );

        $shopReseller = get_posts($args);

        $arrayItem = [];

        foreach ($shopReseller as $item) {
            $arrayItem['ID'] = $item->ID;
            $arrayItem['post_title'] = $item->post_title;
            $arrayItem['guid_reseller']= get_the_permalink($item->ID);
        }

        return $arrayItem;
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

    public function getCategory($product_id)
    {
        $categoryGeneral = array();
        $category = get_the_terms($product_id, 'product_cat');
        foreach ($category as $list) {
            $arrayItem['title'] = $list->name;
            $arrayItem['url'] = get_category_link($list->term_id);
            array_push($categoryGeneral, $arrayItem);
        }
        return $categoryGeneral;
    }

}

$search = new search_product();
$data = json_decode(file_get_contents("php://input"), true);
$search->search = $data['search'];
$search->paged = $data['paged'];
$search->post_por_page = $data['post_por_page'];
$search->consultSearch();


