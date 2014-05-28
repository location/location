<?php

require_once("location-config.php");

class Location {

  public $db;

  public $data;
  public $link;
  public $name;
  public $glat;
  public $glon;
  public $dist;

  function __construct() {
    session_start();
    mb_internal_encoding("UTF-8");
  }

  function info() {
    // $this->data .= "<h3>Distribution</h3>\n<p><span style='background: #aaaaff'>" . $this->dist($this->name, $this->glat, $this->glon, $this->link, $this->dist) . "</span></p>";
  }

  function push() {
    $this->data .= "<!-- " . $this->name . " -->\n";
    $this->data .= "</body>\n</html>\n";
    echo $this->data;
  }

  function Midpoint($name) {
    $this->name = $name;
    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    $query = "SELECT DISTINCT * FROM location WHERE name = '" . $name . "';";

    // echo $query;

    $result = $this->db->query($query);

    $i = 0;

    $X = 0.0;
    $Y = 0.0;
    $Z = 0.0;

    $num_coords = mysqli_num_rows($result);

    while($object = mysqli_fetch_object($result)) {

      $lat = $object->glat * pi()/180;
      $lon = $object->glon * pi()/180;

      $a = cos($lat) * cos($lon);
      $b = cos($lat) * sin($lon);
      $c = sin($lat);
      
      $X += $a;
      $Y += $b;
      $Z += $c;
      
      // print $X .",". $Y .",". $Z . "<br />\n";
    }
    
    $X /= $num_coords;
    $Y /= $num_coords;
    $Z /= $num_coords;

    $lon = atan2($Y, $X);
    $hyp = sqrt($X * $X + $Y * $Y);
    $lat = atan2($Z, $hyp);

    if ($lat == 0 && $lon == 0) {
      $data .= "<p>Center is not yet calculated because there are no votes for this location.  Click on 'Vote' to enter some data.</p>";
    } else {
      $data .= "<p>Center is at " . $lat * 180 / pi() . "," . $lon * 180 / pi() . "</p>";
    }

    mysqli_close($this->db);   

    return $data;
  }

  function AverageDistance($name) {
    $this->name = $name;
    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    $query = "SELECT *,SUM(distance)/COUNT(distance) AS avg FROM votement WHERE name = '" . $name . "' ORDER BY distance DESC;";

    // echo $query;

    $result = $this->db->query($query);

    
    while($object = mysqli_fetch_object($result)) {
      if ($object->name != NULL) {
	$data .= "<p>" . $object->avg . " km (" . $object->vote . " votes)</p>";
      }
    } 
    
    mysqli_close($this->db);   

    return $data;
  }

  function dist($name, $glat, $glon, $link, $dist) {
    
    $this->name = $name;
    $this->glat = $glat;
    $this->glon = $glon;
    $this->link = $link;
    $this->dist = $dist;

    /* $data .= "<html><head>\n"; */
    /* $data .= "<title>Location Name Service - " . $name . "</title>\n"; */
    /* $data .= "<link rel='stylesheet' type='text/css' href='/location.css' />"; */
    /* $data .= "</head><body>\n"; */
    // $data .= "<a href='https://www.youtube.com/watch?v=ARJ8cAGm6JE'>I'm sorry Dave, I'm afraid I can't do that.</a>";

    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    $pt1 = -90;
    $pt2 = -180;
    $pt3 = 90;
    $pt4 = 180;

    // echo mysqli_character_set_name ($this->db);
    
    $query = "SELECT DISTINCT *, (
      6371.3929 * acos (
      cos ( radians(" . $glat . ") )
      * cos( radians( glat ) )
      * cos( radians( glon ) - radians(" . $glon . ") )
      + sin ( radians(" . $glat . ") )
      * sin( radians( glat ) )
    )) AS dist FROM location WHERE name = '" . $name . "' HAVING dist < " . $dist . " ORDER BY dist DESC;";

    echo $query;

    $result = $this->db->query($query);

    $data .= "<table>\n";

    while($object = mysqli_fetch_object($result)) {

      $data .= "<tr><td><p><b><a href='" . $object->link . "'>" . $object->name . "</a></b> - (" . $object->dist . ",(" . $object->glat . "," . $object->glon . ")</a><br /><a href='" . $object->link . "'>" . $object->link . "</a></p>\n";
      $data .= "</td></tr>\n";
      
    } 
    
    $data .= "</table>\n";
    
    mysqli_close($this->db);   
    
    return $data;
  }
  
  function vote($name, $glat, $glon, $link, $dist) {
    
    $this->name = $name;
    $this->glat = $glat;
    $this->glon = $glon;
    $this->link = $link;
    $this->dist = $dist;

    if ($this->name == NULL) $this->name = "name";
    if ($this->link == NULL) $this->link = "http://location.gl/" . $this->name;
    if ($this->name == "name") $this->link = "http://location.gl/";
    if ($this->link == "http://location.gl/vote/") $this->link = "http://location.gl/" . $this->name;

    if ($this->glat == NULL) $this->glat = 0;
    if ($this->glon == NULL) $this->glon = 0;

    if ($this->dist == NULL) $this->dist = 10000;

    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    // echo $name . $glat . $glon . $this->db->real_escape_string($link);

    /* if ($dist['California'] < 1); */ // UPDATE votement SET vote = vote + 1 WHERE name = 'California' AND id = 1;

    $query = "INSERT INTO location (name, glat, glon, ggeo, link, vote) VALUES ('" . $this->db->real_escape_string($name) . "', " . $this->db->real_escape_string($glat) . ", " . $this->db->real_escape_string($glon) . ", POINT(" . $this->db->real_escape_string($glat) . "," . $this->db->real_escape_string($glon) . "), '" . $this->db->real_escape_string($link) . "', 1);";

    // echo "<p>" . $query . "</p>\n";

    $result = $this->db->query($query);

    $query = "SELECT DISTINCT name, distance FROM votement WHERE name = '" . $this->db->real_escape_string($name) . "';"; /* FIXME: Replace vote value '1' with autoincremental value from database */

    // echo "<p>" . $query . "</p>\n";

    $result = $this->db->query($query);

    while ($object = mysqli_fetch_object($result)) {
      // echo "<p>Distance to " . $object->name . " is " . $object->distance . "</p>\n";
    }

    mysqli_close($this->db);

    $fp = fopen("/home/1/l/location/location/vote/data.sql","a+");
    fwrite($fp, "INSERT INTO location (name, glat, glon, ggeo, link) VALUES ('" . $this->name . "', " . $this->glat . ", " . $this->glon . ", POINT(" . $this->glat . "," . $this->glon . "), '" . $this->link . "');\n");
    fclose($fp);
    
    $fp = fopen("/home/1/l/location/location/vote/vote.txt","a+");
    fwrite($fp, $this->name . "," . $this->glat . "," . $this->glon . "," . $this->link . "\n");
    fclose($fp);

    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));
    
  }

  function link($name, $glat, $glon, $link, $dist) {

    $this->name = $name;
    $this->link = $link;
    $this->glat = $glat;
    $this->glon = $glon;
    $this->dist = $dist;

    if ($this->name == NULL) $this->name = "name";
    if ($this->link == NULL) $this->link = "http://location.gl/" . $this->name;
    if ($this->name == "name") $this->link = "http://location.gl/";
    if ($this->link == "http://location.gl/vote/") $this->link = "http://location.gl/" . $this->name;

    if ($this->glat == NULL) $this->glat = 0;
    if ($this->glon == NULL) $this->glon = 0;

    if ($this->dist == NULL) $this->dist = 10000;

    $this->data .= "<html>\n<head>\n<title>Location Name Service - " . $this->name . "</title>\n<meta charset='UTF-8'>\n<link rel='Stylesheet' type='text/css' href='/location.css' /><meta name='viewport' content='width=240; user-scalable=no' />\n<style>#map { width:100%; height:800px; }</style>\n";
    $this->data .= "<script src='http://maps.google.com/maps/api/js?sensor=false'></script>\n";
    $this->data .= "</head>\n<body>\n";
    $this->data .= "<h1>location.gl</h1>\n<script>link = '" . $this->link . "'; name = '" . $this->name ."'; glat = '" . $this->glat ."'; glon = '" . $this->glon . "'; dist = '" . $this->dist . "';</script>\n<script src='http://location.gl/location.js'></script>\n<h2><a href='" . $this->link . "'>" . $this->name . "</a></h2>\n<p><a href='" . $this->link . "'>" . $this->link . "</a></p>\n";
    $this->data .= "<h3>Midpoint</h3>\n";
    $this->data .= $this->Midpoint($this->name);
    $this->data .= "<h3>Average Distance</h3>\n";
    $this->data .= $this->AverageDistance($this->name);
    $this->data .= "<h3>Vote By Location</h3>\n";
    $this->data .= "<div id='location'></div>\n";   
    $this->data .= "<div id='errormsg'></div>\n";
    if ($_POST['name']!=NULL) {
      $this->data .= $this->info($this->name,$this->glat,$this->glon,$this->link,$this->dist);
    }
    $this->data .= "<h3>Privacy Notice</h3>\n<p><span style='background: #cccc00;'><i>location.gl stores geolocation data after you have clicked on \"Vote\", so don't click \"Vote\" if you don't want location.gl to store your location.</i></span></p>\n";

    return $this->data;

  }

}
?>

