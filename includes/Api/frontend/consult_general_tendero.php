<?php
header('Access-Control-Allow-Origin: *');
include(dirname(__FILE__) . "./../load.php");

class consult_general_tendero{

    public function general_consult(){
        $args = array(
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'post_type'   => 'tienda',
            'sort_order' => 'asc',
        );
        $consult = get_posts($args);

        $arrayGeneral = [];

        foreach ($consult as $item){
            $arraItem['id'] = $item->ID;
            $arraItem['title'] = $item->post_title;
            $arraItem['category'] = $this->getCategory($item->ID);
            $arraItem['guid'] = get_permalink($item->ID);
            $image_id = get_post_meta( $item->ID, '_thumbnail_id', true );
            $arraItem['urlimage']=   wp_get_attachment_url($image_id);

            array_push($arrayGeneral, $arraItem);

        }

        header("Content-type: application/json");
        echo json_encode($arrayGeneral);
        die();

    }
    public function getCategory($product_id)
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



}
$app = new consult_general_tendero();
$app->general_consult();
