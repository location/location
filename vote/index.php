<html>
<head>
<meta charset='UTF-8'>
<?php
   echo "<meta http-equiv='refresh' content='5; url=http://location.gl/name/index.php?name=" . urlencode($_POST['name']) . "&glat=" . $_POST['glat'] . "&glon=" . $_POST['glon'] . "&link=" . urlencode($_POST['link']) . "' />";
?>
</head>
<body>

<?php
require_once("../location.php");

$location = new Location();
$location->vote($_POST['name'],$_POST['glat'],$_POST['glon'],$_POST['link']);

echo "<h1>location.gl</h1>\n";
echo "<h2>Success!</h2>\n";
echo "<p>Registered vote for " . $_POST['name'] . " (" . $_POST['link'] . ") from (" . $_POST['glat'] . "," . $_POST['glon'] . ")</p>";

?>
</body>
</html>
