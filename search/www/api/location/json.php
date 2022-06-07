<?php
$db = mysqli_connect("piperpal.mysql.domeneshop.no","piperpal","xxxxxxxx","piperpal");
// mysqli_set_charset($db,"utf8");
if (isset($_GET['name'])) {
  $query = "SELECT DISTINCT id,name,service,location,modified,created,glat,glon,paid,token,type,email,111.045*DEGREES(ACOS(COS(RADIANS(latpoint))*COS(RADIANS(glat))*COS(RADIANS(longpoint)-RADIANS(glon))+SIN(RADIANS(latpoint))*SIN(RADIANS(glat)))) AS distance_in_km FROM piperpal JOIN (SELECT  " . $_GET['glat'] . "  AS latpoint, " . $_GET['glon'] . " AS longpoint) AS p ON 1=1 WHERE service = '" . $_GET['service'] . "' AND name = '" . $_GET['name'] . "' ORDER BY distance_in_km;";
} else {
    if (isset($_GET['service'])) {
        $query = "SELECT DISTINCT id,name,service,location,modified,created,glat,glon,paid,token,type,email,111.045*DEGREES(ACOS(COS(RADIANS(latpoint))*COS(RADIANS(glat))*COS(RADIANS(longpoint)-RADIANS(glon))+SIN(RADIANS(latpoint))*SIN(RADIANS(glat)))) AS distance_in_km FROM piperpal JOIN (SELECT  " . $_GET['glat'] . "  AS latpoint, " . $_GET['glon'] . " AS longpoint) AS p ON 1=1 WHERE service = '" . $_GET['service'] . "' ORDER BY distance_in_km";
    } else {
        $query = "SELECT DISTINCT id,name,service,location,modified,created,glat,glon,paid,token,type,email,111.045*DEGREES(ACOS(COS(RADIANS(latpoint))*COS(RADIANS(glat))*COS(RADIANS(longpoint)-RADIANS(glon))+SIN(RADIANS(latpoint))*SIN(RADIANS(glat)))) AS distance_in_km FROM piperpal JOIN (SELECT  " . $_GET['glat'] . "  AS latpoint, " . $_GET['glon'] . " AS longpoint) AS p ON 1=1 ORDER BY distance_in_km";    
    }
}
$result = $db->query($query);
// print $query;
$num_coords = mysqli_num_rows($result);
if ($num_coords == 0) {
  print '<h3>' . $_GET['service'] . '</h3><ul><li><a href="http://piperpal.com/cft/?location=' . $_GET['service'] . '&service=' . basename($_SERVER['HTTP_REFERER']) . '">New</a></li><li>Upvote</li><li>Comment</li><li>Stamp</li></ul></ul>';
//  header("Location: http://piperpal.com/");
} else {
  // print "$(function(){\n  var locations = [\n";
  print "var locations = '{ \"locations\" : [' + '";
  $count = 0;
  //  print "<h1>" . $_GET['service'] . "</h1>";
  while($object = mysqli_fetch_object($result)) {
    $count++;
    if ($count == $num_coords) {
      print '{"id": "' . $object->id . '", "name": "' . $object->name . '", "service": "' . $object->service . '", "location": "' . $object->location . '", "modified": "' . $object->modified . '", "created": "' . $object->created . '", "glat": "' . $object->glat . '", "glon": "' . $object->glon . '", "paid": "' . $object->paid . '", "token": "' . $object->token . '", "type": "' . $object->type . '", "distance": "' . $object->distance_in_km . '", "email": "' . $object->email . '"}';
    } else {
      print '{"id": "' . $object->id . '", "name": "' . $object->name . '", "service": "' . $object->service . '", "location": "' . $object->location . '", "modified": "' . $object->modified . '", "created": "' . $object->created . '", "glat": "' . $object->glat . '", "glon": "' . $object->glon . '", "paid": "' . $object->paid . '", "token": "' . $object->token . '", "type": "' . $object->type . '", "distance": "' . $object->distance_in_km . '", "email": "' . $object->email . '"},';
    }
  }
  print "]}';\n";
}
?>
