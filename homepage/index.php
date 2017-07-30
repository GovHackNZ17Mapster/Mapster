<?php
    header("Access-Control-Allow-Origin: *");
?>
<!DOCTYPE html>
<html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
<head>
<style>
      #map1 {
        height: 100%;
        float:left;
        width:50%;
      }
      #map2 {
        height: 100%;
        float:right;
        width:50%;
      }
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }

a:focus {
font-weight:bold;
}
#navbar {
 padding: 20;
}
    </style>
</head>
<body>
<div id="navbar">
<nav class="w3-bar w3-blue">
	<a href="index.html" class="w3-button w3-bar-item">Home</a>
	<a href="about.html" class="w3-button w3-bar-item">About</a>
	<a href="contact.html" class="w3-button w3-bar-item">Contact</a>
	<img src="Mapster logo.png" style="float:right;height:50px;width:100px;padding:0;">
</nav>
</div>
	<table>
    <div id="map1"></div>
    <div id="map2"></div>
    <script>
      var map1, map2;
      function initMap() {
      	var options =  {
          zoom: 8,
          center: {lat: -39.3, lng: 177}
        };
        map1 = new google.maps.Map(document.getElementById('map1'), options);
        map2 = new google.maps.Map(document.getElementById('map2'), options);

        // Load GeoJSON.
        map1.data.loadGeoJson('https://sebastian-pfaller.name/GovHackNZ17/dbconn.php?coloring=rent');
        map2.data.loadGeoJson('https://sebastian-pfaller.name/GovHackNZ17/dbconn.php?coloring=crimes');

        // Set styling
        map1.data.setStyle(function(feature) {
          return ({
            fillColor: feature.getProperty('color'),
            strokeWeight: 0.25
          });
        });
        map2.data.setStyle(function(feature) {
          return ({
            fillColor: feature.getProperty('color'),
            strokeWeight: 0.25
          });
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHo-ck3t-jIY91awXZ8jou2fXDVebaIAs&callback=initMap">
    </script>
	
</body>
</html>
