<!DOCTYPE html>
<html lang="en">
  <head>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <meta charset="utf-8">
    <title>Piperpal</title>
  </head>
  <body>
    <div id="log"></div>	 
    <script>
	 $(document).ready(function(){
			 
			 setInterval(function(){
					 
					 if (navigator.geolocation) {
						 navigator.geolocation.getCurrentPosition(ajaxCall);         
					 } else{
						 $('#log').html("GPS is not available");
					 }
					 
					 function ajaxCall(position){
						 
						 var latitude = position.coords.latitude;
						 var longitude = position.coords.longitude;
						 var location = window.location.pathname.substr(1);
						 var queryString = window.location.search;
						 var urlParams = new URLSearchParams(queryString);
						 // alert(queryString+":("+latitude+","+longitude+")");
						 $.ajax({
							 url: "https://api.piperpal.com/pull.php", 
									 type: 'POST', //I want a type as POST
                                 data: {'glat' : latitude, 'glon' : longitude, 'location' : location, 'name' : urlParams.get('name'), 'service' : urlParams.get('service'), 'radius' : urlParams.get('radius') },
									 success: function(response) {
										 $('#log').html(response);
										 // alert(response);
								 }
						   });
				   }       
			   },30000);
		   

		 });
    </script>
    </body>
</html>
