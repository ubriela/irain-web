<!DOCTYPE html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">
    </script>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Google Maps Example</title>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

    <script type="text/javascript">
        //<![CDATA[
        var map = null;
        function load() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: new google.maps.LatLng(37.943115,-122.036133),
                zoom: 9,
                mapTypeId: 'roadmap'
            });
        
            var infoWindow = new google.maps.InfoWindow;

            // Change this depending on the name of your PHP file             
            var point = new google.maps.LatLng(47.6145, -122.3418);
            var html = "Marker's address 12345";
            var icon = 'http://labs.google.com/ridefinder/images/mm_20_blue.png';
            /* var marker = new google.maps.Marker({
                map: map,
                position: point,
                icon: icon
            });
            bindInfoWindow(marker, map, infoWindow, html);*/
        }
        var json = "blank";
        function getJSON(url){
            $.getJSON('http://whateverorigin.org/get?url=' + encodeURIComponent(url) + '&callback=?', function(data){
                json = data.contents;
                alert('getJSON done');
            });
        }
        
        function add(i){
            var infoWindow = new google.maps.InfoWindow;           
            var lat = obj.notified_workers.x_cords[i] ;
            var lng = obj.notified_workers.y_cords[i];
            var point = new google.maps.LatLng(lat, lng);
            var html = "Marker's address 12345";
            var icon = 'http://labs.google.com/ridefinder/images/mm_20_blue.png';
            var marker = new google.maps.Marker({
                map: map,
                position: point,
                icon: icon
            });
            bindInfoWindow(marker, map, infoWindow, html);
            i++;
        }
        
        function addMarker_Interval(){
            var i = 0;
            if (i==obj.notified_workers.y_cords.length)
                clearInterval(myVar);
            var myVar=setInterval(function(){add(i)},1000);
            
            
        }
        function AddPolygon(){
            var polygon = new Array();
            if (json=="blank")
                alert(json);
            else
            //    alert("data contained");
                obj = JSON.parse(json);
            for (var i = 0; i<6; i++){
                
                var lat = obj.notified_workers.x_cords[i] ;
                var lng = obj.notified_workers.y_cords[i];
                var point = new google.maps.LatLng(lat, lng);
                polygon[i] = point;
            }
            var endpoint = polygon[polygon.length-1];
            
            polygon[polygon.length] = endpoint;
            var region = new google.maps.Polygon({
                path:polygon,
                strokeColor:"#0000FF",
                strokeOpacity:0.8,
                strokeWeight:2,
                fillColor:"#0000FF",
                fillOpacity:0.4
            });
            region.setMap(map);
            
            /* var region;


            // Define the LatLng coordinates for the polygon's path.
            var triangleCoords = [
                new google.maps.LatLng(37.88956, -121.61944),
                new google.maps.LatLng(37.89827, -122.06306),
                new google.maps.LatLng(37.925499, -121.735505),
                new google.maps.LatLng(37.94752, -121.69631),
                new google.maps.LatLng(37.88956, -121.61944)
            ];

            // Construct the polygon.
            region = new google.maps.Polygon({
                paths: triangleCoords,
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35
            });

            region.setMap(map);*/
        }
       
        function addBLUEMarker(){
            // getJSON('http://137.135.57.140/geocast/37.943115,-122.036133');
            if (json=="blank")
                alert(json);
            else
            //    alert("data contained");
                obj = JSON.parse(json);

            // alert(obj.notified_workers.y_cords[0]);
            // var parse = eval('('+json+')');
            // var array = parse.spatial_task.location;
           
        
            var infoWindow = new google.maps.InfoWindow;


            // Change this depending on the name of your PHP file   
            for (var i = 0; i<obj.notified_workers.y_cords.length; i++){
                
                var lat = obj.notified_workers.x_cords[i] ;
                var lng = obj.notified_workers.y_cords[i];
                var point = new google.maps.LatLng(lat, lng);
                var html = "Marker's address 12345";
                var icon = 'http://labs.google.com/ridefinder/images/mm_20_blue.png';
                var marker = new google.maps.Marker({
                    map: map,
                    position: point,
                    icon: icon
                });
                bindInfoWindow(marker, map, infoWindow, html);
            }
           
           
        }
      
      
    
        
        function bindInfoWindow(marker, map, infoWindow, html) {
            google.maps.event.addListener(marker, 'click', function() {
                infoWindow.setContent(html);
                infoWindow.open(map, marker);
            });
        }
        
        function async_simulate(){
            getJSON('http://137.135.57.140/geocast/37.943115,-122.036133');
            while (json=='blank') {
                doNothing();
            }
            AddPolygon();
        }

     
     

        function doNothing() {}
        
        

        //]]>

    </script>

</head>

<body onload="load()">

    <div id="map" style="width: 600px; height: 600px"></div>


</div>
<form method="post" > 

    <input type="submit" name="submit" value="Submit"> 
    <input type="submit" name="null" value="Null"> 
    <input type="button" value="Retrieve JSON" onClick="getJSON('http://137.135.57.140/geocast/37.943115,-122.036133')"></input>
    <input type="button" value="Display BLUE Marker" onClick="async_simulate()"></input>    

</form>
<?php
$json = "";

if (isset($_POST['submit'])) {
    $json = file_get_contents('http://137.135.57.140/geocast/37.943115,-122.036133');
    $obj1 = json_decode($json);
    $obj2 = $obj1->{'notified_workers'}->{'x_cords'}[1];
    $obj3 = "false";
    if ($obj1->{'is_performed'})
        $obj3 = "true";
    echo $obj2 . $obj3;
}
if (isset($_POST['null'])) {
    echo "null button is clicked";
}
?>

</body>




</html>