<?php

class Location {

  public $data;
  public $link;
  public $name;
  public $glat;
  public $glon;

  function dist($name, $glat, $glon, $distance) {
    
    $this->name = $name;
    $this->glat = $glat;
    $this->glon = $glon;

    $pt1 = $glat + $distance / ( 111.1 / cos($glat));
    $pt2 = $glon + $distance / 111.1;
    $pt3 = $glat - $distance / ( 111.1 / cos($glat));
    $pt4 = $glon - $distance / 111.1;
    
    // $query="SELECT * FROM location WHERE MBRContains(GeomFromText('LineString(".$pt1." ".$pt2.", ".$pt3." ".$pt4.")'), ggeo);\n";
    $data .= "<html><head>\n";
    $data .= "<link rel='stylesheet' type='text/css' href='/location.css' />";
    $data .= "</head><body>\n";
    $data .= "<a href='https://www.youtube.com/watch?v=ARJ8cAGm6JE'>I'm sorry Dave, I'm afraid I can't do that.</a>";
    $data .= "</body></html>\n";
    return $data;
  }
  
  function vote($name, $glat, $glon, $link) {

    $this->name = $name;
    $this->glat = $glat;
    $this->glon = $glon;
    $this->link = $link;

    $fp = fopen("/home/1/l/location/location/vote/data.sql","a+");
    fwrite($fp, "INSERT INTO location (name, glat, glon, ggeo, link) VALUES ('" . $this->name . "', " . $this->glat . ", " . $this->glon . ", POINT(" . $this->glat . "," . $this->glon . "), '" . $this->link . "');\n");
    fclose($fp);
    
    $fp = fopen("/home/1/l/location/location/vote/vote.txt","a+");
    fwrite($fp, $this->name . "," . $this->glat . "," . $this->glon . "," . $this->link . "\n");
    fclose($fp);
		  
  }

  function find($name) {

    setlocale(LC_ALL, 'nb_NO.UTF8');

    if ($name == "name") return;

    $this->name = $name;

    $array = array();
    $row = 0;
    
    if (($fp = fopen("/home/1/l/location/location/vote/vote.txt", "r")) != FALSE) { 
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

    $found = 0;

    $data .= "<h3>Results</h3>\n";

    $data .= "<table>\n";
    foreach ($array as $item) {
      // print $item[0] . "<br />\n";
      if ($item[0]==$name) {
	$found = 1;
	$data .= "<tr><td><a href='" . $item[3] . "'>" . $item[0] . "</a></td><td><a href='/dist/?name=" . $item[0] . "&glat=" . "0" . "&glon=" . "0" . "&hash=" . sha256("" . $item[1] . "," . $item[2] . "") . "'>0,0</a></td></tr>\n";
	// "SELECT name, glat, glon FROM location WHERE name = '" . $item[0] . "';";
      }
    }
    $data .= "</table>\n";

    if ($found == 1) return $data;
    
  }
  
  function link($name, $glat, $glon, $link) {

    $this->name = $name;
    $this->link = $link;
    $this->glat = $glat;
    $this->glon = $glon;

    if ($this->name == NULL) $this->name = "name";
    if ($this->link == NULL) $this->link = "http://location.gl/" . $this->name;
    if ($this->name == "name") $this->link = "http://location.gl/";
    if ($this->link == "http://location.gl/vote/") $this->link = "http://location.gl/" . $this->name;

    if ($this->glat == NULL) $this->glat = 0;
    if ($this->glon == NULL) $this->glon = 0;

    $this->data .= "<html>\n<head>\n<title>" . $this->name . "</title>\n<meta charset='UTF-8'>\n";
    $this->data .= "<link rel='Stylesheet' type='text/css' href='/location.css' />";
    $this->data .= "<meta name='viewport' content='width=240; user-scalable=no' />\n";
    $this->data .= "<style>#map { width:100%; height:800px; }</style>\n";
    $this->data .= "<script src='http://maps.google.com/maps/api/js?sensor=false'></script>\n";
    $this->data .= "</head>\n";
    $this->data .= "<body>\n";
    $this->data .= "<h1>location.gl</h1>\n<script>link = '" . $this->link . "'; name = '" . $this->name ."'; glat = '" . $this->glat ."'; glon = '" . $this->glon . "';</script>\n";
    $this->data .= "<script src='http://location.gl/location.js'></script>\n";
    $this->data .= "<h2><a href='" . $this->link . "'>" . $this->name . "</a></h2>\n";
    $this->data .= "<p><a href='" . $this->link . "'>" . $this->link . "</a></p>\n";
    $this->data .= "<div id='errormsg'></div>\n";
    $this->data .= "<div id='location'></div>\n";   
    $this->data .= "<h3>Privacy</h3>\n<p><i>location.gl stores geolocation data after you have clicked on \"Vote\", so don't click \"Vote\" if you don't want location.gl to store your location.</i></p>\n";
    $this->data .= $this->find($this->name);
    $this->data .= "</body>\n</html>\n";

    return $this->data;

  }

}
?>
