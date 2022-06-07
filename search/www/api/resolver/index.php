<?php

$fp = fopen("/home/4/p/piperpal/rank.csv", "a+");
fwrite($fp, "rank:" . $_SERVER['REMOTE_ADDR'] . ";" . $_GET['name'] . ";" . $_GET['location'] . ";" . $_GET['service'] . ";" . $_GET['paid'] . "\n");
fclose($fp);
#header("Location: https://piperpal.com/cft/s/?name=" . $_GET['name'] . "&glat=" . $_GET['glat'] . "&location=" . $_GET['location'] . "&glon=" . $_GET['glon'] . "&service=" . $_GET['service'] . "&paid=" . $_GET['paid']);
#https://api.piperpal.com/resolver/?name=Wikipedia+%28Encyclop%C3%A6dia%29&service=Books&location=http://www.wikipedia.org/&glat=59.93405070&glon=10.74806250&paid=1
#exit(0);

# echo $_SERVER['REMOTE_ADDR'];

# if ($_SERVER['REMOTE_ADDR']=="178.255.144.178" || $_SERVER['REMOTE_ADDR']=="51.175.144.124") {
if ($_SERVER['REMOTE_ADDR']=="178.255.144.178") {
    $db = mysqli_connect("piperpal.mysql.domeneshop.no","piperpal","xxxxxxxx","piperpal");
    $query = "INSERT INTO piperpal (name,location,service,glat,glon,paid) VALUES ('" . $_GET['name'] . "', '" . $_GET['location'] . "', '" . $_GET['service'] . "', " . $_GET['glat'] . "," . $_GET['glon'] . "," . $_GET['paid'] . ") WHERE NOT EXISTS (SELECT name FROM piperpal WHERE name = '" . $_GET['name'] . "');";
    // print $query . "\n";
    $result = $db->query($query);
}
?>
