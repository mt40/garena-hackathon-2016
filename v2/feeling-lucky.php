<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("utilities.php");
$utilities = new Utilities();
/** @var User $user */
$user = $utilities->getUser($_COOKIE["username"]);
$utilities->removeTodayFoodOrder($user->username);
$utilities->insertFoodOrder($user->username, -1);