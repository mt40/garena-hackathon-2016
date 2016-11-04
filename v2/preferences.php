<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("utilities.php");
$utilities = new Utilities();
/** @var User $user */
$user = $utilities->getUser($_COOKIE["username"]);
$preferenceContainer = array();

if (isset($_POST["user_data"])) {
    if (isset($_POST["user_data"]["individuality"])) {
        foreach ($_POST["user_data"]["individuality"] as $avoid) {
            $preference = new UserPreference($user->username, "AVOID", strtolower($avoid));
            $preferenceContainer[] = $preference;
        }
        foreach ($_POST["user_data"]["notification"] as $avoid) {
            $preference = new UserPreference($user->username, "NOTIFICATION", strtolower($avoid));
            $preferenceContainer[] = $preference;
        }
    }
    $utilities->replaceUserSettings($user->username, $preferenceContainer, array("AVOID", "NOTIFICATION"));
}
var_dump($preferenceContainer);
print_r(json_encode(5));