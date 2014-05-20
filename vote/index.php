<?php
require_once("../location.php");

$location = new Location();
$location->vote($_POST['name'],$_POST['glat'],$_POST['glon'],$_POST['link']);
/* echo "<h1>location.gl</h1>\n"; */
/* echo "<h2>Success!</h2>\n"; */
/* echo "<p>Registered vote for " . $_POST['name'] . " (" . $_POST['link'] . ") from (" . $_POST['glat'] . "," . $_POST['glon'] . ")</p>"; */
$location->link($_POST['name'],$_POST['glat'],$_POST['glon'],$_POST['link']);
echo $location->dist($_POST['glat'],$_POST['glon'],0.5);
echo $location->data;

?>
