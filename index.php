<?php

require_once("location.php");

$location = new Location();
if ($_SERVER['HTTP_REFERER']=="http://location.gl/name/" . $_GET['name']) {
  $location->link($_GET['name'],$_GET['glat'],$_GET['glon'],$_GET['link']);
} else {
  $location->link($_GET['name'],$_GET['glat'],$_GET['glon'],$_SERVER['HTTP_REFERER']);
}

echo $location->data;

?>
