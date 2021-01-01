<?php
header('Access-Control-Allow-Origin: *');
include(dirname(__FILE__) . "./../load.php");
class ConsultVarProduct{
    public $idproduct;
    public function __consultImage(){
         $id_product_category = $this->idproduct;
         // https://woocommerce.github.io/code-reference/classes/WC-Product-Variation.html
         $product_variation = new WC_Product_Variation(  $id_product_category );
         $image = $product_variation->get_image('woocommerce_large');


         header("Content-type: application/json");
         echo json_encode($image) ;
         die();

    }

}

$data = json_decode(file_get_contents("php://input"), true);
$app = new ConsultVarProduct();
$app->idproduct = $data['id'];
$app->__consultImage();
