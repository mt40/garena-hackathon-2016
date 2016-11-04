<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("utilities.php");

$utilities = new Utilities();
$emailContents = $utilities->generateEmailContents();
var_dump($emailContents);