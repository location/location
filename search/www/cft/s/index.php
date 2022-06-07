<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width">
    <title>piperpal.com - Location-based Search Engine</title>
    <!--[if IE]><![endif]-->      
    <!--[if IE 8]>
      <link href="../common_o2.1_ie8-91981ea8f3932c01fab677a25d869e49.css" media="all" rel="stylesheet" type="text/css" />
    <![endif]-->
   <!--[if !(IE 8)]><!-->
      <link href="../common_o2.1-858f47868a8d0e12dfa7eb60fa84fb17.css" media="all" rel="stylesheet" type="text/css" />
    <!--<![endif]-->

    <!--[if lt IE 9]>
      <link href="../airglyphs-ie8-9f053f042e0a4f621cbc0cd75a0a520c.css" media="all" rel="stylesheet" type="text/css" />
    <![endif]-->

    <link href="../main-f3fcc4027aaa2c83f08a1d51ae189e3b.css" media="screen" rel="stylesheet" type="text/css" />
  <!--[if IE 7]>
    <link href="../p1_ie_7-0ab7be89d3999d751ac0e89c44a0ab50.css" media="screen" rel="stylesheet" type="text/css" />
  <![endif]-->
  <!--[if IE 6]>
    <link href="../p1_ie_6-7d6a1fd8fe9fdf1ce357f6b864c83979.css" media="screen" rel="stylesheet" type="text/css" />
  <![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
    <meta http-equiv="X-UA-Compatible" content="IE=edge" >
    <link href='//fonts.googleapis.com/css?family=Titillium+Web:300italic,400,700,400italic,600italic' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Titillium+Web:300italic,400,700,400italic,600italic' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="https://www.piperpal.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="https://www.piperpal.com/jquery.autocomplete.min.js"></script>
    <!-- FIXME
    <script type="text/javascript" src="https://www.piperpal.com/yaylocation-autocomplete.php"></script>
    <script type="text/javascript" src="https://www.piperpal.com/piperpal-autocomplete-names.php"></script>
    <script type="text/javascript" src="https://www.piperpal.com/piperpal-autocomplete-services.php"></script>
    <script type="text/javascript" src="https://www.piperpal.com/piperpal-autocomplete-locations.php"></script>
    <script type="text/javascript" src="https://www.piperpal.com/piperpal-autocomplete-queries.php"></script>
    -->
    <meta name="viewport" content="width=620" />
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
   $(function() {
       $( "#simple-search-tourin" ).datepicker();
       $( "#simple-search-tourout" ).datepicker();
     });

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

  function updateGeo() {
    var glat = document.getElementById("glat");
    var glon = document.getElementById("glon");
    
    glat.value = position.coords.latitude;
    glon.value = position.coords.longitude;

    alert(glat+","+glon);
  }
  </script>

    </head>
    <body style="background: #ffffff">
    <center>
    <a href="https://www.piperpal.com/"><img border="0" src="https://www.piperpal.com/piperpal.png" width="600" alt="Logo" /></a>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
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
	
        q.innerHTML = '<div class="service_form input_form"><img src="img/icon2.png" alt="Icon2" class="icon_form" height="36" width="36"><input type=text class="input_form_custom" name=glat size=16 value=' + position.coords.latitude + ' /></div><div class="service_form input_form"><img src="img/icon2.png" alt="Icon2" class="icon_form" height="36" width="36"><input type=text class="input_form_custom" name=glon size=16 value=' + position.coords.longitude + ' /></div>';

        // '<input type=text name=glat size=16 value=' + position.coords.latitude + ' /><input type=text name=glon size=16 value=' + position.coords.longitude + ' />';
	q.className = 'success';
	
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
	var s = document.querySelector('#status');
	s.innerHTML = typeof msg == 'string' ? msg : "failed";
	s.className = 'fail';
	
	// console.log(arguments);
}

if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(success, error);
} else {
	error('not supported');
}

</script>
   <form onsubmit='updateGeo()' id="searchbar-form" method='POST' action='https://www.piperpal.com/checkout.php'>
   <input type='hidden' name='c' value='INSERT' />
   <table cellpadding=5>
   <tr><td>Text:<input id="simple-search-location" class="input-large js-search-location" size=24 type=text name=name class=biginput id=name value='<?php echo $_GET['name']; ?>' placeholder='Example: FriendliestCoffeeShop' /></td></tr>
    <tr><td>Location:<input id="simple-search-location" class="input-large js-search-location" size=60 type=text name=location class=biginput id=location value='<?php if (!isset($_GET['location'])) { echo $_GET['location']; } else { echo 'https://jitsi.aamot.software/'; echo $_GET['service']; echo '/'; echo $_GET['name']; } ?>' /></td></tr>
   <tr><td>Service:<input id="simple-search-location" class="input-large js-search-location" size=24 type=text name=service class=biginput id=service value='<?php echo $_GET['service']; ?>' placeholder='Service' /></td></tr>
   <tr><td>Not Before:<input id="simple-search-tourin" class="input-large js-search-location" size=24 type=text name=notBefore class=biginput id=notBefore value='<?php echo $_GET['notBefore']; ?>' placeholder='Not Before' /></td></tr>
   <tr><td>Not After:<input id="simple-search-tourout" class="input-large js-search-location" size=24 type=text name=notAfter class=biginput id=notAfter value='<?php echo $_GET['notAfter']; ?>' placeholder='Not After'/></td></tr>
   <input id="simple-search-location" class="input-large js-search-location" size=24 type=hidden name=paid class=biginput id=paid value='<?php echo $_GET['paid']; ?>' placeholder='Price' />
   <div id='status'>
   <input id='glat' type='hidden' name='glat' placeholder='Latitude' size=32 value='37.42242580' />
   <input id='glon' type='hidden' name='glon' placeholder='Longitude' size=32 value='-122.08755550' />
   </div>
   <tr><td><form action='' method='POST'><script src='https://checkout.stripe.com/checkout.js' class='stripe-button' data-key='pk_live_9UbKhDJJWaAFnMjYQTBA8I9i00H8Z5eMmL' data-amount='<?php echo $_GET['paid']; ?>' data-name='Aamot Software' data-description='<?php echo $_GET['location']; ?> / <?php echo $_GET['service']; ?> (<?php echo $_GET['paid']; ?>)' data-image='/img/Location_Icon.png'></script></td></tr>
   </table>
  </form>
</body>
</html>
