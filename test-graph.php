<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'vendor/autoload.php';

use GraphAware\Neo4j\Client\ClientBuilder;

class Node
{
    var $id;
    var $group;
    var $name;

    public function __construct($id, $group, $name)
    {
        $this->id = $id;
        $this->group = $group;
        $this->name = $name;
    }
}

class Link
{
    var $source;
    var $target;
    var $value;
    public function __construct($source, $target, $value)
    {
        $this->source = $source;
        $this->target = $target;
        $this->value = $value;
    }
}

$json_array = array(
    "nodes" => array(),
    "links" => array()
);

$group_array = array(
    "tank" => 1,
    "marksman" => 2,
    "assassin" => 3,
    "mage" => 4,
    "fighter" => 5,
    "support" => 6
);

$client = ClientBuilder::create()
    ->addConnection('default', 'http://neo4j:123456@localhost:7474')
    ->build();

$query = "MATCH (c:Champion) RETURN c.champion_name, c.tag";
$result = $client->run($query);
foreach ($result->getRecords() as $record) {
    $json_array["nodes"][] = new Node($record->value('c.champion_name'), $group_array[$record->value('c.tag')], $record->value('c.champion_name'));
}



$query = "MATCH p=(c1: Champion)-[r:SAME_MATCH]-(c2: Champion)
WHERE r.strength > 1000000
RETURN c1.champion_name, c2.champion_name, r.strength;";
$result = $client->run($query);
foreach ($result->getRecords() as $record) {
    $json_array["links"][] = new Link($record->value('c1.champion_name'), $record->value('c2.champion_name'), $record->value('r.strength')/1000000);
}
print json_encode($json_array);



