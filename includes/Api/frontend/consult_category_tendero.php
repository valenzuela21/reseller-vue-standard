<?php
header('Access-Control-Allow-Origin: *');
include(dirname(__FILE__) . "./../load.php");
 
class consult_category_tendero{
    
    public $taxonomy;
    public $term;
    public $paged;
    public $per_paged;

    public function _consultCategory(){
  
        $args = array(
            'posts_per_page' => $this->per_paged,
            'paged'=>  $this->paged,
            'post_type' => 'tienda',
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


        $arrayCategory=[];

        foreach ($consult as $item){
            $arrayItem['id'] = $item->ID;
            $arrayItem['title'] = $item->post_title;
            $arrayItem['category'] = $this->getCategory($item->ID);
            $image_id = get_post_meta( $item->ID, '_thumbnail_id', true );
            $arrayItem['url_image'] =  wp_get_attachment_url($image_id);
            $arrayItem['guid'] = get_permalink($item->ID);

            array_push($arrayCategory, $arrayItem);
        }
        $arrayInfo['number_result'] = $this->numberRow();
        $result = [$arrayCategory, $arrayInfo];

        header("Content-type: application/json");
        echo json_encode($result) ;
        die();

    }
    
    public function numberRow(){

       $args = array(
            'posts_per_page' => -1,
            'post_type' => 'tienda',
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
$data = json_decode(file_get_contents("php://input"), true);
$app=new consult_category_tendero();
$app->taxonomy = 'type-tendero';
$app->term = $data['term'];
$app->paged = $data['paged'];
$app->per_paged = data['per_paged'];
$app->_consultCategory();