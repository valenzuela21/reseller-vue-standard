<?php
header('Access-Control-Allow-Origin: *');
include(dirname(__FILE__) . "./../load.php");
class consult_category_product_select{

    public function _select_category(){
        $data = json_decode(file_get_contents("php://input"), true);
        
        $args = array(
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'post_type'   => 'product',
        );

        $post = get_posts($args);

        //Important ID Reseller or Tendero
        $id_product = $data['id_shop'];
        

        $selectGeneral = [];

        foreach($post as $item){

             $products = get_post_meta($item->ID, 'meta_reseller_shopping', true);
            

             if(is_array($products)){

                if(in_array($id_product, $products)){
                        $arrayItem['category']=$this->getCategory($item->ID);
                        array_push( $selectGeneral, $arrayItem );
                }
                
             }
        }

        $finalyArray = [];
        foreach($selectGeneral  as $category){
            foreach($category as $subCategory){
                foreach($subCategory as $item){
                     $arraItem['title'] = $item['title'];
                     $arraItem['slug'] = $item['slug'];
                     array_push($finalyArray , $arraItem);
                }
            } 
        }


       header("Content-type: application/json");
       echo json_encode( $finalyArray ) ;
       die();
        
    }

    public function getCategory($product_id){
        $categoryGeneral = array();
        $category = get_the_terms( $product_id, 'product_cat' );
        foreach ($category as $list) {
            $arrayItem['title'] = $list->name;
            $arrayItem['url'] = get_category_link($list->term_id);
            $arrayItem['slug'] = $list->slug;
            array_push($categoryGeneral, $arrayItem);
        }
        return  $categoryGeneral;
    }
}
$app = new consult_category_product_select();
$app->_select_category();