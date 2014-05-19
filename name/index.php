<?php
require_once("../location.php");
$location = new Location();

if ($_GET['name']=="name" || $_GET['name']==NULL || $_GET['link']=="http://location.gl/name" || $_GET['link'] == NULL) {
  header("Location: http://location.gl/");
} else {
  echo "<html>\n";
  echo "<head>\n";
  echo "<meta charset='UTF-8'>\n";
  echo "</head>\n";
  echo "<body>\n";
  echo "<h1>location.gl</h1>\n";
  echo "<h2><a href='http://location.gl/" . $_GET['name'] . "'>" . $_GET['name'] . "</a></h2>\n";
  echo "<p><a href='" . $_GET['link'] . "'>" . $_GET['link'] . "</a></p>\n";
  // echo "<p><a href='" . $_GET['link'] . "'>" . $_GET['name'] . "," . $_GET['glat'] . "," . $_GET['glon'] . "</a></p>";
  echo $location->find($_GET['name']);
  echo "</body>\n";
  echo "</html>\n";
}
?>
