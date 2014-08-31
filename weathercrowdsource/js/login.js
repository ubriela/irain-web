$(document).ready(function(){
    var map;
    var markers = [];
    var markersnone = [];
    var markersrain = [];
    var markerssnow = [];
    var none = baseurl+"img/mark_none_ic.png";
    var rain = baseurl+"img/mark_rain_ic.png";
    var snow = baseurl+"img/mark_snow_ic.png";
    var gridsize = 10;
    var noneStyle = [{textColor: 'yellow',fontSize: 20, url: none,height: 37,width: 32}];
    var rainStyle = [{textColor: 'yellow',fontSize: 20, url: rain,height: 37,width: 32}];
    var snowStyle = [{textColor: 'yellow',fontSize: 20, url: snow,height: 37,width: 32}];
    var noneOptions = {gridSize: gridsize,styles: noneStyle};
    var rainOptions = {gridSize: gridsize,styles: rainStyle};
    var snowOptions = {gridSize: gridsize,styles: snowStyle};
    var xhr = null;
    var mcnone;
    var mcrain;
    var mcsnow;
    var iw1 = new google.maps.InfoWindow({
        content: "Home For Sale"                                
    });
    function center(select){
        width = screen.width;
        height = screen.height;
        top = (height-$('#'+select).height())/2;
        $('#'+select).css('margin',''+top+' auto');
    }
    function hideall(){
        $('#overlay').hide();
        $('#loginform').hide();
        $('#registerform').hide();
    }
    hideall();
    function rndStr() {
        x=Math.random().toString(36).substring(7).substr(0,5);
        while (x.length!=5){
            x=Math.random().toString(36).substring(7).substr(0,5);
        }
        return x;
    }
    function getmarker(SW_lat,SW_lng,NE_lat,NE_lng,clunone,clurain,clusnow){
        var number = $('#type').val();
        for (var i = 0, marker; marker = markers[i]; i++) {
            marker.setMap(null);
        }
        markers = [];
        markersnone = [];
        markersrain = [];
        markerssnow = [];
        if( xhr != null ) {
            xhr.abort();
            xhr = null;
        }
        var now = new Date();
        now.setDate(now.getDate() - number); 
        var from = now.getFullYear()+"-"+(now.getMonth()+1)+"-"+now.getDate()+' 00:00:00';
        var to = now.getFullYear()+"-"+(now.getMonth()+1)+"-"+now.getDate()+' 24:00:00';
        xhr = $.ajax({
            type: "POST",
            url: baseurl+"index.php/weather/rectangle_report",
            data:"swlat="+SW_lat+"&swlng="+SW_lng+"&nelat="+NE_lat+"&nelng="+NE_lng+"&startdate="+from+"&enddate="+to,
            dataType: 'json',
            success: function(data){
                $.each(data, function(i, item) {
                    var location = new google.maps.LatLng(item.lat, item.lng);
                    var icons =none;
                    if(item.response_code==1){
                        icons=rain;
                    }
                    if(item.response_code==2){
                        icons=snow;
                    }
                    var image = {
                        url: icons,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(9, 18),
                        scaledSize: new google.maps.Size(20, 20)
                    };
                    var marker = new MarkerWithLabel({
                        map: map,
                        icon: image,
                        position: location            
                    });        
                    markers.push(marker);
                    if(item.response_code==1){
                        markersrain.push(marker);
                    }
                    if(item.response_code==2){
                        markerssnow.push(marker);
                    }
                    if(item.response_code==0){
                        markersnone.push(marker);
                    } 
                });
                           clunone.clearMarkers();
                           clunone.addMarkers(markers); 
                             
                           clurain.clearMarkers();
                           clurain.addMarkers(markers); 
                           
                           clusnow.clearMarkers();
                           clusnow.addMarkers(markers);    
            }
        });  
                   
    }
    function initialize() {
      var myLatlng = new google.maps.LatLng(40.71278369999998, -74.00594130000002);
      var zoom = 4;
      var mapOptions = {
          minZoom:zoom,
          zoom:zoom,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          scrollwheel: true,
          disableDoubleClickZoom: false,
          disableDefaultUI: false
      };
      map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
        mcnone = new MarkerClusterer(map,markersnone,noneOptions);
        mcrain = new MarkerClusterer(map,markersrain,rainOptions); 
        mcsnow = new MarkerClusterer(map,markerssnow,snowOptions);   
      // Create the search box and link it to the UI element.
      var input = /** @type {HTMLInputElement} */(document.getElementById('pac-input'));
      //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
      var searchBox = new google.maps.places.SearchBox(
        /** @type {HTMLInputElement} */(input));
      // [START region_getplaces]
      // Listen for the event fired when the user selects an item from the
      // pick list. Retrieve the matching places for that item.
      google.maps.event.addListener(searchBox, 'places_changed', function() {
        var places = searchBox.getPlaces();
        if (places.length == 0) {
          return;
        }
        for (var i = 0, marker; marker = markers[i]; i++) {
          marker.setMap(null);
        }
        // For each place, get the icon, place name, and location.
        markers = [];
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0, place; place = places[i]; i++) {
          bounds.extend(place.geometry.location);
        }
        map.fitBounds(bounds);
        map.setZoom(6);
        var SW_lat = map.getBounds().getSouthWest().lat();
        var SW_lng = map.getBounds().getSouthWest().lng();
        var NE_lat = map.getBounds().getNorthEast().lat();
        var NE_lng = map.getBounds().getNorthEast().lng();
        getmarker(SW_lat,SW_lng,NE_lat,NE_lng,mcnone,mcrain,mcsnow);
      });
      // [END region_getplaces]
      // Bias the SearchBox results towards places that are within the bounds of the
      // current map's viewport.
      google.maps.event.addListener(map, 'bounds_changed', function() {
        var bounds = map.getBounds();
        searchBox.setBounds(bounds);
        var SW_lat = map.getBounds().getSouthWest().lat();
        var SW_lng = map.getBounds().getSouthWest().lng();
        var NE_lat = map.getBounds().getNorthEast().lat();
        var NE_lng = map.getBounds().getNorthEast().lng();
        getmarker(SW_lat,SW_lng,NE_lat,NE_lng,mcnone,mcrain,mcsnow);
        google.maps.event.clearListeners(map,'bounds_changed'); 
      });
      google.maps.event.addListener(map, 'dragend', function() {
        var SW_lat = map.getBounds().getSouthWest().lat();
        var SW_lng = map.getBounds().getSouthWest().lng();
        var NE_lat = map.getBounds().getNorthEast().lat();
        var NE_lng = map.getBounds().getNorthEast().lng();
        getmarker(SW_lat,SW_lng,NE_lat,NE_lng,mcnone,mcrain,mcsnow);                  
      });      
      google.maps.event.addListener(map, 'click', function(event){                    
      });
      google.maps.event.addListener(map, 'zoom_changed', function() {
        
        //getmarker(SW_lat,SW_lng,NE_lat,NE_lng,mcnone,mcrain,mcsnow);
      });
                                 
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    
    $('.btnback').click(function(){
        hideall();
    });
    $('#showlogin').click(function(){
       $('#overlay').show();
       $('#loginform').show(200); 
    });
    $('#showregister').click(function(){
       $('#overlay').show();
       $('#registerform').show(200); 
    });
    $('#btnlogin').click(function(){
        var username = $('#username').val();
        var hashpass = SHA512($('#password').val());
        $.ajax({
            type: 'POST',
            url: baseurl+'index.php/user/login',
            data: "username="+username+"&password="+hashpass,
            success:function(data){
                if(data.status=='success'){
                    window.location= baseurl+'index.php/home';
                }else{
                
                    $.notify('Invalid username or password!','warn');
                }    
        }
        });
    });
    $('#btnregister').click(function(){
        var res_username = $('#resusername').val();
        //var res_email = $('#email').val();
        var res_pass = $('#respassword').val();
        //var rppass = $('#rppassword').val();
        var hasspass = SHA512(res_pass);
        var channel = 'iRain_'+res_username;
            if(res_username==''){
                alert('Please enter username!');
                return;
            }
            if(res_pass==''){
                alert('Please enter password');
                return;
            }
            $.post(baseurl+'index.php/user/checkusername',{username:res_username},function(data){
                if(data.status=='success'){
                    $.post(baseurl+'index.php/user/register',{username:res_username,email:rndStr()+'@test.com',password:hasspass,repeatpw:hasspass,channelid:channel},function(data){
                        if(data.status=='success'){
                            $.notify('Your account has been create','success');
                            hideall();
                            $('#overlay').show();
                            $('#loginform').show();
                            $('#username').val(res_username);
                            $('#password').val(res_pass);
                        }
                    });
                    }else{
                        $.notify('Username already exists','warn');
                        return;
                    }
            });
                
                
    });
    $('#type').change(function(){
        var SW_lat = map.getBounds().getSouthWest().lat();
        var SW_lng = map.getBounds().getSouthWest().lng();
        var NE_lat = map.getBounds().getNorthEast().lat();
        var NE_lng = map.getBounds().getNorthEast().lng();
        getmarker(SW_lat,SW_lng,NE_lat,NE_lng,mcnone,mcrain,mcsnow);
    });
})