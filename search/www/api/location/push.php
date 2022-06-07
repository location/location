<?php

if ($_GET['paid'] > 0) {
    $fp = fopen("/home/4/p/piperpal/data.csv", "a+");
    fwrite($fp, "push;" . time() . ";" . $_SERVER['REMOTE_ADDR'] . ";" . $_GET['name'] . "\n");
    fclose($fp);
    header("Location: https://piperpal.com/cft/s/?name=" . $_GET['name'] . "&glat=" . $_GET['glat'] . "&location=" . $_GET['location'] . "&glon=" . $_GET['glon'] . "&service=" . $_GET['service'] . "&paid=" . $_GET['paid']);
}

exit(0);

/* $db = mysqli_connect("piperpal.mysql.domeneshop.no","piperpal","xxxxxxxx","piperpal"); */

/* $query = "INSERT INTO piperpal (name,location,service,glat,glon) VALUES ('" . $_GET['name'] . "', '" . $_GET['location'] . "', '" . $_GET['service'] . "', " . $_GET['glat'] . "," . $_GET['glon'] . ");"; */

/* $result = $db->query($query); */
?>
