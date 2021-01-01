<?php
include(dirname(__FILE__) . "/load.php");

class consult_shopping_success
{

	public $id;
	
    public function consultShopping()
    {
     
        $id_post = get_post_meta($this->id, 'meta_reseller_shopping', true);

        $args = array('post_type' => 'tienda', 'posts_per_page' => -1);

        $query = get_posts($args);
        $arrayGeneral = [];
        foreach ($query as $item) {
            $id_general = $item->ID;

            if (in_array($id_general, $id_post)) {
                $arrayItem['id'] = $item->ID;
                $arrayItem['title'] = $item->post_title;

                array_push($arrayGeneral, $arrayItem);

            }

        }

        header("Content-type: application/json");
        echo json_encode(array_reverse($arrayGeneral));
        die();

    }


}

$data = json_decode(file_get_contents("php://input"), true);

if(!empty($data['id'])){
	$app = new consult_shopping_success();
	$app->id = $data['id'];
	$app->consultShopping();
}

