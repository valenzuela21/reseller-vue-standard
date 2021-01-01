<?php
include(dirname(__FILE__) . "/load.php");

class insert_new_reseller
{

	public $id;
	public $id_reseller;
	
    public function insertReseller()
    {
       
        $consult = get_post_meta($this->id, 'meta_reseller_shopping', true);

        if (!empty($consult) && !$consult == NULL && !$consult == "") {
            if (is_array($consult)) {
                if (!in_array($this->id_reseller, $consult)) {
                    $arrayData = array_merge((array)$this->id_reseller, $consult);
                } else {
                    $arrayData = $consult;
                }

                update_post_meta($this->id, 'meta_reseller_shopping', $arrayData);
            }

        } else {
            update_post_meta($this->id, 'meta_reseller_shopping', (array)$this->id_reseller);
        }


    }

}
 
$data = json_decode(file_get_contents("php://input"), true);
if(!empty($data['id_reseller']) && !empty($data['id'])){
	$app = new insert_new_reseller();
	$app->id = $data['id'];
	$app->id_reseller = $data['id_reseller'];
	$app->insertReseller();
}

