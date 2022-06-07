
<?php
$db = mysqli_connect("piperpal.mysql.domeneshop.no","piperpal","xxxxxxxxx","piperpal");
$query = "SELECT DISTINCT id,name,service,location,modified,created,glat,glon,paid,token,type,email,111.045*DEGREES(ACOS(COS(RADIANS(latpoint))*COS(RADIANS(glat))*COS(RADIANS(longpoint)-RADIANS(glon))+SIN(RADIANS(latpoint))*SIN(RADIANS(glat)))) AS distance_in_km FROM piperpal JOIN (SELECT  " . $_POST['glat'] . "  AS latpoint, " . $_POST['glon'] . " AS longpoint) AS p ON 1=1 WHERE name = '%" . $_POST['name'] . "%' OR service LIKE '%" . $_POST['service'] . "%' HAVING distance_in_km < " . $_POST['radius'] . " ORDER BY distance_in_km ASC, modified DESC";
$result = $db->query($query);
$num_coords = mysqli_num_rows($result);
if ($num_coords == 0) {
    print "<form onsubmit='updateGeo()' id='lnsForm' name='lnsForm' action='https://www.piperpal.com/checkout.php' method='POST'>";
    print "<input type='hidden' name='c' value='INSERT' />\n";
    print "<table cellpadding=5><tr>";
    print "<td><a href='http://piperpal.com/" . $_POST['name'] . "'><img border=0 width=16 height=16 src='/js-icon.png' /></td>";
    print "<td><input size=16 type=text name=name class=biginput id=name placeholder='Name' value='" . $_POST['name'] . "' /></td>\n";
    print "<td><input size=20 type=text name=location class=biginput id=location placeholder='http://' value='" . $_SERVER['HTTP_REFERER'] . "' /></td>\n";
    print "<td><input size=16 type=text name=service class=biginput id=service placeholder='Service' value='" . $_POST['service'] . "' /></td>\n";
    print "<div id='status'><input type='hidden' name='glat' placeholder='Latitude' size=16 value='" . $_POST['glat'] . "' /><input type='hidden' name='glon' placeholder='Longitude' size=16 value='" . $_POST['glon'] . "' /></div>\n";
    print "<td><form action='' method='POST'><script src='https://checkout.stripe.com/checkout.js' class='stripe-button' data-key='pk_live_9UbKhDJJWaAFnMjYQTBA8I9i00H8Z5eMmL' data-amount='43' data-name='Aamot Software' data-description='Piperpal &" . $_POST['name'] . "%" . $_POST['service'] . " (USD 0.43)' data-image='/128x128.png'></script></td>";
    print "</tr>\n";
    print "</form>\n";
} else {
    print "var locations = '{ \"locations\" : [' + '";
  while($object = mysqli_fetch_object($result)) {
    print "    { id: '" . $object->id . "', name: '" . $object->name . "', service: '" . $object->service . "', location: '" . $object->location . "', modified: '" . $object->modified . "', created: '" . $object->created . "', glat: '" . $object->glat . ", glon: '" . $object->glon . ", distance: '" . $object->distance_in_km . "', paid: '" . $object->paid . "', token: '" . $object->token . "', type: '" . $object->type . "', email: '" . $object->email . "' }\n";
  }
  print "]};";
}
?>
