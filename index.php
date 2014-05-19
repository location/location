<?php

require_once("location.php");

$location = new Location();
$location->link($_GET['name'],$_GET['glat'],$_GET['glon'],$_SERVER['HTTP_REFERER']);
echo $location->data;

?>
