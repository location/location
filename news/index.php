<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>Location Name Service - News</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><link rel='Stylesheet' type='text/css' href='/location.css' /><meta name='viewport' content='width=240; user-scalable=no' />
</head>
<body>
<h1>Location News</h1>

<?php
require_once("../location.php");

$location = new Location();

if ($_POST['glat'] != NULL && $_POST['glon'] != NULL && $_POST['grad'] != NULL) {
  echo $location->news($_POST['name'],$_POST['glat'],$_POST['glon'],$_POST['grad']);
}

if ($_GET['glat'] != NULL && $_GET['glon'] != NULL && $_GET['grad'] != NULL) {
  echo $location->news($_GET['name'],$_GET['glat'],$_GET['glon'],$_GET['grad']);
}
?>
</body>
</html>
