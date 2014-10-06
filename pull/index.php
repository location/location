<?php
require_once("../location.php");
// header("Location: http://location.gl/" . $_GET['name']);
// exit(0);
$loc = new Location();
$sessiont = microtime();

// if ($_GET['name']!=NULL && $_GET['glat']!=NULL && $_GET['glon']!=NULL && $_GET['link']!=NULL) { 
print (PROTOCOL . " " . $sessiont . " PULL DATA " . $_GET['name'] . " " . $_GET['glat'] . " " . $_GET['glon'] . " " . $_GET['link'] . "\n");

$loc->pull($_GET['name'],$_GET['glat'],$_GET['glon'],$_GET['link'],$_GET['grad'],$_GET['vote']);


if ($location->pull($_GET['name'],$_GET['glat'],$_GET['glon'],$_GET['link'],$_GET['grad'],$_GET['vote']==PULLOKAY)) {
  print "LNS 0.1.0 " . $sessiont . " PULL OKAY\n";
  $location->json();
} else if ($location->pull($_GET['name'],$_GET['glat'],$_GET['glon'],$_GET['link'],$_GET['grad'],$_GET['vote']==PULLFAIL)) {
  {
    print "LNS 0.1.0 " . $sessiont . " PULL FAIL\nLNS 0.1.0 " . $sessiont . " PULL CONT\n";  
    /* Check geolocation address.  If glat and glon is approximately within grad specified, then send retry. */
  }
}

?>
