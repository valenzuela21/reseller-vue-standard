<?php
include(dirname(__FILE__) . "/load.php");

class  delete_item_product_reseller
{
	public $id_reseller;
	public $id_post;

    public function delete_item()
    {
       
        $consult = get_post_meta($this->id_post, 'meta_reseller_shopping', true);
        $key = array_keys($consult, $this->id_reseller);

        unset($consult[$key[0]]);

        $newConsult = array_values($consult);

        update_post_meta($this->id_post, 'meta_reseller_shopping', $newConsult);


    }

}

$data = json_decode(file_get_contents("php://input"), true);

if(!empty($data['id_reseller']) && !empty($data['id_post'])){
	$app = new delete_item_product_reseller();
	$app->id_reseller = $data['id_reseller'];
	$app->id_post = $data['id_post'];
	$app->delete_item();
}

