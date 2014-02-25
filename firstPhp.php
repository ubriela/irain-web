<!DOCTYPE html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js">
    </script>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Google Maps Example</title>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script type="text/javascript">
        //<![CDATA[
        var map = null;
        function load() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: new google.maps.LatLng(37.817712,-122.426834),
                zoom: 15,
                mapTypeId: 'roadmap'
             
              
            });
            google.maps.event.addListener(map, 'dblclick', function(event) {
              
                var touch_point = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
                var marker = new google.maps.Marker({
                    map: map,
                    position: touch_point,
                    icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
                });
                var infoWindow = new google.maps.InfoWindow;    
                bindInfoWindow(marker, map, infoWindow, event.latLng.lat()+'-'+ event.latLng.lng());
                marker.setMap(map);
            });
      
           
        }
        var Array_region = new Array();
        var Array_polygon = new Array();
        var polygon = new Array();
        var point0;
        var point1;
        var point2;
        var point3;
                     
        var i = -1;
        var json = "blank";
        
        function GeoCast_Query(url){
            $.getJSON('http://whateverorigin.org/get?url=' + encodeURIComponent(url) + '&callback=?', function(data){
                json = data.contents;
             
                if (json=="blank")
                    alert(json);
                else{
                    obj = JSON.parse(json);
                    Overlay_GeoCast_Region();
                }
            });
        }
        
        function add(i){
         
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
        
     
        
       
        
       
        
       
        function Overlay_GeoCast_Region(){
            /*var marker = new google.maps.Marker({
                map: map,
                position: new google.maps.LatLng(obj.spatial_task.location[0], obj.spatial_task.location[1]),
                icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
            });
           
            marker.setMap(map);            */
            i=-1;
            var interval = setInterval(function() { 
                add_geocast_cell(i);
                i++; 
                if(i >= obj.geocast_query.x_min_cords.length) 
                    clearInterval(interval);
            }, 1000);         
        }
        
      
        function add_geocast_cell(i){
          
                
            polygon = new Array();  
            Array_region = new Array();
            Array_polygon = new Array();
                
            point0 = new google.maps.LatLng(obj.geocast_query.x_min_cords[i],
            obj.geocast_query.y_min_cords[i]);
            polygon[0] = point0;
                
            point1 = new google.maps.LatLng(obj.geocast_query.x_min_cords[i], 
            obj.geocast_query.y_max_cords[i]);
            polygon[1] = point1;
                
            point2 = new google.maps.LatLng(obj.geocast_query.x_max_cords[i], 
            obj.geocast_query.y_max_cords[i]);
            polygon[2] = point2;
                
            point3 = new google.maps.LatLng(obj.geocast_query.x_max_cords[i], 
            obj.geocast_query.y_min_cords[i]);
            polygon[3] = point3;
                
            polygon[4] = point0;
                
            Array_polygon[i]=polygon;
              
               
            Array_region[i] = new google.maps.Polygon({
                path:Array_polygon[i],
                strokeColor:"#0000FF",
                strokeOpacity:0.8,
                strokeWeight:2,
                fillColor:"#0000FF",
                fillOpacity:0.1
            });
            Array_region[i].setMap(map);
           
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
            json = "blank";
            google.maps.event.addListener(marker, 'mouseover', function() {
                infoWindow.setContent(html);
                infoWindow.open(map, marker);
            });
            google.maps.event.addListener(marker, 'mouseout', function() {
                infoWindow.close(map, marker);
            });
            google.maps.event.addListener(marker, 'click', function(event) {
                url = 'http://geocrowd2.cloudapp.net/geocast/' + event.latLng.lat() + "," + event.latLng.lng();
                GeoCast_Query(url);
                alert( 'Lat: ' + event.latLng.lat() + ' and Longitude is: ' + event.latLng.lng() );
            });
            
        }
       
       

       
     

       
        

        //]]>

    </script>

</head>

<body onload="load()">
    <div style="width: 100%;overflow:auto;">

        <div style="float:left;">
            <input type="button" value="Retrieve JSON" onClick="GeoCast_Query('http://geocrowd2.cloudapp.net/geocast/37.80198523110427,-122.41419297781249')">

            </input>      
        </div>
        <div style="float:left;">
            <div id="map" style="width: 800px; height: 550px"></div>
        </div>
    </div>





    <?php
    $json = "";

    if (isset($_POST['submit'])) {
        $json = file_get_contents('http://137.135.57.140/geocast/37.80198523110427,-122.41419297781249');
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
