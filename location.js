<!--
// Main function
function locationGeo() {
    // Determine support for geolocation
    if (navigator.geolocation){
	var timeoutVal = 10 * 1000 * 1000;
	navigator.geolocation.getCurrentPosition(
	    displayPosition, 
	    displayError,
	    { enableHighAccuracy: true, timeout: timeoutVal, maximumAge: 0 }
	);
    }
    else{
	alert('It seems like Geolocation, which is required for this page, is not enabled in your browser. Please use a browser which supports it.');
    }
}

// Success callback function
function displayPosition(pos){
    var glat = pos.coords.latitude;
    var glon = pos.coords.longitude;
    var thediv = document.getElementById("location");
    thediv.innerHTML = "<p><a href='http://location.gl/vote/?name="+name+"&glat="+glat+"&glon="+glon+"'>Vote</a></p>";    
}

// Error callback function
function displayError(error) {
    var errors = { 
	1: 'Permission denied',
	2: 'Position unavailable',
	3: 'Request timeout'
    };
    var thediv = document.getElementById("errormsg");
    thediv.innerHTML = "<p>Location Error: " + errors[error.code] + "</p>";
}

locationGeo();

-->