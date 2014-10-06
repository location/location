<?php
require_once("../location.php");

$location = new Location();
$sessiont = microtime();

// if ($_GET['name']!=NULL && $_GET['glat']!=NULL && $_GET['glon']!=NULL && $_GET['link']!=NULL) { 
print ("LNS 0.1.0 " . $sessiont . " PUSH DATA " . $_GET['name'] . " " . $_GET['glat'] . " " . $_GET['glon'] . " " . $_GET['link'] . "\n");
if ($location->push($_GET['name'],$_GET['glat'],$_GET['glon'],$_GET['link'],$_GET['grad'],$_GET['vote']==PUSHOKAY)) {
    print "LNS 0.1.0 " . $sessiont . " PUSH OKAY\n";
  } else if ($location->push($_GET['name'],$_GET['glat'],$_GET['glon'],$_GET['link'],$_GET['grad'],$_GET['vote']==PUSHFAIL)) {
  {
    print "LNS 0.1.0 " . $sessiont . " PUSH FAIL\nLNS 0.1.0 " . $sessiont . " PUSH CONT\n";
    /* Check geolocation address.  If glat and glon is approximately within grad specified, then send retry. */
  }
}

// header("Location: http://location.gl/" . $_GET['name'] . ".list?glat=" . $_GET['glat'] . "&glon=" . $_GET['glon'] . "&grad=" . $_GET['grad']);
/* $location->info .= "<p><span style='background: #ffcc00'>";  */
/* $location->info .= "Registered vote for <b>" . $_POST['name'] . "</b> (<a href='" . $_POST['link'] . "'>" . $_POST['link'] . "</a></b>) from (<b>" . $_POST['glat'] . "</b>,<b>" . $_POST['glon'] . "</b>)</span></p>\n";   */
/* $location->info .= "<p><span style='background: #ffccff'>";  */
/* $location->info .= $location->news($_POST['glat'], $_POST['glon'], 1000);  */
/* $location->info .= "</span></p>\n";  */

// } else {
//    header("Location: http://location.gl/" . $_GET['name'] . ".list");
// }

// echo $location->AverageDistance($_GET['name']);

/* $uploaddir = 'location/push/'; */
/* $uploadfile = $uploaddir . basename($_FILES['userfile']['name']) . time(); */
/* if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) { */
/*     print ("LNS VALIDATE PUSH " . $_GET['name'] . " " . $_GET['glat'] . " " . $_GET['glon'] . " " . $_GET['link'] . "\n"); */
/* } else { */
/*     print ("LNS DUPLICAT PULL " . $_GET['name'] . " " . $_GET['glat'] . " " . $_GET['glon'] . " " . $_GET['link'] . "\n"); */
/* } */
?>
