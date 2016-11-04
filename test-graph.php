<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$client = ClientBuilder::create()
    ->addConnection('default', 'http://neo4j:123456@localhost:7474')
    ->build();

$query = "MATCH (nineties:Movie) WHERE nineties.released > 1990 AND nineties.released < 2000 RETURN nineties.title";
$result = $client->run($query);
//var_dump($result);

foreach ($result->getRecords() as $record) {
    echo sprintf('Movie : %s <br>', $record->value('nineties.title'));
}