<?php

class Location {

  public $data;
  public $link;
  public $name;
  public $glat;
  public $glon;

  function link($name, $link) {

    $this->name = $name;
    $this->link = $link;

    if ($this->name == NULL) $this->name = "name";
    if ($this->link == NULL) $this->link = "http://location.gl/link";

    $this->data .= "<html>\n<head>\n<title>" . $this->name . "</title>\n";
    $this->data .= "<style>#map { width:100%; height:800px; }</style>\n";
    $this->data .= "<script src='http://maps.google.com/maps/api/js?sensor=false'></script>\n";
    $this->data .= "</head>\n";
    $this->data .= "<body>\n";
    $this->data .= "<script>link = '" . $this->link . "'; name = '" . $this->name . "';</script>\n";
    $this->data .= "<script src='http://location.gl/location.js'></script>\n";
    $this->data .= "<a href='" . $this->link . "'>" . $this->name . "</a>\n";
    $this->data .= "<div id='errormsg'></div>\n";
    $this->data .= "<div id='location'></div>\n";
    $this->data .= "</body>\n</html>\n";

    return $this->data;

  }

}

$location = new Location();
$location->link($_GET['name'],$_SERVER['HTTP_REFERER']);
echo $location->data;

?>
