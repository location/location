<?php
require_once("../location.php");

$location = new Location();
if ($_POST['name']!=NULL && $_POST['glat']!=NULL && $_POST['glon']!=NULL && $_POST['link']!=NULL) {
  $location->vote($_POST['name'],$_POST['glat'],$_POST['glon'],$_POST['link']);
  $location->info .= "<p><span style='background: #ffcc00'>";
  $location->info .= "Registered vote for <b>" . $_POST['name'] . "</b> (<a href='" . $_POST['link'] . "'>" . $_POST['link'] . "</a></b>) from (<b>" . $_POST['glat'] . "</b>,<b>" . $_POST['glon'] . "</b>)</span></p>\n"; 
}
$location->link($_POST['name'],$_POST['glat'],$_POST['glon'],$_POST['link']);
$location->push($_POST['name']);

?>
