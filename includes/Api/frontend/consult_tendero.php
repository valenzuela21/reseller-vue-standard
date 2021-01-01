<?php
header('Access-Control-Allow-Origin: *');
include(dirname(__FILE__) . "./../load.php");

class consult_tendero{

    public function _consultJson(){
        $data = json_decode(file_get_contents("php://input"), true);
        $paged = $data['page'];
        $posts_per_page = $data['posts_per_page'];
        $args = array(
            'posts_per_page' => $posts_per_page,
            'paged'=> $paged,
            'post_status'    => 'publish',
            'post_type'   => 'tienda'
        );

        $post = get_posts( $args );

        $arrayGeneral = [];

        foreach ($post as $item) {
            $attachment = get_post($item->ID);
            $arrayItem['id'] =  $item->ID;
            $arrayItem['title'] =  $item->post_title;
            $arrayItem['guid'] = get_permalink($item->ID);
            $image_id = get_post_meta( $item->ID, '_thumbnail_id', true );
            $arrayItem['url_image']  = wp_get_attachment_url( $image_id);
            $arrayItem['category'] = $this->getCategory($item->ID);
            $arrayItem['content'] = $attachment->post_content;
            $arrayItem['number_result'] = $this->numberRow();
            $arrayItem['page'] = $paged;

            array_push($arrayGeneral, $arrayItem);
        }

        header("Content-type: application/json");
        echo json_encode(array_reverse($arrayGeneral)) ;
        die();

    }

    public function numberRow(){
        $args = array(
            'post_status'    => 'publish',
            'post_type'   => 'tienda'
        );

        $query = get_posts( $args );
        return count($query);

    }

    function getCategory($product_id){
        $categoryGeneral = array();
        $category = get_the_terms( $product_id, 'type-tendero' );
        foreach ($category as  $list){
            $category = $list->name;
            array_push($categoryGeneral, $category);
        }
        return  $categoryGeneral;
    }

}
$app = new consult_tendero();
$app->_consultJson();