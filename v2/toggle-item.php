<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("utilities.php");
$response = array();
$utilities = new Utilities();
$old_food_id = $utilities->userHasOrderedFoodToday($_POST["username"]);
if ($old_food_id) {
    if ($old_food_id == $_POST["food_id"]) {
        $utilities->removeTodayFoodOrder($_POST["username"], $_POST["food_id"]);
        $response["status"] = "Removed";
        $response["action"] = "Removed food.";
    } else {
        $response["status"] = "Failed";
        $response["action"] = "Failed to place order. You have an existing order.";
    }
} else {
    $utilities->insertFoodOrder($_POST["username"], $_POST["food_id"]);
    $response["status"] = "Success";
    $response["action"] = "Placed order.";
}

$response["food_id"] = $_POST["food_id"];
$foodOrders = $utilities->getFoodDetails();
$response["new_number"] = $foodOrders[$_POST["food_id"]]->popularity;


print_r(json_encode($response));