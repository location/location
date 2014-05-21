<?php
require_once("../location.php");

$location = new Location();
$location->vote($_POST['name'],$_POST['glat'],$_POST['glon'],$_POST['link']);
$location->info = "<h3>Info</h3>\n<p style='background: #ffaa00'>Registered vote for " . $_POST['name'] . " (" . $_POST['link'] . ") from (" . $_POST['glat'] . "," . $_POST['glon'] . ")</p>"; 
$location->link($_POST['name'],$_POST['glat'],$_POST['glon'],$_POST['link']);
// echo $location->dist($_POST['name'],$_POST['glat'],$_POST['glon'],0.5);
echo $location->data;

?>
