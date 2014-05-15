<?php

class Location {

  public $data;
  public $link;
  public $name;

  function link($name, $link) {

    $this->name = $name;
    $this->link = $link;

    if ($this->name == NULL) $this->name = "name";
    if ($this->link == NULL) $this->link = "http://location.gl/link";

    $this->data .= "<html>\n<head>\n<title>Location Name Service: " . $this->name . "</title>\n";
    $this->data .= "</head>\n";
    $this->data .= "</head>\n";
    $this->data .= "<body>\n";
    $this->data .= "<div id='location'></div>\n";
    $this->data .= "<a href='" . $this->link . "'>" . $this->name . "</a>\n";
    $this->data .= "</body>\n</html>\n";
    return $this->data;

  }

}

$location = new Location();
$location->link($_GET['name'],$_SERVER['HTTP_REFERER']);
echo $location->data;

?>
