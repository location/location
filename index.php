<?php

require_once("location.php");

$location = new Location();

if ($_POST['name']!=NULL && $_POST['glat']!=NULL && $_POST['glon']!=NULL && $_POST['link']!=NULL) {
  $location->vote($_POST['name'],$_POST['glat'],$_POST['glon'],$_POST['link'],$_POST['grad']);
  $location->info .= "<p><span style='background: #ffcc00'>";
  $location->info .= "Registered 1 vote for <b>" . $_POST['name'] . "</b> (<a href='" . $_POST['link'] . "'>" . $_POST['link'] . "</a></b>) from (<b>" . $_POST['glat'] . "<b>,<b>" . $_POST['glon'] . "</b>)(" . $_POST['grad'] . ")</b></span></p>"; 
}

if ($_SERVER['HTTP_REFERER']=="http://location.gl/Home/" . $_GET['name']) {
  $location->link($_GET['name'],$_GET['glat'],$_GET['glon'],$_GET['link'],$_GET['grad']);
} else {
  $location->link($_GET['name'],$_GET['glat'],$_GET['glon'],$_SERVER['HTTP_REFERER'],$_GET['grad']);
}
// $location->AverageDistance($_GET['name']);
$location->push($_GET['name']);

?>
