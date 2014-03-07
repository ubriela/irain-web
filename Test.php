<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Heatmaps</title>
        <style>
            html, body, #map-canvas {
                height: 100%;
                margin: 0px;
                padding: 0px
            }

        </style>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=visualization"></script>
        <script>

            var map, heatmap_Gowalla, heatmap_Yelp;
            var Gowalla =[];
            var Yelp =[];
            
            function readfile(filename, output)
            {
               
                var txtFile;
                if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    txtFile = new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    txtFile = new ActiveXObject("Microsoft.XMLHTTP");
                }
            txtFile.open("GET",filename,false);
            txtFile.send();
            var txtDoc=txtFile.responseText;
            
            var lines = txtDoc.split("\r\n"); // values in lines[0], lines[1]...
            var single_line = lines[0].split("\n");
           
                
            for (var i=0; i<single_line.length; i++){
                var temp = single_line[i].split("-");
                var lat_lng =  temp[0]+",-"+temp[1];
                   
                var coordinate = lat_lng.split(",");
                output[i] = new google.maps.LatLng(parseFloat(coordinate[0]), parseFloat(coordinate[1]));
            }
                
                
       
                
     
        }


        function initialize() {
            var mapOptions = {
                zoom: 13,
                center: new google.maps.LatLng(37.76822, -122.44297),
                mapTypeId: 'roadmap'
            };

            readfile("gowalla_SF.dat", Gowalla);
            readfile("yelp.dat", Yelp);
            //readfile();


            map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);

            var Gowalla_pointArray = new google.maps.MVCArray(Gowalla);
            var Yelp_pointArray = new google.maps.MVCArray(Yelp);


            heatmap_Gowalla = new google.maps.visualization.HeatmapLayer({
                data: Gowalla_pointArray
            });
            heatmap_Yelp = new google.maps.visualization.HeatmapLayer({
                data: Yelp_pointArray
            });

            heatmap_Gowalla.setMap(map);
            heatmap_Yelp.setMap(map);

        }

        function toggleGowalla() {
            heatmap_Gowalla.setMap(heatmap_Gowalla.getMap() ? null : map);

        }

        function toggleYelp() {
            heatmap_Yelp.setMap(heatmap_Yelp.getMap() ? null : map);

        }
        google.maps.event.addDomListener(window, 'load', initialize);

        </script>
    </head>

    <body>
        <div id="panel">
            <button onclick="toggleGowalla()">Gowalla Heatmap</button>
            <button onclick="toggleYelp()">Yelp Heatmap</button>

        </div>
        <div id="map-canvas"></div>
    </body>
</html>