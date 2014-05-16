<?php

class Location {

  public $data;
  public $link;
  public $name;
  public $glat;
  public $glon;

  function link($name, $glat, $glon, $link) {

    $this->name = $name;
    $this->link = $link;
    $this->glat = $glat;
    $this->glon = $glon;

    if ($this->name == NULL) $this->name = "name";
    if ($this->link == NULL) $this->link = "http://location.gl/link";
    if ($this->glat == NULL) $this->glat = 60;
    if ($this->glon == NULL) $this->glon = 10;

    $this->data .= "<html>\n<head>\n<title>" . $this->name . "</title>\n<meta charset='UTF-8'>\n";
    $this->data .= "<style>#map { width:100%; height:800px; }</style>\n";
    $this->data .= "<script src='http://maps.google.com/maps/api/js?sensor=false'></script>\n";
    $this->data .= "</head>\n";
    $this->data .= "<body>\n";
    $this->data .= "<h1>location.gl</h1>\n<script>link = '" . $this->link . "'; name = '" . $this->name ."'; glat = '" . $this->glat ."'; glon = '" . $this->glon . "';</script>\n";
    $this->data .= "<script src='http://location.gl/location.js'></script>\n";
    $this->data .= "<h2>" . $this->name . "</h2>\n";
    $this->data .= "<div id='errormsg'></div>\n";
    $this->data .= "<div id='location'></div>\n";   
    $this->data .= "<h3>Privacy</h3>\n<p><i>location.gl stores geolocation data after you have clicked on \"Vote\", so don't click \"Vote\" if you don't want location.gl to store your location.<br />location.gl won't share the geolocation data or the voting results during the testing phase with anybody.</i></p>\n";
    $this->data .= "</body>\n</html>\n";

    return $this->data;

  }

}

$location = new Location();
$location->link($_GET['name'],$_GET['glat'],$_GET['glon'],$_SERVER['HTTP_REFERER']);
echo $location->data;

?>
