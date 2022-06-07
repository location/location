#!/usr/bin/perl

# Copyright (C) 2018  Ole Aamot Software
#
# Author: ole@aamotsoftware.com
#
# Date: 2018-04-06T06:40:00+01
#
# Field: Incremental, location, sql, perl
#
# URL: https://www.piperpal.com/market/index.cgi

use strict;
use warnings;
use DBI;
use CGI qw/:standard/;
use CGI::Cookie;
use lib '../..';
use Piperpal;

my $c = CGI->new;
print "Content-Type: text/html\n\n";
print <<EOF;
<!DOCTYPE html>
<html lang="en">
    <head>

    <!--[if IE]><![endif]-->
    <meta charset="utf-8">

    <!--[if IE 8]>
      <link href="/css/common_o2.1_ie8-91981ea8f3932c01fab677a25d869e49.css" media="all" rel="stylesheet" type="text/css" />
    <![endif]-->
   <!--[if !(IE 8)]><!-->
      <link href="/css/common_o2.1-858f47868a8d0e12dfa7eb60fa84fb17.css" media="all" rel="stylesheet" type="text/css" />
    <!--<![endif]-->

    <!--[if lt IE 9]>
      <link href="/css/airglyphs-ie8-9f053f042e0a4f621cbc0cd75a0a520c.css" media="all" rel="stylesheet" type="text/css" />
    <![endif]-->

    <link href="/css/main-f3fcc4027aaa2c83f08a1d51ae189e3b.css" media="screen" rel="stylesheet" type="text/css" />
  <!--[if IE 7]>
    <link href="/css/p1_ie_7-0ab7be89d3999d751ac0e89c44a0ab50.css" media="screen" rel="stylesheet" type="text/css" />
  <![endif]-->
  <!--[if IE 6]>
    <link href="/css/p1_ie_6-7d6a1fd8fe9fdf1ce357f6b864c83979.css" media="screen" rel="stylesheet" type="text/css" />
  <![endif]-->
    <script type="text/javascript" src="https://www.piperpal.com/piperpal-autocomplete-tours.php"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <link rel="dns-prefetch" href="//maps.googleapis.com">
    <link rel="dns-prefetch" href="//maps.gstatic.com">
    <link rel="dns-prefetch" href="//mts0.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//www.piperpal.com">
    <title>piperpal.com - Location-based Search Engine</title>
    <link href="my_style_form.css" type="text/css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Titillium+Web:700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
    <meta charset="utf-8" />
    <script>
    function price_changed() {
        var priceWidget = document.getElementById('price');
        var price = priceWidget.options[priceWidget.selectedIndex].value;
        var stripeSubmit = document.getElementsByClassName('stripe-button-el')[0];
        var regularSubmit = document.getElementById('regular-submit');
        if (price > 0) {
	    stripeSubmit.style.display = 'inline-block';
	    regularSubmit.style.display = 'none';
        } else {
	    stripeSubmit.style.display = 'none';
	    regularSubmit.style.display = 'inline-block';
        }
}

function updateGeo() {
    var glat = document.getElementById("glat");
    var glon = document.getElementById("glon");
    
    glat.value = position.coords.latitude;
    glon.value = position.coords.longitude;

    alert(glat+","+glon);
}

function getWikipedia() {
    jQuery(function($) {
	$.getJSON('http://api.geonames.org/findNearbyWikipediaJSON?formatted=true&lat='+ position.coords.latitude +'&lng='+ position.coords.longitude +'&username=username&style=full&lang=en&wikipediaUrl&thumbnailImg', function(json){

	    for(var i = 0; i < json.geonames.length; i++)
	    {
		$("#wikipedia").prepend('<span style="font-family: geneva, arial, helvetica, sans-serif;"><br><br><img src="wikilogo.gif"><br>' + json.geonames[i].summary + '<br><a href="http://'+ json.geonames[i].wikipediaUrl +'" target="_blank">'+ json.geonames[i].wikipediaUrl +'</a><br></span>');
		  }
	    }); });
}

function submit() {
        // Strip hijacks the form submit, so we need to un-hijack it.
	    var form = document.getElementById('form');
        form.submit();
}
    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
    <meta http-equiv="X-UA-Compatible" content="IE=edge" >
    <link href='//fonts.googleapis.com/css?family=Titillium+Web:300italic,400,700,400italic,600italic' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Titillium+Web:300italic,400,700,400italic,600italic' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <script type="text/javascript" src="https://www.piperpal.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="https://www.piperpal.com/jquery.autocomplete.min.js"></script>
    <script type="text/javascript" src="https://www.piperpal.com/piperpal-autocomplete.php"></script>
    <script type="text/javascript" src="https://www.piperpal.com/piperpal-autocomplete-names.php"></script>
    <script type="text/javascript" src="https://www.piperpal.com/piperpal-autocomplete-services.php"></script>
    <script type="text/javascript" src="https://www.piperpal.com/piperpal-autocomplete-locations.php"></script>
    <script type="text/javascript" src="https://www.piperpal.com/piperpal-autocomplete-queries.php"></script>
    <meta name="viewport" content="width=620" />
    <script>
    \$(function() {
	\$( "#datepicker" \)\.datepicker\(\)\;
	\$( "#simple-search-tourout" \)\.datepicker\(\)\;
    \}\)\;
    </script>
    </head>
    <body style="background: #ffffff;" onload="document.getElementById('query').focus();">
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <!--script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"--><!--/script-->
    <script>
	function success(position) {
	var s = document.querySelector('#status');
	
	if (s.className == 'success') {
		// not sure why we're hitting this twice in FF, I think it's to do with a cached result coming back    
		return;
	}
	
        s.innerHTML = '<input type=hidden class="input_form_custom" name=glat value=' + position.coords.latitude + ' /><input type=hidden class="input_form_custom" name=glon value=' + position.coords.longitude + ' />';
	s.className = 'success';

	var q = document.querySelector('#status_publisher');
	
	if (q.className == 'success') {
		// not sure why we're hitting this twice in FF, I think it's to do with a cached result coming back    
		return;
	}
	
        q.innerHTML = '<img src="img/Latitude_Icon.png" title="Latitude of Location-aware Content Tag" class="icon_form" height="36" width="36"><input type=text class="input_form_custom" name=glat size=16 value=' + position.coords.latitude + ' /><br /><img src="img/Longitude_Icon.png" title="Longitude of Location-aware Content Tag" class="icon_form" height="36" width="36"><input type=text class="input_form_custom" name=glon size=16 value=' + position.coords.longitude + ' />';

        // '<input type=text name=glat size=16 value=' + position.coords.latitude + ' /><input type=text name=glon size=16 value=' + position.coords.longitude + ' />';
	q.className = 'success';

	var r = document.querySelector('#status_lns');
	
	if (r.className == 'success') {
		// not sure why we're hitting this twice in FF, I think it's to do with a cached result coming back    
		return;
	}
	
        r.innerHTML =  '<img src="img/Latitude_Icon.png" title="Latitude of Location-aware Content Tag" class="icon_form" height="36" width="36"><input type=text class="input_form_custom" name=glat size=16 value=' + position.coords.latitude + ' /><br /><img src="img/Longitude_Icon.png" title="Longitude of Location-aware Content Tag" class="icon_form" height="36" width="36"><input type=text class="input_form_custom" name=glon size=16 value=' + position.coords.longitude + ' />';

//'<input type=hidden class="input_form_custom" name=glat value=' + position.coords.latitude + ' /><input type=hidden class="input_form_custom" name=glon value=' + position.coords.longitude + ' />';
	r.className = 'success';

	var mapcanvas = document.createElement('div');
	mapcanvas.id = 'mapcanvas';
	mapcanvas.style.height = '400px';
	mapcanvas.style.width = '640px';
	
	document.querySelector('article').appendChild(mapcanvas);
	
	var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	var myOptions = {
	zoom: 15,
	center: latlng,
	mapTypeControl: false,
	navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
	mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("mapcanvas"), myOptions);
	
	var marker = new google.maps.Marker({
		position: latlng, 
				map: map, 
				title:"You are here! (at least within a "+position.coords.accuracy+" meter radius)"
				});

        var locations = [
         ['Banja Luka', 44.766666699999990000, 17.183333299999960000, 4],
    ['Tuzla', 44.532841000000000000, 18.670499999999947000, 5],
    ['Zenica', 44.203439200000000000, 17.907743200000027000, 3],
    ['Sarajevo', 43.850000000000000000, 18.250000000000000000, 2],
    ['Mostar', 43.333333300000000000, 17.799999999999954000, 1]
];

var infowindow = new google.maps.InfoWindow();

var marker, i;
for (i = 0; i < locations.length; i++) {
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(locations[i][1], locations[i][2]),
      map: map
				    });

google.maps.event.addListener(marker, 'click', (function (marker, i) {
    return function () {
	infowindow.setContent(locations[i][0]);
	infowindow.open(map, marker);
    }
						})(marker, i));
}
}

function error(msg) {
	// var s = document.querySelector('#status');
	// s.innerHTML = typeof msg == 'string' ? msg : "failed";
	// s.className = 'fail';
	
	// console.log(arguments);
}

if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(success, error);
} else {
	error('not supported');
}

</script>

    <center><table><tr><td valign='top' width=600><a href="https://www.piperpal.com/"><img src='/piperpal.png' alt='piperpal.com Logo' width=600/></a></td></tr></table>
    
    <div class="background_form">
EOF

print "<table>";
print "<tr><td valign='top' width=600>";
print "<center><h3>Discover the world around you</h3></center>";
print "</td></tr></table>";
&Piperpal::select_location();
print "<br />";
print "<p>Hint: You could markup with &lt;location&gt; tag in <a href='https://www.w3.org/wiki/HTML/next#.3Clocation.3E_element_.28like_.29_for_expressing_geo_information.2C_eg_with_attributes_lat.2C_long.2C_altitude'>HTML.next</a><br />";
print "Proposal: <a href=\"https://www.aamotsoftware.com/location.html\">https://www.aamotsoftware.com/location.html</a><br />";
print "JSON in HTML5: <a href=\"https://www.piperpal.com/google.html\">https://www.piperpal.com/google.html</a></p>";
print "<p><a href=\'https://www.stripe.com/\'><img alt=\'Powered by stripe\' src=\'/powered_by_stripe.png\'></a>&nbsp;<a href=\'https://play.google.com/store/apps/details?id=com.piperpal.api.android&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1'><img alt='Get it on Google Play\' src=\'/google-piperpal.png\'/></a></p>";
print "<p>Copyright &copy; 2015-2020  <location name='Aamot Software' href='https://www.aamotsoftware.com/' lat='37.4219999' lon='-122.0862462' service='Electronics'>Aamot Software</location><br /><location name='Piperpal' href='https://www.piperpal.com/' lat='59.94215220' lon='10.71697530' service='Crawler'>Oslo, Norway</location> - <location name='Piperpal' href='https://www.piperpal.com/' lat='37.879015' lon='-122.262425' service='Crawler'>Berkeley, California, USA</location></p>";
print "</td></tr></table>\n";
print "</body>\n</html>\n";
