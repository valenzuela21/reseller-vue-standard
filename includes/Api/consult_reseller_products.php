<?php
include(dirname(__FILE__) . "/load.php");

class consult_reseller_products
{
	public $id_global;

    public function _consults_products()
    {
    
        $args = array(
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post_type' => 'product'
        );

        $post = get_posts($args);

        $arrayIDproduct = [];

        foreach ($post as $item) {
            $id_product = $item->ID;
            $reseller = $this->_consultProduct($id_product);


            if (is_array($reseller)) {

                if (in_array($this->id_global, $reseller)) {

                    array_push($arrayIDproduct, $id_product);


                }
            }

        }

        $arrayGeneral = [];

        foreach ($arrayIDproduct as $item) {


            $args = array(
                'p' => $item,
                'posts_per_page' => -1,
                'post_type' => 'product',
            );

            $content = get_posts($args);


            foreach ($content as $item) {
                $itemArray['id'] = $item->ID;
                $itemArray['title'] = $item->post_title;
                $itemArray['poststatus'] = $item->post_status;
                $itemArray['guid'] = get_permalink($item->ID);
                array_push($arrayGeneral, $itemArray);
            }

        }


        header("Content-type: application/json");
        echo json_encode($arrayGeneral);
        die();

    }

    public function _consultProduct($id_reseller)
    {

        $reseller_id = get_post_meta($id_reseller, 'meta_reseller_shopping', true);

        return $reseller_id;

    }

}

$data = json_decode(file_get_contents("php://input"), true);
if(!empty($data['id_shop'])){ 
	$app = new consult_reseller_products();
	$app->id_global = $data['id_shop'];
	$app->_consults_products();
}