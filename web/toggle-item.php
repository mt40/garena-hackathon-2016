<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../class.php";
require_once '../vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

$client = ClientBuilder::create()
    ->addConnection('default', 'http://neo4j:123456@localhost:7474')
    ->build();


$query = "MATCH (c:Champion) RETURN c.champion_id,c.champion_name, c.ability_icon_1, c.tag LIMIT 10";



$response = array();
$response["action"] = "ordered";
print_r(json_encode($response));