<?php
header('Access-Control-Allow-Origin: *');
include(dirname(__FILE__) . "./../load.php");

$data = json_decode(file_get_contents("php://input"), true);
$id_shop = $data["id_shop"];
$data=get_post_meta($id_shop, 'input_options_rating_product', true);

header("Content-type: application/json");
echo json_encode($data) ;
die();