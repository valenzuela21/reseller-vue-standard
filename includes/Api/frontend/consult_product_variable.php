<?php
header('Access-Control-Allow-Origin: *');
include(dirname(__FILE__) . "./../load.php");
class consult_product_variable{

    public function _consult_variable(){
        global $product;
        $data = json_decode(file_get_contents("php://input"), true);
        $id_product = $data['id_shop'];
        $args = [
            'p'=>$id_product,
            'post_status' => 'publish',
            'post_type' => 'product',
        ];

        $consult = get_posts($args);

        $arrayGeneral = [];

        foreach ($consult as $key => $item) {
            $product_type = get_the_terms( $item->ID, 'product_type');
            $arrayProduct['product_type'] = $product_type[0]->slug;

            $product= new WC_Product_Variable($id_product);
            $variables = $product->get_children();


            foreach ( $variables as $variation ) {

                // get variation ID
                $variation_ID = $variation;

                // get variations meta
                // https://woocommerce.github.io/code-reference/classes/WC-Product-Variation.html
                $product_variation = new WC_Product_Variation( $variation_ID );
                $arrayProduct['variation_id'] = $product_variation->get_id();
                $arrayProduct['variation_is_active'] = $product_variation->variation_is_active();
                $arrayProduct['variation_is_visible'] = $product_variation->variation_is_visible();
                $arrayProduct['productvariations'] = $product_variation->get_attributes();
                $arrayProduct['price_html'] = $product_variation->get_price_html();
                $arrayProduct['sale_price'] = $product_variation->get_sale_price();
                $arrayProduct['regular_price'] = $product_variation->get_regular_price();

                array_push($arrayGeneral,$arrayProduct);

            }

            header("Content-type: application/json");
            echo json_encode($arrayGeneral);
            die();

        }


    }

}
$product_variable = new consult_product_variable();
$product_variable->_consult_variable();