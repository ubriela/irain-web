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
        var infoWindow;
        function load() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: new google.maps.LatLng(37.76822,-122.44297),
                zoom: 12,
                mapTypeId: 'roadmap'
             
              
            });
            google.maps.event.addListener(map, 'dblclick', function(event) {
              
                var touch_point = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
                var marker = new google.maps.Marker({
                    map: map,
                    position: touch_point,
                    icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
                });
                infoWindow = new google.maps.InfoWindow;    
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
        
        /*GeoCast_Query  takes as parametter the url which is used to retrieve 
         *a json file containning information of the geocast query
         */
        function GeoCast_Query(url){
            $.getJSON('http://whateverorigin.org/get?url=' + encodeURIComponent(url) + '&callback=?', function(data){
                json = data.contents;
             
                if (json=="blank")
                    alert("Service is now unavailable");
                else{
                    obj = JSON.parse(json);
                    if(obj.hasOwnProperty('error')){
                        alert ("The selected location is outside of the dataset");
                    }
                    else{
                        Overlay_GeoCast_Region();
                    }
                }
            });
        }
     
        /*
         *Overlay_GeoCast_Region is to visualize how geocast cells are chosen by
         *iteratively overlay polygons on map.
         *This function used setInterval to repeatedly add cell after specific 
         *amount of miliseconds
         */
        function Overlay_GeoCast_Region(){
            i=-1;
            var interval = setInterval(function() { 
                add_geocast_cell(i);
                i++; 
                if(i >= obj.geocast_query.x_min_cords.length) 
                    clearInterval(interval);
            }, 300);         
        }
        
      
        /*
         * add_geocast_cell is to add a specific cell in the list. It takes
         * as a parameter a number i indicating the order of the cell in the cell list.
         * The eventlistenr at the bottom of the function is to display a cell info
         * whenever it is clicked. This is called within the Overlay_GeoCast_Region function
         */
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
           
            

            // Add a listener for the click event to show cell info.
            infoWindow = new google.maps.InfoWindow();
            google.maps.event.addListener(Array_region[i], 'click', function(event){
                var info = 'Order added: ' + (i+1);
                info+= '<br>Cell Utility:<br>' + obj.geocast_query.utilities[i][0];
                info+= '<br>Current GeoCast Utility:<br>' + obj.geocast_query.utilities[i][1];
                info+= '<br>Compactness:<br>' + obj.geocast_query.compactnesses[i];
                info+= '<br>Distance:<br>' + obj.geocast_query.distances[i];
                info+= '<br>Area:<br>' + obj.geocast_query.areas[i];
                info+= '<br>Worker Counts:<br>' + obj.geocast_query.worker_counts[i];
                
                infoWindow.setContent(info);
                infoWindow.setPosition(event.latLng);

                infoWindow.open(map);
            });
           
        }
        
        
        /*The following function is to specify action to be performed when an event
         *happened on a marker.
         *
         *When a marker is clicked, the geocast_query for the task the marker
         *represent will be issued and visuallized on map 
         */
        function Marker_Click_Actions(marker, map, infoWindow, html) {    
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
                var center = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
                map.panTo(center);
            });
            
        }
       
       
        var Gowalla_boundary;
        var Yelp_boundary;
        var Gowalla_vertices = new Array();
        var Yelp_vertices = new Array();
       
        /*
         * Show_Boundary is to show/hide boundary of the dataset
         */
        function Show_Boundary(){
            var Gowalla_Button = document.getElementById("Boundary");
            var json_boundary ;
            var boundary='blank';
            var url1 ='http://geocrowd2.cloudapp.net/dataset';
           
            if (Gowalla_Button.value=="Show Boundary") {
                $.getJSON('http://whateverorigin.org/get?url=' + encodeURIComponent(url1) + '&callback=?',
                function(data){
                    json_boundary = data.contents;
                    boundary = JSON.parse(json_boundary);
                    Gowalla_Button.value = "Hide Boundary";
                    Gowalla_vertices[0] = new google.maps.LatLng(boundary.boundaries[0],boundary.boundaries[1]);
                    Gowalla_vertices[1] = new google.maps.LatLng(boundary.boundaries[0],boundary.boundaries[3]);
                    Gowalla_vertices[2] = new google.maps.LatLng(boundary.boundaries[2],boundary.boundaries[3]);
                    Gowalla_vertices[3] = new google.maps.LatLng(boundary.boundaries[2],boundary.boundaries[1]);
           
                    Gowalla_vertices[4] = new google.maps.LatLng(boundary.boundaries[0],boundary.boundaries[1]);
                    Gowalla_boundary = new google.maps.Polyline({
                        path:Gowalla_vertices,
                        strokeColor:"#FF0000",
                        strokeOpacity:0.8,
                        strokeWeight:2
                   
                    });
                    Gowalla_boundary.setMap(map);
                    infoWindow = new google.maps.InfoWindow();
                    google.maps.event.addListener(Gowalla_boundary, 'click', function(event) {
                        var boundaryinfo = 'dataset:<br>' + boundary.dataset + '<br>workers counts:<br>'+boundary.worker_count;
                        infoWindow.setContent(boundaryinfo);   
                        infoWindow.setPosition(event.latLng);
                        infoWindow.open(map);
                    });
                   
               
                });
               
            }
            else {Gowalla_Button.value = "Show Boundary";
                Gowalla_boundary.setMap(null);
            }
                
           
           
        }
        
          
        
        /*
         * This is for 1first function: input coordinates to a text box, hit button
         * and visualize
         * Query function is trigerred when the GeoCastQuery button is clicked.
         * It takes the coordinate input by the users and then visualize geocast query
         * for task at that specific location
         */
        function Query()
        {
            url = 'http://geocrowd2.cloudapp.net/geocast/' + document.forms["input"]["coordinate"].value;
            GeoCast_Query(url);
        
       
            var coor = document.forms["input"]["coordinate"].value;
            var lat_lng = coor.split(",");
            var task_point = new google.maps.LatLng(lat_lng[0],lat_lng[1]);
            var marker = new google.maps.Marker({
                map: map,
                position: task_point,
                icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
            });
            var infoWindow = new google.maps.InfoWindow;    
            Marker_Click_Actions(marker, map, infoWindow, 
            document.forms["input"]["coordinate"].value);
            marker.setMap(map);
            map.panTo(task_point);
               
        }
                
              
        /*
         *this is for the 2nd function: select a task from dropdown list,
         *hit button and visuallize
         * Visualize_Task_Seleclted is triggered when user click on Visualize
         * button after choosing a coordinate from a dropdown list.
         * The function will then visualize geocast query
         * for task at the selected location
         */
        function Visualize_Task_Selected(){
            var coor_seleted = document.getElementById("task_name");
            var location = coor_seleted.options[coor_seleted.selectedIndex].text;
            url = 'http://geocrowd2.cloudapp.net/geocast/' + location;
            GeoCast_Query(url);
        
       
            var lat_lng = location.split(",");
            var task_point = new google.maps.LatLng(lat_lng[0],lat_lng[1]);
            var marker = new google.maps.Marker({
                map: map,
                position: task_point,
                icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
            });
            var infoWindow = new google.maps.InfoWindow;    
            Marker_Click_Actions(marker, map, infoWindow, 
            document.forms["input"]["coordinate"].value);
            marker.setMap(map);
            map.panTo(task_point);
                    
                    
        }
      
       

       
     

       
        

        //]]>

    </script>

</head>

<body onload="load()">
    <div style="width: 100%;overflow:auto;">

        <div style="float:left;">

            <form name="input" action="firstPhp.php" onsubmit="Query(); return false">
                Coordinates <input type="text" name="coordinate"><br>
                <input type="submit" value="GeoCastQuery">
            </form>

            <script>
               
            </script>  



            <form action="firstPhp.php" method="post" name="Tasks_History" onsubmit="Visualize_Task_Selected(); return false">
                <select name="task_name" id="task_name">
                    <option selected="selected">Please Choose One:</option>
                    <?php
// define file
                    //the following code segment is to load coordinates from txt file to a drop down list
                    $file = 'test.txt';

                    $handle = @fopen($file, 'r');
                    if ($handle) {
                        while (!feof($handle)) {
                            $line = fgets($handle, 4096);
                            $item = explode('|', $line);
                            echo '<option value="' . $item[0] . '">' . $item[0] . '</option>' . "\n";
                        }
                        fclose($handle);
                    }
                    ?>
                </select>
                <input type="submit" name="submit" value="Visualize" />
            </form>

            <input type="button" value="Show Boundary" id="Boundary"
                   onClick="Show_Boundary()">
            <br>





            </input>      
        </div>
        <div style="float:left;">
            <div id="map" style="width: 680px; height: 600px"></div>
        </div>
        <div style="float:left;">
            <form name="input" action="firstPhp.php" onsubmit="Query(); return false">
                Setting Menu Come Here<br>         
                Setting Menu Come Here<br>
                Setting Menu Come Here<br>
                Setting Menu Come Here<br>
                Setting Menu Come Here<br>
                Setting Menu Come Here<br>
                Setting Menu Come Here<br>
                Setting Menu Come Here<br>



            </form>
        </div>
    </div>







</body>




</html>