<?php
require_once "page1.php"; 
require_once "items.php"; 

// Set response header
header("Content-Type: application/json");

if (!isset($conn)) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

$item = new Item($conn);


$item_id = isset($_GET['item_id']) ? $_GET['item_id'] : null;
$name = isset($_GET['name'])? $_GET['name'] :null;
$frm_amount = isset($_GET['frm_amount'])? $_GET['frm_amount'] :null;
$to_endamount = isset($_GET['to_endamount'])? $_GET['to_endamount'] :null;


if (empty($item_id)) {
    echo json_encode(["success" => false, "message" => "Missing 'item_id' parameter"]);
    exit;
}


$data = $item->getItemsByIdRange($item_id,$name,$frm_amount,$to_endamount);


if (!empty($data)) {
    echo json_encode(["success" => true, "data" => $data]);
} else {
    echo json_encode(["success" => false, "message" => "No items found"]);
}

// Close connection
$conn = null;
?>
