<?php

$url = $_GET["__url"];
$proto = $_GET["__proto"];

$_GET["__url"] = null;
$_GET["__proto"] = null;

$url = $proto . "://" . $url . "?" . http_build_query($_GET);

header('Access-Control-Allow-Origin: *');
echo file_get_contents($url);

?>