package Piperpal;

# Copyright (C) 2015 Ole Aamot
#
# Author: oka@oka.no
#
# Date: 2015-08-22T16:45:00+01
#
# Field: Incremental, prime number, sql, perl
#
# URL: https://www.piperpal.com/Piperpal.pm

#######################
# LOAD MODULES
#######################
use strict;

use lib '/home/4/p/piperpal/share/perl/5.10.1/';
use warnings FATAL => 'all';

use 5.008001;
use Encode qw();

use HTTP::Request::Common;
use DBI;
use CGI;
use LWP;
use WWW::Mechanize;
use JSON -support_by_pp;

use MediaWiki::API;

use Math::Round;

my @Piperpal;

BEGIN {
    @Piperpal = qw{input_location input_publisher lns_item lns_market_item is_valid is_paid initialize update_location select_location} 
}

sub get_locations {
    my $c = CGI->new;
    my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});
    $dbh->do ("SELECT DISTINCT name,glat,glon FROM yaylocation WHERE paid > '0' ORDER by name;");
    my $sth = $dbh->prepare ("SELECT DISTINCT name,glat,glon FROM yaylocation WHERE paid > '0' ORDER by name;");
    $sth->execute();
    while (my $ref = $sth->fetchrow_hashref()) {
	if ('GET' eq $c->request_method && $c->param('locationservicename') && $c->param('location')) {
	    if ($ref->{'name'} == $c->param('locationservicename')) {
		print "<option selected value=" . $ref->('glat') . "," . $ref->{'glon'} . ">" . $ref->{'name'} . "</option>";
	    } else {
		print "<option value=" . $ref->('glat') . "," . $ref->{'glon'} . ">" . $ref->{'name'} . "</option>";
		
	    } 
	}
    }
    $sth->finish();
    $dbh->disconnect();    
    return;
}

sub get_services {
    my $c = CGI->new;
    my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});
    $dbh->do ("SELECT DISTINCT service,name,glat,glon FROM piperpal WHERE paid > '0' ORDER by service;");
    my $sth = $dbh->prepare ("SELECT DISTINCT service,name,glat,glon FROM piperpal WHERE paid > '0' ORDER by service;");
    $sth->execute();
    if ('GET' eq $c->request_method && $c->param('service')) {
	while (my $ref = $sth->fetchrow_hashref()) {
	    if ($ref->{'service'} == $c->param('service')) {
		print "<option selected value=" . $ref->('glat') . "," . $ref->{'glon'} . ">" . $ref->{'service'} . "</option>";
	    } else {
		print "<option value=" . $ref->('glat') . "," . $ref->{'glon'} . ">" . $ref->{'service'} . "</option>";
		
	    } 
	}
    }
    $sth->finish();
    $dbh->disconnect();    
    return;
}
sub input_searchers {
    my @radiuses = (40075,30000,20000,10000,9000,8000,7000,6000,5000,4000,3000,2500,2000,1500,1250,1000,750,500,250,100,50,25,10,5,2,1);
    my $r;
    my $sth;
    my $dbh;
    my $c = CGI->new;
    my $servicep;
    if ($c->param('service')) {                                                          my $servicep = $c->param('service');
    } else {
	 my $servicep = "Books";
    }              
    print "<form onsubmit='updateGeo()' method='GET' action='https://www.piperpal.com/' id='formID'>";
    print '<table>';
    if ('GET' eq $c->request_method && $c->param('query')) {
	print '<tr><td><center><input size="30" type="text" autofocus class="input_form_custom" id="query" name="query" placeholder="Enter keyword" value="' . $c->param('query') . '" /></center></td></tr>';
#	$dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});                
#	$dbh->{'mysql_enable_utf8'} = 1;                                           
#	if ($c->param('query')) {                                                   
#	    $sth = $dbh->prepare ("SELECT name,location,service FROM piperpal WHERE (name LIKE '%" . $c->param('query') . "%' OR service LIKE '%" . $c->param('query') . "%') AND paid > '0' ORDER by modified DESC;");                                  $sth->execute();                                                        
#	    while (my $ref = $sth->fetchrow_hashref()) {
#	       print '<tr><td></td><td>';
#	       lns_item($ref->{'name'}, $ref->{'location'}, $ref->{'service'}, $ref->{'glat'}, $ref->{'glon'}, $ref->{'modified'}, $ref->{'created'}, $ref->{'distance_in_km'});
	#              print '</td></tr>';
#	#   }
	#       }
#:        $sth->finish();
# $ref->{'name'}, $ref->{'location'}, $ref->{'service'}, $ref->{'glat'}, $ref->{'glon'}, $ref->{'modified'}, $ref->{'created'}, $ref->{'distance_in_km'}
    } else {
	print '<tr><td><center><input size="30" type="text" class="input_form_custom" id="query" name="query" placeholder="Enter keyword" /></center></td>';
    }
    print '</tr><tr><td>';
    print '<select size="12" id="service" name="service">';
    # print '<option value="' . $servicep . '" selected>"' . $servicep . '"</option>';
    print '<option value="Restaurant">Restaurant</option><option value="Bar">Bar</option><option value="Concert">Concert</option><option value="Film">Film</option><option value="Books" selected>Books</option><option value="Health">Health</option><option value="Clothes">Clothes</option><option value="Food">Food</option><option value="Music">Music</option><option value="Electronics">Electronics</option><option value="Transport">Transport</option><option value="Rental">Rental</option>';
    print '</select></td>';
    print '<td><select size="12" name="radius" id="radius" class="input_form_custom">';
    foreach $r (@radiuses) {
	if ($c->param('radius')) {
	    if ($c->param('radius')==$r) {
		print "<option value='" . $r . "' selected>" . $r . " km</option>";
	    } else {
		print "<option value='" . $r . "'>" . $r . " km</option>";
	    }
	} else {
	    if ($r == 5) {
		print "<option value='" . $r . "' selected>" . $r . " km</option>";	    
	    } else {
		print "<option value='" . $r . "'>" . $r . " km</option>";
	    }
	}

    }
    print '</select></td>';
    print '<td>&nbsp;</td><td><input type="submit" id="search" name="search" value="Go" /></td></tr></table>';
    print "<span id='status'><input type='hidden' name='glat' /><input type='hidden' name='glon' /></span>\n";
    print "</form>";
    print "<br>";
    print '<p>Mobile Browsers: <a href="http://www.brave.com/">Brave</a>, <a href="https://www.mozilla.org/firefox">Firefox</a>, <a href="http://www.vivaldi.com/">Vivaldi</a></p>';;
    print '<p>Locations: <a href="https://www.piperpal.com/location/berkeley">Berkeley</a>, <a href="https://www.piperpal.com/location/london">London</a>, <a href="https://www.piperpal.com/location/nyc">New York City</a>, <a href="https://www.piperpal.com/location/paris">Paris</a>, <a href="https://www.piperpal.com/location/oslo">Oslo</a></p>';
    print '<p>Maps: <a href="http://maps.google.com/">Google Maps</a>, <a href="http://www.openstreetmap.org/">Open Street Map</a>, <a href="https://www.bing.com/maps/">Bing Maps</a>, <a href="https://maps.apple.com/">Apple Maps</a></p>';
    print '<h3>News</h3>';
    print '<p><b>2021</b></p>';    
    print '<p><i><a href="https://www.aamotsoftware.com/">Aamot Software</a>\'s second functional example of HTML with location tagging and fetching was stored on <a href="https://piperpal.com/google.html">https://piperpal.com/google.html</a></i></p>';
    print '<p><b>2020</b></p>';
    print '<p><i><a href="https://www.aamotsoftware.com/">Aamot Software</a>\'s Indexer for Piperpal written in the programming language Python can now recursively index location tags as specified on <a href="https://www.aamotsoftware.com/location.html">https://www.aamotsoftware.com/location.html</a> from web pages that are listed for such indexing on <a href="https://www.aamotsoftware.com/location-source.html">https://www.aamotsoftware.com/location-source.html</a></i></p>';
    print '<p><b>2015</b></p>';    
    print '<p><i><a href="https://www.aamotsoftware.com/">Aamot Software</a>\'s first practical example of location tagging with Piperpal was stored on <a href="https://piperpal.com/Google">https://piperpal.com/Google</a> at a lunch table with <a href="http://www.norvig.com/">Peter Norvig</a> in the Google Visitor Center in Mountain View, California in 2015 where he mentioned crowd sourcing of location tags with a neural network filter as future work.</i></p>';
    print '<p><i><a href="https://www.aamotsoftware.com/">Aamot Software</a>\'s presentation for <a href="http://research.google.com/">Google Research</a>: <a href="https://www.piperpal.com/doc/3.0/Piperpal-Location-aware-ContentTag.pdf">Location-aware Content Tag: &lt;location&gt;,&location markup</a></p>';
}

sub input_publisher {

    my $c = CGI->new;
    print "<tr><td width='60'>&nbsp;</td><td><form onsubmit='updateGeo()' id='welcomeForm' name='welcomeForm' action='https://www.piperpal.com/checkout.php' method='POST'>";
#    print '<span class="input_form">';
    print '<img src="img/Name_Icon.png" title="Name of Location-aware Content Tag" class="icon_form" height="70" width="70">';
    if ($c->param('query')) {
	print '<input type="text" id="name" class="input_form_custom" name="name" placeholder="Name" value=' . $c->param('query') . ' />';
    } else {
	print '<input type="text" id="name" class="input_form_custom" name="name" placeholder="Name" />';
    }
#    print '</span>';
    print '</td></tr><tr><td width=60>&nbsp;</td><td>';
#    print '<span class="input_form">';
    print '<img src="img/Location_Icon.png" title="URL of Location-aware Content Tag" class="icon_form" height="70" width="70">';
    print '<input type="text" id="location" class="input_form_custom" name="location" placeholder="https://">';
#    print '</span>';
    print '</td></tr><tr><td width=60>&nbsp;</td><td>';
 #   print '<span class="input_form">';
    print '<img src="img/Service_Icon.png" title="Service type of Location-aware Content Tag" class="icon_form" height="70" width="70">';
    print '</td></tr><tr><td width=60>';
    print '<input type="text" id="service" name="service" placeholder="Service">';
#    print '<span class="row row-condensed space-top-2 space-2">
#      <span class="col-sm-6">
#        <label for="simple-search-tourin" class="screen-reader-only">
#          Tour in
#        </label>
#        <input
#          id="simple-search-tourin"
#          type="text"
#          name="notBefore"
#          class="input-large tourin js-search-tourin"
#          placeholder="Not Before">
#      </span>
#      <span class="col-sm-6">
#        <label for="simple-search-tourout" class="screen-reader-only">
#          Tour out
#          </label>
#        <input
#          id="simple-search-tourout"
#          type="text"
#          name="notAfter"
#          class="input-large tourout js-search-tourout"
#          placeholder="Not After">
#      </span>
#    </span>';
  #  print '</span>';
    print "<span id='status_publisher'>\n";
#    print "<span class='input_form_custom'>";
    print "<input id='glat' class='input_form_custom' type='text' name='glat' placeholder='Latitude' size=35 />\n";
#    print "<span class='input_form_custom'>
    print "<input id='glon' class='input_form_custom' type='text' name='glon' placeholder='Longitude' size=35 />\n";
    print "</span>\n";
    print "<input type='hidden' name='c' value='INSERT' />\n";
    print '<span class="send_form">';
    print "<script src='https://checkout.stripe.com/checkout.js' class='stripe-button' data-key='pk_live_9UbKhDJJWaAFnMjYQTBA8I9i00H8Z5eMmL' data-amount='45' data-name='Aamot Software' data-description='Piperpal Entry ($0.45 USD)' data-image='/img/Location_Icon.png'></script>";
    # print '<input class="custom_send_button" type="submit" value="PAY WITH CARD">';
    print '</span>';
    print '</form>';
    print '</td></tr></table>';
    
#    print "<input type='hidden' name='c' value='INSERT' />\n";
#    print "<table cellpadding=5><tr>";
    #   print "<td><a href='https://www.piperpal.com/Piperpal'><img border=0 width=16 height=16 src='js-icon.png' /></td>";
  #  print "<td><input size=16 type=text name=name class=biginput id=name placeholder='Name' /></td>\n";
    # print "<td><input size=20 type=text name=location class=biginput id=location placeholder='https://' /></td>\n";
    #print "<td><input size=16 type=text name=service class=biginput id=service placeholder='Service' /></td>\n";
    #print "<span id='status'><input type='hidden' name='glat' placeholder='Latitude' size=16 /><input type='hidden' name='glon' placeholder='Longitude' size=16 /></span>\n";
    # print "<td><form action='https://www.piperpal.com/checkout.php' method='POST'><script src='httpss://checkout.stripe.com/checkout.js' class='stripe-button' data-key='pk_live_odDlc1NMTUJdPN4WC2VTLvvu' data-amount='10' data-name='Aamot Software' data-description='1 Hour Programming/Support (10 cent)' data-image='/128x128.png'></script></td>";
  #  print "</tr>\n";
   # print "</form>\n";


}

sub input_advertiser {

    my $c = CGI->new;
    print "<input type='hidden' name='c' value='INSERT' />\n";
    print "<table cellpadding=5><tr>";
    print "<td>Word</td><td><input type=text name=name class=biginput id=name placeholder='Name'></textarea></td>\n";
    print "<td><input size=20 type=text name=location class=biginput id=location placeholder='https://' /></td>\n";
    print "<td><input size=16 type=text name=service class=biginput id=service placeholder='Service' /></td>\n";
#    print '<td><span class="row row-condensed space-top-2 space-2">
#      <span class="col-sm-6">
#        <label for="simple-search-tourin" class="screen-reader-only">
#          Tour in
#        </label>
#        <input
#          id="simple-search-tourin"
#          type="text"
#          name="notBefore"
#          class="input-large tourin js-search-tourin"
#          placeholder="Not Before">
#      </span>
#      <span class="col-sm-6">
#        <label for="simple-search-tourout" class="screen-reader-only">
#          Tour out
#          </label>
#       <input
#          id="simple-search-tourout"
#          type="text"
#          name="notAfter"
#          class="input-large tourout js-search-tourout"
#          placeholder="Not After">
#      </span>
#    </span>';
    print "<span id='status'><input type='hidden' name='glat' placeholder='Latitude' size=16 /><input type='hidden' name='glon' placeholder='Longitude' size=16 /></span>\n";
    print "<td><form action='https://www.piperpal.com/checkout.php' method='POST'><script src='https://checkout.stripe.com/checkout.js' class='stripe-button' data-key='pk_test_EeI0KTkAXtAyW1ajpKukLZPm00ChuDtuP8' data-amount='4500' data-name='Aamot Software' data-description='1 Hour Programming/Support (45 USD)' data-image='/img/Location_Icon.png'></script></td>";
    print "</tr>\n";
    print "</form>\n";

}

sub lns_item {
    my $c = CGI->new;
    # my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});

    my $name = shift(@_);
    my $location = shift(@_);
    my $service = shift(@_);
    my $glat = shift(@_);
    my $glon = shift(@_);
    my $modified = shift(@_);
    my $created = shift(@_);
    my $distance = shift(@_);

    print "<tr><td width=60>&nbsp;</td><td>";
    print "<form onsubmit='updateGeo()' id='lnsForm' name='lnsForm' action='https://www.piperpal.com/checkout.php' method='POST'>";
    # print '<span class="name_form input_form">';
    print "<location name='" . $name . "' href='" . $location . "' service='" . $service . "' address='" . $location . "' lat='" . $glat . "' lon='" . $glon . "'><a href='" . $location . "'><h2>" . $name . "</h2></a></location>";    
#    print "<h3>" . nearest(0.1, $distance) . " km away</h3>";
#    print "<script type='text/javascript' lang='JavaScript'>";
#    print "var latpoint = document.getElementById('glat');";
#    print "var longpoint = document.getElementById('glon');";  
#    print "latpoint.value = position.coords.latitude;";
#    print "longpoint.value = position.coords.longitude;";
#    print "print latpoint.value;";
#    print "print longpoint.value;";
#    print "</script>";
#    print "111.045*DEGREES(ACOS(COS(RADIANS(latpoint))*COS(RADIANS(glat))*COS(RADIANS(longpoint)-RADIANS(glon))+SIN(RADIANS(latpoint))*SIN(RADIANS(glat))))";
    print "<table>";
    print "<tr><td width='60'>";
    print '<a href="/' . $name . '"><img src="/img/Name_Icon.png" title="Name of Location-aware Content Tag" height="70" width="70"></a>';
    print '</td><td><input type="text" id="' . $name .'" name="name" placeholder="Name" value="' . $name . '"></td></tr>';
  #  print '</span>';
#    print '<br>';
    #    print '<span class="website_form input_form">';
    print "<tr><td width='60'>";    
    print '<a href="' . $location . '"><img src="/img/Location_Icon.png" title="URL of Location-aware Content Tag" height="70" width="70"></a>';
    print '</td><td><input type="text" id="' . $name . '_location' . '" name="location" placeholder="https://" value="' . $location . '">';
 #   print '</span>';
 #    print '<br>';
    #    print '<span class="service_form input_form">';
    print "</td></tr><tr><td width='60'>";        
    print '<a href="/service/' . $service . '"><img src="/img/Service_Icon.png" title="Service type of Location-aware Content Tag" height="70" width="70"></a></td><td>';
    print '<input type="text" id="' . $name . '_service' . '" name="service" placeholder="Service" value="' . $service . '">';
    print "</td></tr><tr><td width='60'>";            
#    print '<span class="row row-condensed space-top-2 space-2">
#      <span class="col-sm-6">
#        <label for="simple-search-tourin" class="screen-reader-only">
#          Tour in
#        </label>
#        <input
#          id="datepicker"
#          type="text"
#          name="notBefore"
#          class="input-large tourin js-search-tourin"
#          placeholder="Not Before" value="' . $created . '">
#      </span>
#      <span class="col-sm-6">
#        <label for="simple-search-tourout" class="screen-reader-only">
#          Tour out
#          </label>
#        <input
#          id="datepicker"
#          type="text"
#          name="notAfter"
#          class="input-large tourout js-search-tourout"
#          placeholder="Not After" value="' . $modified . '">
#      </span>
#    </span>';
#    print '</span>';
    print "</td></tr><tr><td width='60'>";            
    print "<span id='status_lns'>\n";
#    print "<span class='service_form input_form'>";
    print "<img src='/img/Latitude_Icon.png' alt='Icon2' height='70' width='70'></td><td><input id='" . $name . "_glat' type='text' name='glat' placeholder='Latitude' size=35 value='" . $glat . "' />\n";
    print "</td></tr><tr><td width='60'>";

#</span>\n";

#    print "<span class='input_form_custom'><img src='img/Latitude_Icon.png' alt='Icon2' class='icon_form' height='70' width='70'><input id='" . $name . "_glat' class='input_form_custom' type='text' name='glat' placeholder='Latitude' size=32 value='" . $glat . "' /></span>\n";

    #   print "<span class='service_form input_form'>
    print "</td></tr><tr><td width='60'>";    
    print "<img src='/img/Longitude_Icon.png' alt='Icon2' height='70' width='70'></td><td><input id='" . $name . "_glon' type='text' name='glon' placeholder='Longitude' size=35 value='" . $glon . "' />\n";
#</span>\n";

 #   print "<span class='input_form_custom'><img src='img/Longitude_Icon.png' alt='Icon2' class='icon_form' height='70' width='70'><input id='" . $name . "_glon' class='input_form_custom' type='text' name='glon' placeholder='Longitude' size=32 value='" . $glon . "' /></span>\n";

    print "</td></tr><tr><td width='60'>";                
    print "<input type='hidden' name='c' value='INSERT' />\n";
#    print '<span class="send_form">';
#    print "<script src='https://checkout.stripe.com/checkout.js' class='stripe-button' data-key='pk_live_dg4Qj9EUNdnBicNW40nNoEJh' data-amount='100' data-name='Aamot Software' data-description='Piperpal Entry (1 dollar)' data-image='/img/Location_Icon.png'></script>";
    # print '<input class="custom_send_button" type="submit" value="PAY WITH CARD">';
#    print '</span>';
    print "</td></tr>";
    print "</table>";
    # print "<form action='http://www.piperpal.com/checkout.php?name=" . $name . "&service=" . $service . "' method='POST'>";
    # print '<span class="name_form input_form">';
    # print '<img src="img/icon1.png" alt="Icon1" style="margin-right:27px" class="icon_form" height="70" width="70">';
    # print '<input type="text" value="' . $name . '" id="' . $name . '" class="input_form_custom" name="name" placeholder="' . $name . '">';
    # print '</span>';
    # print '<br>';
    # print '<span class="website_form input_form">';
    # print '<img src="img/icon2.png" alt="Icon2" class="icon_form" height="70" width="70">';
    # print '<input type="text" value="' . $location . '" id="location" class="input_form_custom" name="location" placeholder="' . $location . '">';
    # print '</span>';
    # print '<br>';
    # print '<span class="service_form input_form">';
    # print '<img src="img/icon3.png" alt="Icon3" class="icon_form" height="70" width="70">';
    # print '<input type="text" value="' . $service . '" id="service" class="input_form_custom" name="service" placeholder="' . $service . '">';
    # print '</span>';
    # print "<span id='status_lns'>\n";
    # print "<span class='service_form input_form'>";
    # print '<img src="img/icon2.png" alt="Icon2" class="icon_form" height="70" width="70">';
    # print "<input id='glat' value='" . $glat . "' class='input_form_custom' type='text' name='glat' placeholder='" . $glat . "' size=16 /></span>\n";
    # print "<span class='service_form input_form'>";
    # print '<img src="img/icon2.png" alt="Icon2" class="icon_form" height="70" width="70">';
    # print "<input id='glon' value='" . $glon . "' class='input_form_custom' type='text' name='glon' placeholder='" . $glon . "' size=16 /></span>\n";
    # print "</span>\n";
    # print "<input type='hidden' name='c' value='INSERT' />\n";
    # print '<span class="send_form_item">';
    # print "<script src='https://checkout.stripe.com/checkout.js' class='stripe-button' data-key='pk_live_odDlc1NMTUJdPN4WC2VTLvvu' data-amount='500' data-name='Aamot Software' data-description='" . $service . " (5 USD)' data-image='/img/icon2.png'></script>";
    # print '</span>';
    # print '</form>';

}

sub lns_market_item {
    my $c = CGI->new;
    # my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});

    my $name = shift(@_);
    my $location = shift(@_);
    my $service = shift(@_);
    my $glat = shift(@_);
    my $glon = shift(@_);
    my $modified = shift(@_);
    my $created = shift(@_);
    my $distance = shift(@_);

    print "<tr><td width=60>&nbsp;</td><td>";
    print "<form onsubmit='updateGeo()' id='lnsForm' name='lnsForm' action='https://www.piperpal.com/checkout.php' method='POST'>";
    # print '<span class="name_form input_form">';
    print "<location name='" . $name . "' href='" . $location . "' lat='" . $glat . "' lon='" . $glon . "'><a href='" . $location . "'><h2>" . $name . "</h2></a></location>";    
    print "<h3>" . nearest(0.1, $distance) . " km away</h3>";
    print "<table>";
    print "<tr><td width='60'>";
    print '<a href="/' . $name . '"><img src="/img/Name_Icon.png" title="Name of Location-aware Content Tag" height="70" width="70"></a>';
    print '</td><td><input type="text" id="' . $name .'" name="name" placeholder="Name" value="' . $name . '"></td></tr>';
  #  print '</span>';
#    print '<br>';
    #    print '<span class="website_form input_form">';
    print "<tr><td width='60'>";    
    print '<a href="' . $location . '"><img src="/img/Location_Icon.png" title="URL of Location-aware Content Tag" height="70" width="70"></a>';
    print '</td><td><input type="text" id="' . $name . '_location' . '" name="location" placeholder="https://" value="' . $location . '">';
 #   print '</span>';
 #    print '<br>';
    #    print '<span class="service_form input_form">';
    print "</td></tr><tr><td width='60'>";        
    print '<a href="/service/' . $service . '"><img src="/img/Service_Icon.png" title="Service type of Location-aware Content Tag" height="70" width="70"></a></td><td>';
    print '<input type="text" id="' . $name . '_service' . '" name="service" placeholder="Service" value="' . $service . '">';
    print '<input type="hidden" id="paid_' . $name . '_service' . '" name="paid" placeholder="paid" value="45">';
    print "</td></tr><tr><td width='60'></td><td>";            
#    print '<span class="row row-condensed space-top-2 space-2">
#      <span class="col-sm-6">
#        <label for="simple-search-tourin" class="screen-reader-only">
#          Tour in
#        </label>
#        <input
#          id="datepicker"
#          type="text"
#          name="notBefore"
#          class="input-large tourin js-search-tourin"
#          placeholder="Not Before" value="' . $created . '">
#      </span>
#      <span class="col-sm-6">
#        <label for="simple-search-tourout" class="screen-reader-only">
#          Tour out
#          </label>
#        <input
#          id="datepicker"
#          type="text"
#          name="notAfter"
#          class="input-large tourout js-search-tourout"
#          placeholder="Not After" value="' . $modified . '">
#      </span>
#    </span>';
#    print '</span>';
    print "</td></tr><tr><td width='60'>";            
    print "<span id='status_lns'>\n";
#    print "<span class='service_form input_form'>";
    print "<img src='/img/Latitude_Icon.png' alt='Icon2' height='70' width='70'></td><td><input id='" . $name . "_glat' type='text' name='glat' placeholder='Latitude' size=35 value='" . $glat . "' />\n";
#</span>\n";

#    print "<span class='input_form_custom'><img src='img/Latitude_Icon.png' alt='Icon2' class='icon_form' height='70' width='70'><input id='" . $name . "_glat' class='input_form_custom' type='text' name='glat' placeholder='Latitude' size=32 value='" . $glat . "' /></span>\n";

    #   print "<span class='service_form input_form'>
    print "</td></tr><tr><td width='60'>";    
    print "<img src='/img/Longitude_Icon.png' alt='Icon2' height='70' width='70'></td><td><input id='" . $name . "_glon' type='text' name='glon' placeholder='Longitude' size=35 value='" . $glon . "' />\n";
#</span>\n";

 #   print "<span class='input_form_custom'><img src='img/Longitude_Icon.png' alt='Icon2' class='icon_form' height='70' width='70'><input id='" . $name . "_glon' class='input_form_custom' type='text' name='glon' placeholder='Longitude' size=32 value='" . $glon . "' /></span>\n";                             
    print "</td></tr><tr><td width='60'>";
    print "<img src='/img/Email_Icon.png' alt='Icon2' height='70' width='70'></td><td>";
    print "<input id='" . $name . "_expire' type='text' name='expire' placeholder='' size=35 value='" . $modified . "' />\n";
    print "</td></tr><tr><td width='60'>";                
    print "<img src='/img/Phone_Icon.png' alt='Icon2' height='70' width='70'></td><td>";       
    print "<input id='" . $name . "_expire' type='text' name='expire' placeholder='' size=35 value='" . $created . "' />\n";                                                                     print "</td></tr><tr><td width='60'>";
    print "<input type='hidden' name='c' value='INSERT' />\n";
#    print '<span class="send_form">';
#    print "<script src='https://checkout.stripe.com/checkout.js' class='stripe-button' data-key='pk_live_dg4Qj9EUNdnBicNW40nNoEJh' data-amount='100' data-name='Aamot Software' data-description='Piperpal Entry (1 dollar)' data-image='/img/Location_Icon.png'></script>";
    # print '<input class="custom_send_button" type="submit" value="PAY WITH CARD">';
#    print '</span>';
    print "</td></tr>";
    print "</table>";
    # print "<form action='http://www.piperpal.com/checkout.php?name=" . $name . "&service=" . $service . "' method='POST'>";
    # print '<span class="name_form input_form">';
    # print '<img src="img/icon1.png" alt="Icon1" style="margin-right:27px" class="icon_form" height="70" width="70">';
    # print '<input type="text" value="' . $name . '" id="' . $name . '" class="input_form_custom" name="name" placeholder="' . $name . '">';
    # print '</span>';
    # print '<br>';
    # print '<span class="website_form input_form">';
    # print '<img src="img/icon2.png" alt="Icon2" class="icon_form" height="70" width="70">';
    # print '<input type="text" value="' . $location . '" id="location" class="input_form_custom" name="location" placeholder="' . $location . '">';
    # print '</span>';
    # print '<br>';
    # print '<span class="service_form input_form">';
    # print '<img src="img/icon3.png" alt="Icon3" class="icon_form" height="70" width="70">';
    # print '<input type="text" value="' . $service . '" id="service" class="input_form_custom" name="service" placeholder="' . $service . '">';
    # print '</span>';
    # print "<span id='status_lns'>\n";
    # print "<span class='service_form input_form'>";
    # print '<img src="img/icon2.png" alt="Icon2" class="icon_form" height="70" width="70">';
    # print "<input id='glat' value='" . $glat . "' class='input_form_custom' type='text' name='glat' placeholder='" . $glat . "' size=16 /></span>\n";
    # print "<span class='service_form input_form'>";
    # print '<img src="img/icon2.png" alt="Icon2" class="icon_form" height="70" width="70">';
    # print "<input id='glon' value='" . $glon . "' class='input_form_custom' type='text' name='glon' placeholder='" . $glon . "' size=16 /></span>\n";
    # print "</span>\n";
    # print "<input type='hidden' name='c' value='INSERT' />\n";
    # print '<span class="send_form_item">';
    # print "<script src='https://checkout.stripe.com/checkout.js' class='stripe-button' data-key='pk_live_odDlc1NMTUJdPN4WC2VTLvvu' data-amount='500' data-name='Aamot Software' data-description='" . $service . " (5 USD)' data-image='/img/icon2.png'></script>";
    # print '</span>';
    # print '</form>';

}

sub is_valid {
    my $location = shift;
    my $ua = LWP::UserAgent->new;
    my $result = $ua->request(GET $location);
    print "is_valid.\n";
    print_r($result);
    return 0 if ($result==0);
    return 1 if (($result==1)||($result==2));
}

sub is_paid {
    my $c = CGI->new;
    my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});
    if ('GET' eq $c->request_method && $c->param('paid') eq "1" && $c->param('query') && $c->param('location')) {
	$dbh->do ("UPDATE piperpal SET paid = paid + 1, modified = NOW() WHERE name = '" . $c->param('query') . "' and location = '" . $c->param('location') . "';");
    }
    return;
}

sub delete_location {
    my $c = CGI->new;
    my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});
    if ('GET' eq $c->request_method && $c->param('c') eq "DELETE" && $c->param('query') && $c->param('location') && $c->param('service')) {
	$dbh->do ("DELETE FROM piperpal WHERE name = '" . $c->param('query') . "' AND location = '" . $c->param('location') . "' AND service = '" . $c->param('service') . "';");
    }
    return;
}

sub insert_location {
    my $c = CGI->new;
    my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});
    if ('GET' eq $c->request_method && $c->param('c') eq "INSERT" && $c->param('query') && $c->param('location') && $c->param('service')) {
	# $dbh->do ("INSERT IGNORE INTO piperpal (name, location, service, glat, glon, modified, created, paid, token, type, email) VALUES ('" . $c->param('query') . "','" . $c->param('location') . "','" . $c->param('service') . "','" . $c->param('glat') . "','" . $c->param('glon') . "', NOW(), NOW(), 1, '" . $c->param('stripeToken') . "', '" . $c->param('stripeTokenType') . "','" . $c->param('stripeEmail') . "') ON DUPLICATE KEY UPDATE modified = NOW();");
    }
    return;
}

sub insert_wikipedia {
    my $c = CGI->new;
        print "<script>";
    print "getWikipedia();";
#function getcontent(a,b) {\n";
#    print "jQuery(function($) {\n";
#    print "$.getJSON('http://api.geonames.org/findNearbyWikipediaJSON?formatted=true&lat='+ position.coords.latitude +'&lng='+ position.coords.longitude +'&username=username&style=full&lang=de&wikipediaUrl&thumbnailImg', function(json) {\n";
#    print "for(var i = 0; i < json.geonames.length; i++)\n";
#    print "{\n";
#    print "$(\"#wikipedia\").prepend('<span style=\"font-family: geneva, arial, helvetica, sans-serif;\"><br><br><img src=\"wikilogo.gif\"><br>' + json.geonames[i].summary + '<br><a href=\"http://'+ json.geonames[i].wikipediaUrl +'\" target=\"_blank\">'+ json.geonames[i].wikipediaUrl +'</a><br></span>');\n";
#    print "}\n";
#    print "});})\n";
#    print "}";
#    print "getcontent(a,b);";
#    print "</script>";
    print "<span id='wikipedia'></span>\n";
    # print 
    # 	function getcontent(a,b) {
    # 	    jQuery(function($) {
    # 		$.getJSON('http://api.geonames.org/findNearbyWikipediaJSON?formatted=true&lat='+ a +'&lng='+ b +'&username=username&style=full&lang=de&wikipediaUrl&thumbnailImg', function(json){

    # 		    for(var i = 0; i < json.geonames.length; i++)
    # 		    {
    # 			$("#tweet").prepend('<span style="font-family: geneva, arial, helvetica, sans-serif;"><br><br><img src="wikilogo.gif"><br>' + json.geonames[i].summary + '<br><a href="http://'+ json.geonames[i].wikipediaUrl +'" target="_blank">'+ json.geonames[i].wikipediaUrl +'</a><br></span>');
    # 			  }
    # 		    }); }); }

    # my $mw = MediaWiki::API->new();
    # $mw->{config}->{api_url} = 'http://en.wikipedia.org/w/api.php';


    # if ($c->request_method && $c->param('glat') && $c->param('glon')) {
    # 	my $url = "https://en.wikipedia.org/w/api.php?action=query&prop=extracts|coordinates|pageimages|pageterms&colimit=50&piprop=thumbnail&pithumbsize=144&pilimit=50&wbptterms=description&generator=geosearch&ggscoord=" . $c->param('glat') . "|" . $c->param('glon') . "&ggsradius=" . $c->param('radius') . "&ggslimit=50"; # &format=json";
    # 	print "<a href='" . $url . "'>" . $url . "</a>";
    # 	my ($json_url) = $url;
    # 	my $browser = WWW::Mechanize->new();
    # 	eval{
    # 	    # download the json page:
    # 	    print "Getting json $json_url\n";
    # 	    $browser->get( $json_url );
    # 	    my $content = $browser->content();
    # 	    my $json = new JSON;
	    
    # 	    # these are some nice json options to relax restrictions a bit:
    # 	    my $json_text = $json->allow_nonref->utf8->relaxed->escape_slash->loose->allow_singlequote->allow_barekey->decode($content);
	    
    # 	    # iterate over each episode in the JSON structure:
    # 	    my $page_num = 1;
    # 	    foreach my $page(@{$json_text->{query}->{pages}->{53445}}) {
    # 		my %ep_hash = ();
    # 		$ep_hash{title} = "Page $page_num: $page->{title}";
    # 		$ep_hash{description} = $page->{description};
    # 		$ep_hash{url} = "http://en.wikipedia.org/wiki/" . $page->{id};
    # 		$ep_hash{publish_date} = $page->{airdate};
    # 		$ep_hash{thumbnail_url} = $page->{thumbnail}->{source};
		
    # 		# print page information:
    # 		while (my($k, $v) = each (%ep_hash)){
    # 		    print "$k => $v\n";
    # 		}
    # 		print "\n";
		
    # 		$page_num++;
    # 	    }
    # 	};
    # 	# catch crashes:
    # 	if($@){
    # 	    print "[[JSON ERROR]] JSON parser crashed! $@\n";
    # 	}
    # }
    print "</script>\n";
}

sub insert_publisher {
    my $c = CGI->new;
    my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});
    my $q;
    if ($c->request_method && $c->param('c') && $c->param('query') && $c->param('location') && $c->param('service')) {
	my @tags = ($c->param('query') =~ m/&(\w+)/g);
	my $name = $c->param('query');

	foreach my $tag (@tags)
	{
	    next unless $tag =~ m/[a-zA-záäåāąæćčéēėęģíīįķļłńņðóöøōőŗśšúüūűųýźżžþ]/i;  
	    $name =~ s{&$tag}{<a href="https://www.piperpal.com/register.cgi?name=$tag">&$tag</a>}g;
	}
	$q = "INSERT INTO piperpal (name, location, service, glat, glon, modified, created, paid, token, type, email) VALUES ('" . $name . "','" . $c->param('location') . "','" . $c->param('service') . "','" . $c->param('glat') . "','" . $c->param('glon') . "', NOW(), NOW(), 1, '" . $c->param('stripeToken') . "', '" . $c->param('stripeTokenType') . "','" . $c->param('stripeEmail') . "') ON DUPLICATE KEY UPDATE modified = NOW();";
	print $q;
	$dbh->do ($q);
    } else {
	# print "Error in inserting data.";
    }
    return;
}

sub update_location {
    my $c = CGI->new();
    my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});
#    $dbh->{'mysql_enable_utf8'} = 1;
    if ($c->param('query')) {
	my $sth = $dbh->prepare ("SELECT name,location,service FROM piperpal WHERE (name LIKE '%" . $c->param('query') . "%' OR service LIKE '%" . $c->param('query') . "%') AND paid > '0' ORDER by modified DESC;");
	$sth->execute();
	while (my $ref = $sth->fetchrow_hashref()) {
	    my @tags = ($ref->{'name'} =~ m/&(\w+)/g);
		foreach my $tag (@tags) {
		    next unless $tag =~ m/[a-zA-záäåāąæćčéēėęģíīįķļłńņðóöøōőŗśšúüūűųýźżžþ]/i;  
		    print "<form method='POST' action='adverts.cgi'>";
		    print "<td>Word:</td><td><input type='name' name='" . $tag . "' value='" . $tag . "' /></td>";
		    print "</form>\n";
		}
	}
	$sth->finish();
    }
    $dbh->disconnect();    
    return;
}

sub insert_advertiser {

    my $c = CGI->new;
    my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});
#    $dbh->{'mysql_enable_utf8'} = 1;
    if ('GET' eq $c->request_method && $c->param('c') eq "INSERT" && $c->param('query') && $c->param('location') && $c->param('service')) {
	
	my $sth = $dbh->prepare ("UPDATE piperpal SET location = '" . $c->param('location') . "', name = '" . $c->param('query') . "', service = '" . $c->param('service') . "' = '" . $c->param('location') . "', service = '" . $c->param('service') . " WHERE name = '" . $c->param('query') . "';");
	$sth->execute();
	while (my $ref = $sth->fetchrow_hashref()) {
	    if (is_paid($ref->{'location'})) {
		print "<pre>UPDATE:The location of " . $ref->{'name'} . " " . $ref->{'location'} . " is a paid piperpal location.</pre>\n";
		$dbh->do ("UPDATE piperpal SET location = 'https://www.piperpal.com/index.cgi?name=" . $ref->{'name'} . "&service" . $ref->{'service'} . "&location=" . ($ref->{'location'}) . "&paid=1', paid = paid + 1, modified = NOW() WHERE name = '" . $ref->{'name'} . "';");
		print "<pre>Paid +1</pre>\n";
	    } else {
		print "<pre>UPDATE:The location of " . $ref->{'name'} . " " . $ref->{'location'} . " is not a paid piperpal location.</pre>\n";
		$dbh->do ("UPDATE piperpal SET location = 'https://www.piperpal.com/index.cgi?name=" . $ref->{'name'} . "&service" . $ref->{'service'} . "&location=" . ($ref->{'location'}) . "&paid=-1', paid = paid - 1, modified = NOW() WHERE name = '" . $ref->{'name'} . "';");
		print "<pre>Paid -1</pre>\n";
	    }
	$sth->finish();
	}
    }
    $dbh->disconnect();    
    return;
}

sub select_publisher {
    my $c = CGI->new;
    my $q;
    my $r;
    my $s;
    my $latitude;
    my $longitude;
    my $fp;
#    my $fn = "/home/4/p/piperpal/pull.csv";
#    my $string = "pull:" . $ENV{'REMOTE_ADDR'} . ":" . $q . "\n";    
    if ($c->param('query')) {
	$q = $c->param('query');
#	open(FH, '>', $fn) or die $!;
#	print FH $string;
#	print $fp;
#	close($fp);
    } else {
	$q = "GoogleVisitorCenter";
    }
    if ($c->param('service')) {
	$s = $c->param('service');
    } else {
	$s = 'Search';
    }
    if ($c->param('radius')) {
	$r = $c->param('radius');
    } else {
	$r = 1;
    }
    if ($c->param('glat')) {
	$latitude = $c->param('glat');
    } else {
	# FIXME: READ LATITUDE FROM JAVASCRIPT
	$latitude = $ENV{'position.coords.latitude'};
# 37.8790153;
    }
    if ($c->param('glon')) {
	$longitude = $c->param('glon');
    } else {
	# FIXME: READ LONGITUDE FROM JAVASCRIPT
	$longitude = $ENV{'position.coords.longitude'};
#-122.26242529999999;
    }
    my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});
#    $dbh->{'mysql_enable_utf8'} = 1;
    my $query = "SELECT name,location,service,glat,glon,modified,created,paid,token,type,email FROM piperpal WHERE (name LIKE '%" . $c->param('query') . "%' OR service LIKE '%" . $c->param('query') . "%') AND paid > '0' ORDER by modified DESC;";
    $query = "SELECT DISTINCT id,name,service,location,modified,created,glat,glon,paid,token,type,email,111.045*DEGREES(ACOS(COS(RADIANS(latpoint))*COS(RADIANS(glat))*COS(RADIANS(longpoint)-RADIANS(glon))+SIN(RADIANS(latpoint))*SIN(RADIANS(glat)))) AS distance_in_km FROM piperpal JOIN (SELECT  " . $latitude . "  AS latpoint, " . $longitude . " AS longpoint) AS p ON 1=1 WHERE paid > '0' AND (name LIKE '%" . $c->param('query') . "%' AND service LIKE '%" . $s . "%') HAVING distance_in_km < " . $r . " ORDER BY distance_in_km ASC, modified DESC";
    my $sth = $dbh->prepare ($query);
    $sth->execute();
    print "<table>";
    while (my $ref = $sth->fetchrow_hashref()) {
	print "<tr><td width='60'>&nbsp;</td><td>";
	print "<form onsubmit='updateGeo()' method='GET' action='https://www.piperpal.com/cft/s/' id='formID'>";		
	lns_market_item($ref->{'name'}, $ref->{'location'}, $ref->{'service'}, $ref->{'glat'}, $ref->{'glon'}, $ref->{'modified'}, $ref->{'created'}, $ref->{'distance_in_km'});
	print "<input type='submit' value='Buy Location for " . $ref->{'name'} . "'/>";	
	print "</form>";
	print "</td></tr>";
    }
    print "</table>";
    $sth->finish();
    $dbh->disconnect();
    return;
}

sub select_location {
    my $c = CGI->new;
    my $q;
    my $r;
    my $latitude;
    my $longitude;
    my $fp;
#    my $fn = "/home/4/p/piperpal/pull.csv";
#    my $string = "pull:" . $ENV{'REMOTE_ADDR'} . ":" . $q . "\n";    
    if ($c->param('query')) {
	$q = $c->param('query');
#	open(FH, '>', $fn) or die $!;
#	print FH $string;
#	print $fp;
#	close($fp);
    } else {
	$q = "GoogleVisitorCenter";
    }
    if ($c->param('radius')) {
	$r = $c->param('radius');
    } else {
	$r = 1;
    }
    if ($c->param('glat')) {
	$latitude = $c->param('glat');
    } else {
	$latitude = 37.8790153;
    }
    if ($c->param('glon')) {
	$longitude = $c->param('glon');
    } else {
	$longitude = -122.26242529999999;
    }
    my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});
#    $dbh->{'mysql_enable_utf8'} = 1;

#    my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});
#    $dbh->{'mysql_enable_utf8'} = 1;
    #my $sth = $dbh->prepare ("SELECT DISTINCT id,name,service,location,modified,created,glat,glon,paid,token,type,email,111.045*DEGREES(ACOS(COS(RADIANS(latpoint))*COS(RADIANS(glat))*COS(RADIANS(longpoint)-RADIANS(glon))+SIN(RADIANS(latpoint))*SIN(RADIANS(glat)))) AS distance_in_km FROM piperpal JOIN (SELECT  " . $latitude . "  AS latpoint, " . $longitude . " AS longpoint) AS p ON 1=1 WHERE paid > '0' AND name LIKE '%" . $q . "%' HAVING distance_in_km < " . $r . " ORDER BY distance_in_km");
    my $sth = $dbh->prepare ("SELECT DISTINCT name,location,service,glat,glon,modified,created,paid,token,type,email FROM piperpal WHERE modified < NOW() and created > NOW() AND paid > '0' ORDER by modified DESC, name ASC;");
    $sth->execute();
    while (my $ref = $sth->fetchrow_hashref()) {
	#print "<h3><a href='https://piperpal.com/" . $ref->{'name'}  . "'>" . $ref->{'name'} . "</a></h3>";
	print "<form onsubmit='updateGeo()' method='GET' action='https://www.piperpal.com/cft/s/' id='formID'>";	
	lns_market_item($ref->{'name'}, $ref->{'location'}, $ref->{'service'}, $ref->{'glat'}, $ref->{'glon'}, $ref->{'modified'}, $ref->{'created'});
	print "<input type='submit' value='Buy Location for " . $ref->{'name'} . "'/>";
	print "</form>\n";
    }
    $sth->finish();    
    $dbh->disconnect();
    return;
}

sub select_market {
    my $c = CGI->new;
    my $q;
    my $r;
    my $latitude;
    my $longitude;
    if ($c->param('query')) {
	$q = $c->param('query');
    } else {
	$q = "GoogleVisitorCenter";
    }
    if ($c->param('radius')) {
	$r = $c->param('radius');
    } else {
	$r = 1;
    }
    if ($c->param('glat')) {
	$latitude = $c->param('glat');
    } else {
	$latitude = 37.8790153;
    }
    if ($c->param('glon')) {
	$longitude = $c->param('glon');
    } else {
	$longitude = -122.26242529999999;
    }
    my $dbh = DBI->connect("DBI:mysql:database=piperpal;host=piperpal.mysql.domeneshop.no", "piperpal", "xxxxxxxx", {'RaiseError' => 1});
#    $dbh->{'mysql_enable_utf8'} = 1;
    my $query = "SELECT name,location,service,glat,glon,modified,created,paid,token,type,email FROM piperpal WHERE (name LIKE '%" . $q . "%' OR service LIKE '%" . $q . "%') AND paid > '0' ORDER by modified;";
    $query = "SELECT DISTINCT id,name,service,location,modified,created,glat,glon,paid,token,type,email,111.045*DEGREES(ACOS(COS(RADIANS(latpoint))*COS(RADIANS(glat))*COS(RADIANS(longpoint)-RADIANS(glon))+SIN(RADIANS(latpoint))*SIN(RADIANS(glat)))) AS distance_in_km FROM piperpal JOIN (SELECT  " . $latitude . "  AS latpoint, " . $longitude . " AS longpoint) AS p ON 1=1 WHERE paid > '0' AND (name LIKE '%" . $q . "%' OR service LIKE '%" . $q . "%') HAVING distance_in_km < " . $r . " ORDER BY distance_in_km,modified";
    my $sth = $dbh->prepare ($query);
    $sth->execute();
#    print "</td></tr></table><table><tr><td>";
    while (my $ref = $sth->fetchrow_hashref()) {
	print "<form onsubmit='updateGeo()' id='lnsForm' name='lnsForm' action='https://www.piperpal.com/checkout.php' method='POST'>";
#	print "<table><tr><td width='60'>&nbsp;</td><td>";
	lns_market_item($ref->{'name'}, $ref->{'location'}, $ref->{'service'}, $ref->{'glat'}, $ref->{'glon'}, $ref->{'modified'}, $ref->{'created'}, $ref->{'distance_in_km'});
	print "<input type='submit' value='Buy Location for " . $ref->{'name'} . "'/>";	
#	print "</td></tr></table>";
	print "</form>";
    }
    print "</td></tr></table>";
    $sth->finish();    
    $dbh->disconnect();
    return;
}
