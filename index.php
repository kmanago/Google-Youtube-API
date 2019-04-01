<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="main.css"/>
    </head>

    <body>
            <b>Search Results</b><div id="results"></div>
            <b>Search Term: </b><input type="search" id="term" name="keyword" placeholder="Enter Search Term"><br>
            <b>Location: </b> <input id="address" type="textbox"><br>
            <b>Location Radius: </b> <input type="text" id="locationRadius" name="radius" placeholder="5km"><br>
            <b>Max Results: </b><input type="number" id="maxResults" name="max" value="10">
            <br>
            <input id="submit" type="submit" value="Search">
        
        
        <div id="map"></div>
        <script>	  
           var latitude = null;
           var longitude = null;
//          initializes google maps
            function initMap(){
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 8,
                    center: {lat: 33.951935, lng: -83.357567}
                });
                var geocoder = new google.maps.Geocoder();
                //handles submit button click
                document.getElementById('submit').addEventListener('click', function() {
                    geocodeAddress(geocoder, map);
                    
                });
            }
			
	
            function geocodeAddress(geocoder, resultsMap) {
                var address = document.getElementById('address').value;
                
                geocoder.geocode({'address': address}, function(results, status) {
                    if (status === 'OK') {
                        
                        
                        resultsMap.setCenter(results[0].geometry.location);
                        var marker = new google.maps.Marker({
							icon:'marker.png',
                            map: resultsMap,
                            position: results[0].geometry.location
                        });
                        
                           latitude = results[0].geometry.location.lat();
                           longitude = results[0].geometry.location.lng();
                           var keyword = document.getElementById('term').value;
                           var radius = document.getElementById('locationRadius').value;
                           var max = document.getElementById('maxResults').value;
                            //passing all the values to search.php
//        					window.location.href = "search.php?keyword=" + keyword  +"&latitude=" + latitude  
//        						+ "&longitude=" + longitude + "&radius=" + radius + "&max=" + max;
//                        
                        //ajax call
                            $.ajax({
								type: "GET",       //Get method
                               // dataType: 'json',   //response return type
                                url: 'search.php', //the file the call goes to
                                data:{              //the variables passed through
                                    keyword: keyword,
                                    latitude: latitude,
                                    longitude: longitude,
                                    radius: radius,
                                    max: max
                                },
                                
                                success: function(data){  //what to do when the method call succeeds
								 //alert(data); //returns the data pass in an alert (for testing)
                                  //  $("#results").html(data);
                                    console.log(data);
                                    
                                    //content string for window stuff
                                    var contentString = data;
                                    //info window stuff
                                    var infowindow = new google.maps.InfoWindow({
                                        content: contentString
                                    });
                                    google.maps.event.addListener(marker, 'mouseover', function() {
                                        infowindow.open(map,marker);
                                    });
                                },
                                error: function( xhr, status, errorThrown ) {  //what to do when the method call fails
                                    alert( "Sorry, there was a problem!" );
                                    console.log( "Error: " + errorThrown );
                                    console.log( "Status: " + status );
                                    console.dir( xhr );
                              }
                        });
                    } else {
                        alert('Geocode was not successful for the following reason: ' + status);
                    }
 
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC9A2a6sTMO8RS1RcE4yHEsSr24I1FKcD8&callback=initMap" async defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		
			<div class="results">
            </div>

    </body>


</html>
