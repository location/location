<?php
require_once("../location.php");

$location = new Location();
print ("<p>PUSH " . $_GET['name'] . "</p>\n");
echo $location->AverageDistance($_GET['name']);

$uploaddir = 'location/push/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']) . time();
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "Push was valid and successful.\n";
} else {
    echo "Duplicate push.  RETRY in 15 minutes.\n";
}
?>
