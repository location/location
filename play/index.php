<?

$airports = array(
	'OSL' => 'OSL/Oslo',
	'BGO' => 'BGO/Bergen',
	'KRS' => 'KRS/Kristiansand',
	'SVG' => 'SVG/Stavanger',
	'TRD' => 'TRD/Trondheim',
	'TOS' => 'TOS/Troms&oslash;',
	'AES' => 'AES/&Aring;lesund',
	'ALF' => 'ALF/Alta',
	'ANX' => 'ANX/Andenes',
	'BDU' => 'BDU/Bardufoss',
	'BVG' => 'BVG/Berlev&aring;g',
	'BOO' => 'BOO/Bod&oslash;',
	'BNN' => 'BNN/Br&oslash;nn&oslash;ysund',
	'BJF' => 'BJF/B&aring;tsfjord',
	'VDB' => 'VDB/Fagernes',
	'FRO' => 'FRO/Flor&oslash;',
	'FDE' => 'FDE/F&oslash;rde',
	'HFT' => 'HFT/Hammerfest',
	'EVE' => 'EVE/Harstad/Narvik',
	'HAU' => 'HAU/Haugesund',
	'HVG' => 'HVG/Honningsv&aring;g',
	'KKN' => 'KKN/Kirkenes',
	'KSU' => 'KSU/Kristiansund',
	'LKL' => 'LKL/Lakselv',
	'LKN' => 'LKN/Leknes',
	'MEH' => 'MEH/Mehamn',
	'MQN' => 'MQN/Mo i Rana',
	'MOL' => 'MOL/Molde',
	'MJF' => 'MJF/Mosj&oslash;en',
	'OSY' => 'OSY/Namsos',
	'NVK' => 'NVK/Narvik',
	'RRS' => 'RRS/R&oslash;ros',
	'RVK' => 'RVK/R&oslash;rvik',
	'RET' => 'RET/R&oslash;st',
	'SDN' => 'SDN/Sandane',
	'TRF' => 'TRF/Sandefjord&nbsp;Lufthavn&nbsp;Torp',
	'SSJ' => 'SSJ/Sandnessj&oslash;en',
	'SOG' => 'SOG/Sogndal',
	'SKN' => 'SKN/Stokmarknes',
	'LYR' => 'LYR/Longyearbyen',
	'SVJ' => 'SVJ/Svolv&aelig;r',
	'SOJ' => 'SOJ/S&oslash;rkjosen',
	'VDS' => 'VDS/Vads&oslash;',
	'VAW' => 'VAW/Vard&oslash;',
	'VRY' => 'VRY/V&aelig;r&oslash;y',
	'HOV' => 'HOV/&Oslash;rsta-Volda'
);

$postals = array(
	'OSL' => '2061',
 	'BGO' => '5869', 
 	'KRS' => '4657', 
 	'SVG' => '4055', 
 	'TRD' => '7500', 
 	'TOS' => '9269', 
 	'AES' => '6040', 
 	'ALF' => '9509', 
 	'ANX' => '8480', 
 	'BDU' => '9325', 
 	'BVG' => '9981', 
 	'BOO' => '8041', 
 	'BNN' => '8900', 
 	'BJF' => '9991', 
 	'VDB' => '2900', 
 	'FRO' => '6901', 
 	'FDE' => '6977', 
 	'HFT' => '9600', 
 	'EVE' => '8536', 
 	'HAU' => '4262', 
 	'HVG' => '9751', 
 	'KKN' => '9912', 
 	'KSU' => '6517', 
 	'LKL' => '9700', 
 	'LKN' => '8370', 
 	'MEH' => '9770', 
 	'MQN' => '8615', 
 	'MOL' => '6421', 
 	'MJF' => '8658', 
 	'OSY' => '7800', 
 	'NVK' => '8516', 
 	'RRS' => '7361', 
 	'RVK' => '7900', 
 	'RET' => '8064', 
 	'SDN' => '6823', 
 	'SSJ' => '8800', 
 	'SOG' => '6854', 
 	'SKN' => '8450', 
 	'LYR' => '9171', 
 	'SVJ' => '8300', 
 	'SOJ' => '9152', 
 	'TRF' => '3241', 
 	'VDS' => '9811', 
 	'VAW' => '9950', 
 	'VRY' => '8063', 
	'HOV' => '6150'
);

$pnummap = array(
 	'2061' => array('60.18526', '11.08224'),
 	'3241' => array('59.17201', '10.21396'),
 	'5869' => array('60.29230', '5.22210'), 
 	'4657' => array('58.20733', '8.07991'), 
 	'4055' => array('58.88959', '5.62566'), 
 	'7500' => array('63.46954', '10.920721'), 
 	'9269' => array('69.64810', '18.95670'), 
 	'6040' => array('62.5823',  '6.123'), 
 	'9509' => array('69.976058','23.34069'), 
 	'8480' => array('69.317744','16.121335'), 
 	'9325' => array('69.065297','18.510332'), 
 	'9981' => array('70.84142','29.10564'), 
 	'8041' => array('67.29449','14.59231'), 
 	'8900' => array('65.469845','12.205811'), 
 	'9991' => array('70.63790','29.72702'), 
 	'2900' => array('60.985953','9.236697'), 
 	'6901' => array('61.60059','5.04397'), 
 	'6977' => array('61.37589','5.65343'), 
 	'9600' => array('70.66299','23.67996'), 
 	'8536' => array('68.582961','16.573734'), 
 	'4262' => array('59.355935','5.277621'), 
 	'9751' => array('70.98214','25.97076'), 
 	'9912' => array('69.69152','29.99221'), 
 	'6517' => array('63.11029','7.79234'), 
 	'9700' => array('70.056306','25.000935'), 
 	'8370' => array('68.148182','13.611546'), 
 	'9770' => array('71.03883','27.85206'), 
 	'8516' => array('68.44052','17.40815'), 
 	'6421' => array('62.75212','7.29143'), 
 	'8658' => array('65.81893','13.2741'), 
 	'7800' => array('64.467504','11.491785'), 
 	'8615' => array('66.356186','14.321322'), 
	'7361' => array('62.573353','11.380677'), 
	'7900' => array('64.86556','11.23482'), 
	'8064' => array('67.518154','12.125261'), 
 	'6823' => array('61.780673','6.220193'), 
 	'8800' => array('66.01263','12.60632'), 
 	'6854' => array('61.19567','7.21867'), 
 	'8450' => array('68.56767','14.91314'), 
 	'9171' => array('78.22220','15.63150'), 
 	'8300' => array('68.23224','14.564524'), 
 	'9152' => array('69.79265','20.93912'), 
 	'9811' => array('70.07306','29.75280'), 
 	'9950' => array('70.37033','31.105814'), 
 	'8063' => array('67.66333','12.69181'), 
 	'6150' => array('62.20290','6.12720')
);
if(isset($_GET['gps'])) {
	$gpsdata = array($_GET['gpsdata_latitude'], $_GET['gpsdata_longitude']);
} else {
?>
<html>
<head>
<meta http-equiv="refresh" content="15;url=http://location.gl/play/geoify.php?airport=BGO" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="http://code.google.com/apis/gears/gears_init.js"></script>
<script type='text/javascript'>
<!--
function refreshPosition(position) {
	location.replace("http://location.gl/play/index.php?gps=0&gpsdata_latitude=" + position.coords.latitude + "&gpsdata_longitude=" + position.coords.longitude + "");
}
function handleError(positionError) {
	location.replace("http://location.gl/play/geoify.php?airport=ERR");
}
$(document).ready(function() {
	var geo = "";
	try {window.navgeo = !!(typeof navigator.geolocation != 'undefined');}catch(e){}
	if (window.navgeo) {
		geo = navigator.geolocation;
		$('div#debug').append('Geoify is asking you about permission for location sharing (HTML5).');
	} else {
		try {window.gears = !!(typeof GearsFactory != 'undefined' || navigator.mimeTypes['application/x-googlegears'] || new ActiveXObject('Gears.Factory'));} catch (e) {}
		if (window.gears) {
			geo = google.gears.factory.create('beta.geolocation');
			$('div#debug').append('Geoify is asking you about permission for location sharing (Google Gears).');
			geo.getCurrentPosition(refreshPosition, handleError);
		}
	}
	if (geo != "") {
		geo.getCurrentPosition(refreshPosition, handleError);
	}
});
-->
</script>
<div id="debug"></div>
</body>
</html>
<?
}

foreach ($pnummap as $key => $val) {
	foreach ($postals as $a => $b) {
		if ($key == $b) {
			$xdelta[$a] = abs((float)$gpsdata[0]-(float)$val[0]);
			$ydelta[$a] = abs((float)$gpsdata[1]-(float)$val[1]);
			$zdelta[$a] = abs((float)($xdelta[$a])+(float)($ydelta[$a]));
		}
	}
}
function min_key($array) {
	foreach ($array as $key => $val) {
		if ($val == min($array)) return $key;
	}
}
$airport = min_key($zdelta);
if(isset($_GET['gps'])) {
	header("Location: http://location.gl/play/geoify.php?airport=" . $airport);
}
?>
