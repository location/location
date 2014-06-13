<?php

require_once("location-config.php");

class Location {

  public $db;

  public $data;
  public $info;
  public $news;

  public $link;
  public $name;

  public $glat;
  public $glon;
  public $grad;

  function __construct() {
    /* session_start(); */
    // mb_internal_encoding("UTF-8");
  }

  function push() {
    $this->data .= "<!-- " . $this->name . " -->\n";
    $this->data .= "</body>\n</html>\n";
    echo $this->data;
  }

  function AppearIn($name) {

    $data = "<p><iframe src='http://appear.in/" . sha256("http://location.gl/".$name) . "' width='400' height='400'></iframe>\n";

    return $data;
  }

  function Midpoint($name) {

    $this->name = $name;
    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    $query = "SELECT DISTINCT * FROM location WHERE name = '" . $this->db->real_escape_string($name) . "';";

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
      $data .= "<p>None</p>";
    } else {
      $data .= "<p><a href='https://maps.google.com/?q=" . $lat * 180 / pi() . "," . $lon * 180 / pi() . "'>" . $lat * 180 / pi() . "," . $lon * 180 / pi() . "</a></p>";
    }

    mysqli_close($this->db);   

    return $data;
  }

  function NewsLink($name) {

    $this->name = $name;
    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    $query = "SELECT DISTINCT * FROM location WHERE name = '" . $this->db->real_escape_string($name) . "';";

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
      $data .= "<p>None</p>";
    } else {
      $data .= "<a href='http://location.gl/news/?glat=" . $lat*180/pi() . "&glon=" . $lon*180/pi() . "&grad=" . $this->grad . "'>http://location.gl/news/?glat=" . $lat*180/pi() . "&glon=" . $lon*180/pi() . "&grad=" . $this->grad . "</a>";
    }

    mysqli_close($this->db);   

    return $data;
  }

  function AverageDistance($name) {
    $this->name = $name;
    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    $query = "SELECT *,SUM(distance)/COUNT(distance) AS avg FROM votement WHERE name = '" . $this->db->real_escape_string($name) . "' ORDER BY distance DESC;";

    // echo $query;

    $result = $this->db->query($query);

    while($object = mysqli_fetch_object($result)) {
      if ($object->name != NULL) {
	$data .= "<p>" . $object->avg . " km away (" . $object->vote . " votes)</p>\n";
      } else {
	$data .= "<p>None</p>\n";
      }
    } 
    
    mysqli_close($this->db);   

    return $data;
  }

  function LastVoteDistance($name) {
    
    $this->name = $name;
    $this->glat = $glat;
    $this->glon = $glon;
    $this->link = $link;
    $this->grad = $grad;

    /* $data .= "<html><head>\n"; */
    /* $data .= "<title>Location Name Service - " . $name . "</title>\n"; */
    /* $data .= "<link rel='stylesheet' type='text/css' href='/location.css' />"; */
    /* $data .= "</head><body>\n"; */
    // $data .= "<a href='https://www.youtube.com/watch?v=ARJ8cAGm6JE'>I'm sorry Dave, I'm afraid I can't do that.</a>";

    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    $query = "SELECT DISTINCT location.id,location.name,location.glat,location.glon,votement.distance,location.link FROM votement,location WHERE location.name = '" . $this->db->real_escape_string($name) . "' AND votement.name = location.name AND location.glat = votement.glat AND location.glon = votement.glon ORDER BY votement.distance;";

    // echo $query;

    $result = $this->db->query($query);

    $num_votes = mysqli_num_rows($result);

    if ($num_votes != 0) {

      while($object = mysqli_fetch_object($result)) {
	$data .= "<b>" . $object->name . " {" . $object->id . "} is " . $object->distance . " km away from last vote<br />\n";
	// print_r($object);
      } 

      
    } else {

      $data .= "<p>None</p>";

    }

    mysqli_close($this->db);   
    
    return $data;
  }

  function news($glat, $glon, $grad) {
    $this->glat = $glat;
    $this->glon = $glon;
    $this->grad = $grad;

    // Locate the distance to the farthest or the nearest point for $name instead of using radius

    $pt1 = $this->glat + $this->grad / ( 111.1 / cos($this->glat));
    $pt2 = $this->glon + $this->grad / 111.1;
    $pt3 = $this->glat - $this->grad / ( 111.1 / cos($this->glat));
    $pt4 = $this->glon - $this->grad / 111.1;

    // Verify this
    $query="SELECT * FROM location WHERE MBRContains(GeomFromText('LineString(".$pt1." ".$pt2.", ".$pt3." ".$pt4.")'), ggeo);\n";
    
    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    // $query = "SELECT DISTINCT location.name,location.glat,location.glon,votement.distance,location.link FROM votement,location WHERE location.name = '" . $name . "' AND votement.name = location.name AND location.glat = votement.glat AND location.glon = votement.glon ORDER BY votement.distance;";

    // List hot name items near you

    $query = "SELECT DISTINCT location.id,location.name,location.glat,location.glon,votement.distance,location.link FROM votement,location WHERE MBRContains(GeomFromText('LineString(".$pt1." ".$pt2.", ".$pt3." ".$pt4.")'), location.ggeo) AND votement.name = location.name AND location.glat = votement.glat AND location.glon = votement.glon ORDER BY location.id DESC;";

    // $query = "SELECT DISTINCT * FROM votement WHERE glat = '" . $name . "' ORDER by rank DESC LIMIT 1;";

    // echo $query;

    $result = $this->db->query($query);

    $i = 0;

    while($object = mysqli_fetch_object($result)) {
      if ($i++%2 == 0) {
	$data .= "<table border='1' style='background: #cccccc' width='100%'>";
      } else {
	$data .= "<table border='1' style='background: #eeeee' width='100%'>";
      }
      $data .= "<tr><th width='50'>Name {#}</th><td><a href='http://location.gl/" . $object->name . "'>" . $object->name . "</a> {" . $object->id . "}</td></tr><tr><th>Link</th><td><a href='" . $object->link . "'>" . $object->link . "</a></td></tr><!-- tr form method=POST action='http://location.gl/vote/'><input type='hidden' name='name' value='" . $object->name . "' <input type='hidden' name='glat' value='" . $object->glat . "' /><input type='hidden' name='glon' value='" . $object->glon . "' /><input type='hidden' name='grad' value='" . ((6371.3929 * acos (cos ( deg2rad($object->glat) ) * cos( deg2rad( $this->glat ) ) * cos( deg2rad( $this->glon ) - deg2rad($object->glon) ) + sin ( deg2rad($object->glat)) * sin( deg2rad( $this->glat ))))) . "' /><input type='submit' name='Vote' value='Vote' /> /form --></td></tr><tr><!-- <th>Home/Away</th><td>" . ((6371.3929 * acos (cos ( deg2rad($object->glat) ) * cos( deg2rad( $this->glat ) ) * cos( deg2rad( $this->glon ) - deg2rad($object->glon) ) + sin ( deg2rad($object->glat)) * sin( deg2rad( $this->glat ))))) . " km away</td></tr>--><tr><th>GMap</th><td><a href='https://maps.google.com/?q=" . $object->glat . "," . $object->glon . "'>" . $object->glat . "," . $object->glon . "</a></td></tr><tr><th>Midpoint</th><td>" . $this->Midpoint($object->name) . "</td></tr><tr><th>Distances</th><td>" . $this->LastVoteDistance($object->name) . "</td></tr><tr><th>Video</th><td><a href='http://appear.in/" . sha256($object->name) . "'>http://appear.in/" . sha256($object->name) . "</a></td></tr><tr><th>JSON</th><td>" . $this->json($object->name, $object->id) . "</td></tr></table>";
    }

    return $data;
  }
  
  function json($name, $id) {

    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    // $query = "SELECT DISTINCT location.name,location.glat,location.glon,votement.distance,location.link FROM votement,location WHERE location.name = '" . $name . "' AND votement.name = location.name AND location.glat = votement.glat AND location.glon = votement.glon ORDER BY votement.distance;";

    // List hot name items near you

    $query = "SELECT name,link,glat,glon FROM location WHERE name = '" . $this->db->real_escape_string($name) . "' AND id = '" . $this->db->real_escape_string($id) . "';";

    $result = $this->db->query($query);

    while($obj = mysql_fetch_object($result)) {
      $arr[] = $obj;      
    }

    return json_encode($obj);
   
  }

  function vote($name, $glat, $glon, $link, $grad) {
    
    $this->name = $name;
    $this->glat = $glat;
    $this->glon = $glon;
    $this->link = $link;
    $this->grad = $grad;

    $this->name = $name;
    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    $query = "SELECT DISTINCT link FROM location WHERE name = '" . $this->db->real_escape_string($name) . "' ORDER by vote DESC LIMIT 1;";

    // echo $query;

    $result = $this->db->query($query);

    while($object = mysqli_fetch_object($result)) {
      $this->link = $object->link;
    }
    
    if ($this->name == NULL) $this->name = "Home";
    if ($this->name == "Home") $this->link = "http://location.gl/"; 
    if ($this->link == "http://location.gl/vote/") $this->link = $object->link;
    if ($this->link == NULL) $this->link = "http://location.gl/" . $this->name;

    mysqli_close($this->db);

    if ($this->glat == NULL) $this->glat = 0;
    if ($this->glon == NULL) $this->glon = 0;

    if ($this->grad == NULL) $this->grad = 100000;

    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    // echo $name . $glat . $glon . $this->db->real_escape_string($link);

    /* if ($grad['California'] < 1); */ // UPDATE votement SET vote = vote + 1 WHERE name = 'California' AND id = 1;

    $query = "INSERT INTO location (name, glat, glon, ggeo, link, vote) VALUES ('" . $this->db->real_escape_string($name) . "', " . $this->db->real_escape_string($glat) . ", " . $this->db->real_escape_string($glon) . ", POINT(" . $this->db->real_escape_string($glat) . "," . $this->db->real_escape_string($glon) . "), '" . $this->db->real_escape_string($link) . "', 1);";

    // echo "<p>" . $query . "</p>\n";

    $result = $this->db->query($query);

    $query = "SELECT DISTINCT name, distance FROM votement WHERE name = '" . $this->db->real_escape_string($name) . "';";

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

  }

  function link($name, $glat, $glon, $link, $grad) {

    $this->name = $name;
    $this->link = $link;
    $this->glat = $glat;
    $this->glon = $glon;
    $this->grad = $grad;
    $this->name = $name;

    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    $query = "SELECT DISTINCT link FROM location WHERE name = '" . $name . "' ORDER by rank DESC LIMIT 1;";

    // echo $query;

    $result = $this->db->query($query);

    while($object = mysqli_fetch_object($result)) {
      $this->link = $object->link;
    }
    
    if ($this->name == NULL) $this->name = "Home";
    if ($this->name == "Home") $this->link = "http://location.gl/"; 
    if ($this->link == "http://location.gl/vote/") $this->link = $object->link;
    if ($this->link == NULL) $this->link = "http://location.gl/" . $this->name;

    mysqli_close($this->db);

    if ($this->glat == NULL) $this->glat = 0;
    if ($this->glon == NULL) $this->glon = 0;
    if ($this->grad == NULL) $this->grad = 100000;

    $this->data .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
    $this->data .= '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">';

    $this->data .= "\n<head>\n<title>Location Name Service - " . $this->name . "</title>\n";
    $this->data .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    $this->data .= "<link rel='Stylesheet' type='text/css' href='/location.css' /><meta name='viewport' content='width=240; user-scalable=no' />\n<style type='text/css'>#map { width:100%; height:800px; }</style>\n";
    $this->data .= "<script src='http://maps.google.com/maps/api/js?sensor=false' type='text/javascript'></script>\n";
    $this->data .= "</head>\n<body>\n";
    $this->data .= $this->info;
    $this->data .= "<h1>location.gl</h1>\n";
    $this->data .= "<p><span style='background: #cccc00;'><i>location.gl stores geolocation data after you have clicked on \"Vote\", so don't click \"Vote\" if you don't want location.gl to store your location.</i></span></p>\n";
    $this->data .= "<script type='text/javascript'>link = '" . $this->link . "'; name = '" . $this->name ."'; glat = '" . $this->glat ."'; glon = '" . $this->glon . "'; grad = '" . $this->grad . "';</script>\n<script src='http://location.gl/location.js' type='text/javascript'></script>\n";
    // <h2><a href='" . $this->link . "'>" . $this->name . "</a></h2>\n<p><a href='" . $this->link . "'>" . $this->link . "</a></p>\n";
    $this->data .= "<div id='location'></div>\n";   
    $this->data .= "<div id='errormsg'></div>\n";
    $this->data .= "<h3>Video Conference</h3>\n";
    $this->data .= $this->AppearIn($this->name);
    $this->data .= "<h3>News</h3>\n";
    $this->data .= $this->NewsLink($this->name);
    $this->data .= "<h3>Midpoint</h3>\n";
    $this->data .= $this->Midpoint($this->name);
    $this->data .= "<h3>Last Vote Distance</h3>\n";
    $this->data .= $this->LastVoteDistance($this->name);
    $this->data .= "<h3>Average Distance for Last Vote Distances</h3>\n";
    $this->data .= $this->AverageDistance($this->name);
    return $this->data;

  }

}
?>
