<?php

require_once("location.php");

$location = new Location();

if ($_POST['name']!=NULL && $_POST['glat']!=NULL && $_POST['glon']!=NULL && $_POST['link']!=NULL) {
  $location->vote($_POST['name'],$_POST['glat'],$_POST['glon'],$_POST['link'],$_POST['dist']);
  $location->data .= "<p><span style='background: #ffcc00'>";
  $location->data .= "Registered vote for <b>" . $_POST['name'] . "</b> (<a href='" . $_POST['link'] . "'>" . $_POST['link'] . "</a></b>) from (<b>" . $_POST['glat'] . "<b>,<b>" . $_POST['glon'] . "</b>)(" . $_POST['dist'] . ")</span></p>"; 
}

if ($_SERVER['HTTP_REFERER']=="http://location.gl/name/" . $_GET['name']) {
  $location->link($_GET['name'],$_GET['glat'],$_GET['glon'],$_GET['link'],$_GET['dist']);
} else {
  $location->link($_GET['name'],$_GET['glat'],$_GET['glon'],$_SERVER['HTTP_REFERER'],$_GET['dist']);
}

$location->push($_GET['name']);

?>
