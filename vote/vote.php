<?php

$array = array();
$row = 0;

if (($fp = fopen("vote.txt", "r")) != FALSE) { 
  while (($data = fgetcsv($fp, 1000, ",")) !== FALSE) { 
    $num = count($data); 
    // echo "<p>$num fields in line $row: <br /></p>\n"; 
     $row++; 
     array_push($array, $data); 
     for ($c=0; $c < $num; $c++) { 
       // echo $data[$c] . "<br />\n"; 
     } 
   } 
   fclose($fp); 
 } 

foreach ($array as $item) { 
  print "INSERT INTO location (name, glat, glon, ggeo) VALUES ('" . $item[0] . "', " . $item[1] . ", " . $item[2] . ", POINT(" . $item[1] . "," . $item[2] . "));\n";
} 

// California and Taco

$distance = 5000.0;

$orglat = "37.35201083799544";
$orglng = "-121.9352903165217";

$pt1 = $orglat + $distance / ( 111.1 / cos($orglat));
$pt2 = $orglng + $distance / 111.1;
$pt3 = $orglat - $distance / ( 111.1 / cos($orglat));
$pt4 = $orglng - $distance / 111.1;

$query="SELECT * FROM location WHERE MBRContains(GeomFromText('LineString(".$pt1." ".$pt2.", ".$pt3." ".$pt4.")'), ggeo);\n";

echo $query . "\n";

echo "Location Tags in USA\n";

echo "SELECT * FROM location WHERE MBRContains(GeomFromText('LineString(79.672569934946 -76.930789866477, -4.9685482589552 -166.93979076657)'), ggeo);\n";

// END: http://we-love-programming.blogspot.no/2012/01/mysql-fastest-distance-lookup-given.html

// Ålesund and Honda
// INSERT INTO location (name, glat, glon, ggeo) VALUES ('Honda', 62.4625998, 6.3676068, POINT(62.4625998,6.3676068));

$distance = 10.0;

$orglat = "62.4625998";
$orglng = "6.3676068";

$pt1 = $orglat + $distance / ( 111.1 / cos($orglat));
$pt2 = $orglng + $distance / 111.1;
$pt3 = $orglat - $distance / ( 111.1 / cos($orglat));
$pt4 = $orglng - $distance / 111.1;

$query="SELECT * FROM location WHERE MBRContains(GeomFromText('LineString(".$pt1." ".$pt2.", ".$pt3." ".$pt4.")'), ggeo);\n";

echo $query . "\n";

?>
