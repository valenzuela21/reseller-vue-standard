<?php
include(dirname(__FILE__)."/load.php");

class consult_select_tendero{

    public function select_results(){

        $args = array(
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'post_type'   => 'tienda'
        );

        $post = get_posts( $args );

        $arrayGeneral = [];

        foreach ($post as $item) {
            $arrayItem['id'] =  $item->ID;
            $arrayItem['title'] =  $item->post_title;
            array_push($arrayGeneral, $arrayItem);
        }

        header("Content-type: application/json");
        echo json_encode(array_reverse($arrayGeneral)) ;
        die();

    }

}
$app=new consult_select_tendero();
$app->select_results();