<?php
include(dirname(__FILE__) . "/load.php");
class delete_item_reseller{
	public $id;
	public $id_post;
	
    public function deleteItem(){

        $consult = get_post_meta($this->id, 'meta_reseller_shopping', true);
        $key = array_keys($consult, $this->id_post);

        unset($consult[$key[0]]);

        $newConsult = array_values($consult);

        update_post_meta($this->id, 'meta_reseller_shopping', $newConsult);

    }


}

$data = json_decode(file_get_contents("php://input"), true);
if(!empty($data['id'])&&!empty($data['id_post'])){
	$app = new delete_item_reseller();
	$app->id = $data['id'];
	$app->id_post = $data['id_post'];
	$app->deleteItem();
}

