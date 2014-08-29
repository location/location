<?php
require_once("../location.php");
$location = new Location();
if ($_POST['name']!=NULL && $_POST['glat']!=NULL && $_POST['glon']!=NULL && $_POST['link']!=NULL) {
  $location->vote($_POST['name'],$_POST['glat'],$_POST['glon'],$_POST['link']);
  header("Location: http://location.gl/" . $_POST['name'] . ".list?glat=" . $_POST['glat'] . "&glon=" . $_POST['glon'] . "&grad=" . $_POST['grad']);
  /* $location->info .= "<p><span style='background: #ffcc00'>";  */
  /* $location->info .= "Registered vote for <b>" . $_POST['name'] . "</b> (<a href='" . $_POST['link'] . "'>" . $_POST['link'] . "</a></b>) from (<b>" . $_POST['glat'] . "</b>,<b>" . $_POST['glon'] . "</b>)</span></p>\n";   */
  /* $location->info .= "<p><span style='background: #ffccff'>";  */
  /* $location->info .= $location->news($_POST['glat'], $_POST['glon'], 1000);  */
  /* $location->info .= "</span></p>\n";  */
} else {
  header("Location: http://news.oka.no");
}

// header("Location: http://location.gl/news/?glat=" . $_POST['glat'] . "&glon=" . $_POST['glon'] . "&grad=100");
/* $location->link($_POST['name'],$_POST['glat'],$_POST['glon'],$_POST['link']); */
/* $location->push($_POST['name']); */
?>
