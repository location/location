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

  function send() {
    $this->data .= "<h3>Source</h3>\n<a href='https://github.com/location/" . __CLASS__ . "'>https://github.com/location/" . __CLASS__ . "</a>\n";
    $this->data .= "<!-- " . $this->name . " -->\n";
    $this->data .= "</body>\n</html>\n";
    echo $this->data; 
  }

  function AppearIn($name) {

    $s256 = sha256("http://location.gl/".$name);
    $data = "<p><a href='http://appear.in/" . $s256 . "'>http://appear.in/" . $s256 . "</a></p>\n";

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

  function LastVoteDistance($name,$glat,$glon,$link,$grad) {
    
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

    $query = "SELECT DISTINCT location.id,location.name,location.vote,location.glat,location.glon,votement.distance,location.link FROM votement,location WHERE location.name = '" . $this->db->real_escape_string($name) . "' AND votement.name = location.name AND location.glat = votement.glat AND location.glon = votement.glon ORDER BY votement.distance;";

    // echo $query;

    $result = $this->db->query($query);

    $num_votes = mysqli_num_rows($result);

    if ($num_votes != 0) {

      while($object = mysqli_fetch_object($result)) {
	$data .= "<b>{" . $object->id . "}, <a href='" . $object->link . "'>" . $object->link . "</a>, (" . $object->glat . "," . $object->glon . "), " . $object->distance . " km away, " . $object->vote . " votes<br />\n";
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

    $query = "SELECT DISTINCT location.id,location.name,location.glat,location.glon,votement.distance,location.link,location.time FROM votement,location WHERE MBRContains(GeomFromText('LineString(".$this->db->real_escape_string($pt1)." ".$this->db->real_escape_string($pt2).", ".$this->db->real_escape_string($pt3)." ".$this->db->real_escape_string($pt4).")'), location.ggeo) AND votement.name = location.name AND location.glat = votement.glat AND location.glon = votement.glon ORDER BY location.id DESC;";

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
      $data .= "<tr><th width='50'>Name {#}</th><td><a href='http://location.gl/" . $object->name . "'>" . $object->name . "</a> {" . $object->id . "}</td></tr><tr><th>Link</th><td><a href='" . $object->link . "'>" . $object->link . "</a></td></tr><!-- tr form method=POST action='http://location.gl/vote/'><input type='hidden' name='name' value='" . $object->name . "' <input type='hidden' name='glat' value='" . $object->glat . "' /><input type='hidden' name='glon' value='" . $object->glon . "' /><input type='hidden' name='grad' value='" . ((6371.3929 * acos (cos ( deg2rad($object->glat) ) * cos( deg2rad( $this->glat ) ) * cos( deg2rad( $this->glon ) - deg2rad($object->glon) ) + sin ( deg2rad($object->glat)) * sin( deg2rad( $this->glat ))))) . "' /><input type='submit' name='Vote' value='Vote' /> /form --></td></tr><tr><!-- <th>Home/Away</th><td>" . ((6371.3929 * acos (cos ( deg2rad($object->glat) ) * cos( deg2rad( $this->glat ) ) * cos( deg2rad( $this->glon ) - deg2rad($object->glon) ) + sin ( deg2rad($object->glat)) * sin( deg2rad( $this->glat ))))) . " km away</td></tr>--><tr><th>GMap</th><td><a href='https://maps.google.com/?q=" . $object->glat . "," . $object->glon . "'>" . $object->glat . "," . $object->glon . "</a></td></tr><tr><th>Midpoint</th><td>" . $this->Midpoint($object->name) . "</td></tr><tr><th>Distances</th><td>" . $this->LastVoteDistance($object->name) . "</td></tr><tr><th>Video</th><td><a href='http://appear.in/" . sha256($object->name) . "'>http://appear.in/" . sha256($object->name) . "</a></td></tr><tr><th>Time</th><td>" . $object->time . "</td></tr></table>";
    }

    if ($i==0) {
      print "<p>No news is good news.</p><p>Usage: Add hyperlink to http://location.gl/SomeName or search below.<form method='GET' action='http://location.gl/'><input type='text' name='name' value='' /><input type='submit' value='Search' /></form></p><p>Then click Vote.</p>";
    }

    return $data;
  }
  
  function json($name) {

    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    // $query = "SELECT DISTINCT location.name,location.glat,location.glon,votement.distance,location.link FROM votement,location WHERE location.name = '" . $name . "' AND votement.name = location.name AND location.glat = votement.glat AND location.glon = votement.glon ORDER BY votement.distance;";

    // List hot name items near you

    $query = "SELECT DISTINCT location.id,location.name,location.glat,location.glon,votement.distance,location.link FROM votement,location WHERE MBRContains(GeomFromText('LineString(".$this->db->real_escape_string($pt1)." ".$this->db->real_escape_string($pt2).", ".$this->db->real_escape_string($pt3)." ".$this->db->real_escape_string($pt4).")'), location.ggeo) AND votement.name = location.name AND location.glat = votement.glat AND location.glon = votement.glon ORDER BY location.id DESC;";

    $data = array();
    $result = $this->db->query($query);

    $data = array();
    while ( $row = mysql_fetch_array($result) )
      {
        $data[$row['id']] = array( "id" => $row['id'] ,"name" => $row['name']);
      }

    // print_r($data);
    return json_encode($data);
   
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

    $query = "INSERT INTO location (name, glat, glon, ggeo, link, vote, time) VALUES ('" . $this->db->real_escape_string($name) . "', " . $this->db->real_escape_string($glat) . ", " . $this->db->real_escape_string($glon) . ", POINT(" . $this->db->real_escape_string($glat) . "," . $this->db->real_escape_string($glon) . "), '" . $this->db->real_escape_string($link) . "', 1, NOW());";

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
    fwrite($fp, "INSERT INTO location (name, glat, glon, ggeo, link, time) VALUES ('" . $this->name . "', " . $this->glat . ", " . $this->glon . ", POINT(" . $this->glat . "," . $this->glon . "), '" . $this->link . "',NOW());\n");
    fclose($fp);
    
    $fp = fopen("/home/1/l/location/location/vote/vote.txt","a+");
    fwrite($fp, $this->name . "," . $this->glat . "," . $this->glon . "," . $this->link . "," . $this->time . "\n");
    fclose($fp);

  }

  function cached($name, $glat, $glon, $link, $grad) {
    // Check if ($name,$glat,$glon,$link,$grad) is in cache.  If true:
    // return PULLNAME;
    // If false,
    // return PUSHNAME;

    return PUSHNAME;
  }

  function pull($name, $glat, $glon, $link, $grad) {
    // Location Tags already indexed for $link, pull from database table locationtags for $name, $glat, $glon, $link, $grad
    return "<location link='http://newonflix.com/article1.html' glat='60' glon='10' grad='100'>Tag1</location><location link='http://newonflix.com/article2.html' glat='60' glon='10' grad='100'>Tag2</location link='http://newonflix.com/article3.html' glat='60' glon='10' grad='100'>Tag3</location>";
  }
  
  function push($name, $glat, $glon, $link, $grad) {
    // Fetch HTML on http://newonflix.com/article1.html ($link) using curl

    // Look for all occurences of '$name' in HTML on http://newonflix.com/article1.html ($link) in the pattern "<a href='http://location.gl/$name'>$name</a>"  /* REGEXP? */

    // Return occurences of '$name'

    // Return list of all Location Tags such as <location>Oslo</location>, <location>Lillehammer</location> tags on http://newonflix.com/article.html

    // Store Location Tags in 2-gram location word table

    // CREATE TABLE locationtags (
    //   key TEXT,  /* Tag1 DUPLICATE */ /* Tag1 */   /* Tag1 */   /* Tag2 DUPLICATE */   /* Tag2 DUPLICATE */    /* Tag2 */ /* Tag3 DUPLICATE */    /* Tag3 DUPLICATE */  /* Tag3 DUPLICATE */
    //   name TEXT, /* Tag1 */           /* Tag2 */   /* Tag3 */   /* Tag1 */             /* Tag2 */              /* Tag3 */  /* Tag1 */             /* Tag2 */            /* Tag3 */
    //   glat FLOAT,
    //   glon FLOAT,
    //   link TEXT,   /* http://newonflix.com/article.html */
    //   grad FLOAT
    // );

    // Location Tags already indexed for $link, pull from database table locationtags for $name, $glat, $glon, $link, $grad
    return "<location name='Tag1' link='http://newonflix.com/article1.html' glat='60' glon='10' grad='100'><a href='http://location.gl/Tag1'>Tag1</a></location><location name='Tag2' link='http://newonflix.com/article2.html' glat='60' glon='10' grad='100'><a href='http://location.gl/Tag2'>Tag2</a></location><location name='Tag3' link='http://newonflix.com/article3.html' glat='60' glon='10' grad='100'><a href='http://location.gl/Tag3'>Tag3</a></location>";
  }

  function tags($name, $glat, $glon, $link, $grad) {
    // check if ($name, $link) is in cache.
    $cached = $this->cached($name, $glat, $glon, $link, $grad);
    if ($cached == PULLNAME) {
      $this->data .= $this->pull($name, $glat, $glon, $link, $grad);
    }
    if ($cached == PUSHNAME) {
      $this->data .= $this->push($name, $glat, $glon, $link, $grad);
    }
  }

  /* function route($name) { */
  /*   $data = $this->location("OSL"); */
  /*   $data .= $this->location("DY7001"); */
  /*   $data .= $this->location("JFK"); */
  /*   $data .= $this->location("NYC"); */
  /*   $data .= $this->location("Westin"); */
  /*   $data .= $this->location("EmpireStateBuilding"); */
  /*   $data .= $this->location("RoseCenterforEarthandSpace"); */
  /*   $data .= $this->location("AMNH"); */
  /*   $data .= $this->location("PalmNYCToo"); */
  /*   $data .= $this->location("NYP"); */
  /*   $data .= $this->location("BOS"); */
  /*   $data .= $this->location("KendallHotel"); */
  /*   $data .= $this->location("DY7002"); */
  /*   return $data; */
  /* } */

  function location($name) {
    $data = "<a href='http://location.gl/" . $name . "'>" . $name . "</a>\n";
    return $data;
  }

  function link($name, $glat, $glon, $link, $grad) {

    $this->name = $name;
    $this->link = $link;
    $this->glat = $glat;
    $this->glon = $glon;
    $this->grad = $grad;
    $this->name = $name;

    $this->db = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE) or die("Error " . mysqli_error($db));

    $query = "SELECT DISTINCT link FROM location WHERE name = '" . $this->db->real_escape_string($name) . "' ORDER by rank DESC LIMIT 1;";

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
<<<<<<< HEAD
    $this->data .= "<h1>location.gl</h1>\n";
=======
    $this->data .= "<h1>location.gl/" . $this->name . "</h1>\n";
>>>>>>> branch 'master' of https://github.com/location/location.git
    $this->data .= "<p><span style='background: #cccc00;'><i>location.gl stores geolocation data after you have clicked on \"Vote\", so don't click \"Vote\" if you don't want location.gl to store your location.</i></span></p>\n";
    $this->data .= "<script type='text/javascript'>link = '" . $this->link . "'; name = '" . $this->name ."'; glat = '" . $this->glat ."'; glon = '" . $this->glon . "'; grad = '" . $this->grad . "';</script>\n<script src='http://location.gl/location.js' type='text/javascript'></script>\n";
    // <h2><a href='" . $this->link . "'>" . $this->name . "</a></h2>\n<p><a href='" . $this->link . "'>" . $this->link . "</a></p>\n";
    $this->data .= "<div id='location'></div>\n";   
    $this->data .= "<div id='errormsg'></div>\n";
    /* $this->data .= "<h3>Route</h3>\n"; */
    /* $this->data .= $this->route($this->name); */
    $this->data .= "<h3>News</h3>\n";
    $this->data .= $this->NewsLink($this->name);
    $this->data .= "<h3>Midpoint</h3>\n";
    $this->data .= $this->Midpoint($this->name);
    $this->data .= "<h3>Last Vote Distance</h3>\n";
    $this->data .= $this->LastVoteDistance($this->name);
    $this->data .= "<h3>Average Distance for Last Vote Distances</h3>\n";
    $this->data .= $this->AverageDistance($this->name);
    $this->data .= "<h3>Video</h3>\n";
    $this->data .= $this->AppearIn($this->name);
    $this->data .= "<h3>Tags</h3>\n";
    $this->data .= $this->tags($this->name, $this->glat, $this->glon, $this->link, $this->grad);
    return $this->data;

  }

}
?>
