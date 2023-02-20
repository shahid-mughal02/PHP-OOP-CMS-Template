<?php

session_start();

$root = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];
$root = str_replace("index.php", "", $root);

define("ROOT", $root);
define("ASSETS", $root . "public/assets/");

include "app/init.php";

$app = new App();