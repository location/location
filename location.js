<!--
// Main function
function Location() {
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
  
    thediv.innerHTML = "<form method='POST' action='http://location.gl/vote/'><table><tr><th>Name</th><td><input type='text' name='name' value='" + name + "' /></td></tr><tr><th>Link</th><td><input type='text' name='link' value='" + link + "' /><span style='font-size: 9px'>Hint: Link to <a href='http://location.gl/" + name + "'>http://location.gl/" + name + "</a> from your web page for your web page to appear here</span></td></tr><tr><th>GLat</th><td><input type='text' name='glat' value='" + glat + "' /></td></tr><tr><th>GLon</th><td><input type='text' name='glon' value='" + glon + "' /></td></tr><input type='hidden' name='dist' value='" + dist + "' /><tr><td colspan='2'><input type='submit' value='Vote' /></td></tr></table></form>\n";
    
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

Location();

-->