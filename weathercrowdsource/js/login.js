$(document).ready(function(){
    var map;
    var markers = [];
    var arraytiled = [];
    var none = baseurl+"img/mark_none_ic.png";
    var rainlv3 = baseurl+"img/mark_rain_ic_lv3.png";
    var rainlv2 = baseurl+"img/mark_rain_ic_lv2.png";
    var rainlv1 = baseurl+"img/mark_rain_ic_lv1.png";
    var snowlv3 = baseurl+"img/mark_snow_ic_lv3.png";
    var snowlv2 = baseurl+"img/mark_snow_ic_lv2.png";
    var snowlv1 = baseurl+"img/mark_snow_ic_lv1.png";
    var xhr = null;
    var weatherbyhour = false;
    var iw1;
    var playanimate = null;
    var animSpeed = 1000;
    var lagtime = "00:00";
    var selected = 24;
    var showccs = true;
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
            //x.innerHTML = "Geolocation is not supported by this browser.";
            //alert("Geolocation is not supported by this browser.");
        }
    }
    function showPosition(position) {
        var mylocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        map.panTo(mylocation);
       
    }
    
    
    
    function hideall(){
        $('#overlay').hide();
        $('#loginform').hide();
        $('#registerform').hide();
    }
    function getLagTime(){
        $.ajax({
            type:'POST',
            url: baseurl+'index.php/weather/getlagtime',
            success: function(data){
                var now = new Date();
                var GMTdate = new Date(now.valueOf() + now.getTimezoneOffset() * 60000);
                GMTdate.setHours(GMTdate.getHours() - data - 1);  
                var hour = GMTdate.getHours();
                var minute = GMTdate.getMinutes();
                if(hour<10){
                    hour = 0+""+hour;
                }
                if(minute<10){
                    minute = 0+""+minute;
                }
                lagtime = hour+":"+minute;
                $('#valueLagtime').html(lagtime);
            }
         });
    }
    
    hideall();
    getLagTime();
    checkCookie();
     
     
     
    function getmarker(SW_lat,SW_lng,NE_lat,NE_lng,number){
         if(number==null){
            number=0;
        }
        for (var i = 0, marker; marker = markers[i]; i++) {
            marker.setMap(null);
        }
                    
        markers = [];
        if( xhr != null ) {
            xhr.abort();
            xhr = null;
        }
        var now = new Date();
        //now.setHours(now.getHours() - number); 
        var GMTdate = new Date(now.valueOf() + now.getTimezoneOffset() * 60000);
        GMTdate.setHours(GMTdate.getHours() - number); 
        var from = GMTdate.getFullYear()+"-"+(GMTdate.getMonth()+1)+"-"+GMTdate.getDate()+' '+GMTdate.getHours()+':'+GMTdate.getMinutes()+':'+GMTdate.getSeconds();
        if(weatherbyhour==true){
            GMTdate.setHours(GMTdate.getHours() + 1);
        }else{
            GMTdate.setHours(GMTdate.getHours() + number);
        }
        var to = GMTdate.getFullYear()+"-"+(GMTdate.getMonth()+1)+"-"+GMTdate.getDate()+' '+GMTdate.getHours()+':'+GMTdate.getMinutes()+':'+GMTdate.getSeconds();
        xhr = $.ajax({
        type: "POST",
            url: baseurl+"index.php/weather/rectangle_report",
            data:"swlat="+SW_lat+"&swlng="+SW_lng+"&nelat="+NE_lat+"&nelng="+NE_lng+"&startdate="+from+"&enddate="+to,
            dataType: 'json',
            success: function(data){
                $.each(data, function(i, item) {
                    var location = new google.maps.LatLng(item.lat, item.lng);
                    var icons=none;
                    var weather = "NONE";
                    var now = new Date();
                    var GMTdate = new Date(now.valueOf() + now.getTimezoneOffset() * 60000);
                    var namelocation = item.worker_place;
                    var responsedate = item.response_date;
                    var test = new Date(responsedate);
                    var diff = Math.abs(GMTdate - test);
                    
                    //hourtest = moment.duration(hourtest, 'milliseconds').asHours;
                    
                    if(item.response_code==0){
                        icons = none;
                        weather = "NONE";
                    }
                    if(item.response_code==1 && item.level==0){
                        icons = rainlv3;
                        weather = "LIGHT RAIN";
                    }
                    if(item.response_code==1 && item.level==1){
                        icons = rainlv2;
                        weather = "MODERATE RAIN";
                    }
                    if(item.response_code==1 && item.level==2){
                        icons = rainlv1;
                        weather = "HEAVY RAIN";
                    }
                     if(item.response_code==2 && item.level==0){
                        icons = snowlv3;
                        weather = "LIGHT SNOW";
                    }
                    if(item.response_code==2 && item.level==1){
                        icons = snowlv2;
                        weather = "MODERATE SNOW";
                    }
                    if(item.response_code==2 && item.level==2){
                        icons = snowlv1;
                        weather = "HEAVY SNOW";
                    }
                    var image = {
                        url: icons
                        
                    };
                        
                              // Create a marker for each place.
                    var marker = new MarkerWithLabel({
                        map: map,
                        icon: image,
                        labelVisible:false,
                        labelContent: namelocation,  
                        position: location               
                    });
                    google.maps.event.addListener(marker, 'rightclick', function(event){
                        var getlatlng = event.latLng;
                        var lat = getlatlng.lat();
                        var lng = getlatlng.lng();
                        $('#lat').val(lat);
                        $('#lng').val(lng);
                        $.post(baseurl+'index.php/geocrowd/getplace',{lat:lat,lng:lng},function(data){
                            $('#location').val(data);
                            $('#btnposttask').attr('disabled',false); 
                        });
                        $('#overlay').show();
                        $('#posttask').show();     
                                                       
                    });
                    google.maps.event.addListener(marker, 'mouseover', function(event){
                        var contentString = marker.get('labelContent');
                        iw1 = new google.maps.InfoWindow({
                                    content: "<p style='text-align:center;min-width:250px;min-height:30px'><b>"+namelocation+"</b><br/>"+weather+", "+msToTime(diff)+"</p>"                                
                                }); 
                        
                        iw1.open(map,this);                              
                    }); 
                    google.maps.event.addListener(marker, 'mouseout', function(event){
                        iw1.close(map,this);                               
                    });            
                                
                    markers.push(marker);
                   
                });
                         
            }
        });             
    }
    
//$('#example1').simple_progressbar({value: 25, showValue: true});
    data24h(arraytiled);
    function initialize() {
      var myLatlng = new google.maps.LatLng(38, -97);
      var zoom = 4;
      var mapOptions = {
          minZoom:zoom,
          zoom:zoom,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.HYBRID,
          panControl: false,
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.RIGHT_CENTER
                
            },
          scrollwheel: true,
          disableDoubleClickZoom: false,
         
          streetViewControl:false
          
      };
      
      map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
     
      getmarker(0,0,0,0,24);
      
      
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
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0, place; place = places[i]; i++) {
            var image = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };
             // Create a marker for each place.
            var marker = new google.maps.Marker({
                map: map,
                icon: image,
                title: place.name,
                position: place.geometry.location
            });
            bounds.extend(place.geometry.location);
        }
        map.fitBounds(bounds);
        map.setZoom(4);
        
      });
      // [END region_getplaces]
      // Bias the SearchBox results towards places that are within the bounds of the
      // current map's viewport.
      google.maps.event.addListener(map, 'bounds_changed', function() {
        
        var SW_lat = map.getBounds().getSouthWest().lat();
        var SW_lng = map.getBounds().getSouthWest().lng();
        var NE_lat = map.getBounds().getNorthEast().lat();
        var NE_lng = map.getBounds().getNorthEast().lng();
        //getmarker(SW_lat,SW_lng,NE_lat,NE_lng);
        //google.maps.event.clearListeners(map,'bounds_changed'); 
      });
      //google.maps.event.addListener(map, 'dragend', function() {
                         
      //});      
      //google.maps.event.addListener(map, 'click', function(event){                    
      //});
      google.maps.event.addListener(map, 'zoom_changed', function() {
            if (map.getZoom()>=7) {
                $('#pac-input').notify("no satellite rainfall data at current zoom level",{position:"bottom center"},'warn')
                
             }
       
      });
      //for(i=0;i<24;i++){
            //map.overlayMapTypes.push(arraytiled[i]);
      //}
      google.maps.event.addListener(map, 'ide', function() {
        
      });
      
                          
    }
    google.maps.event.addDomListener(window, 'load', initialize);
       
    $('.btnback').click(function(){
        hideall();
    });
    $('#showlogin').click(function(){
        $('#overlay').show();
       $('#loginform').show(); 
        //tooltip.pop(this, '#loginview',{ position:4,duration:0,overlay:true,offsetX:50});
    });
    $('#showregister').click(function(){
        
        $('#overlay').show();
        $('#resusername').val('');
        $('#respassword').val('');
        $('#registerform').show(); 
        
       
    });
    $.notify.defaults({
        autoHide: true,
        autoHideDelay: 1500
    });
    $('#btnlogin').click(function(){
        var username = $('#username').val();
        var password = $('#password').val();
        var flag = document.getElementById("checkremember").checked;
        login(username,password,flag);
        
    });
   
    
    $('#btnregister').click(function(){
        
        var res_username = $('#resusername').val();
        var res_pass = $('#respassword').val();
        var hasspass = SHA512(res_pass);
        var channel = 'iRain_'+res_username;
        //$.notify.defaults({ className: 'warn' });
            if(res_username==''){
                //$.notify('#resusername','Please enter username!',{position:"top center", className: 'warn'})
                 $('#resusername').notify('Please enter username!',{position:"top center",className: 'warn'});
                return;
            }
            if(res_username.length<7){
                
                 $('#resusername').notify('Username must have more than 7 character',{position:"top center",className: 'warn'});
                return;
            }
            if(res_pass==''){
                 $('#respassword').notify('Please enter password',{position:"top center",className: 'warn'});
                
                return;
            }
            if(res_pass.length<7){
                $('#respassword').notify('Password must have more than 7 character',{position:"top center",className: 'warn'});
                return;
            }
            var target = document.getElementById('registerform');
            var spinner = new Spinner().spin(target);
            $.post(baseurl+'index.php/user/checkusername',{username:res_username},function(data){
                if(data.status=='success'){
                    $.post(baseurl+'index.php/user/register',{username:res_username,password:hasspass,repeatpw:hasspass,channelid:channel},function(data){
                        if(data.status=='success'){
                            
                            hideall();
                            $('#overlay').show();
                            $('#loginform').show();
                            $('#username').val(res_username);
                            $('#password').val(res_pass);
                            
                            $('#btnlogin').notify("Your account has been create",{position:"right middle",className: 'success'});
                        }
                        
                    });
                }else{
                    $('#resusername').notify('Username already exists',{position:"top center",className: 'warn'});
                        
                }
                spinner.stop();
            });
                
                
    });
        var postiled = 2;
     $('.nodeweather').on('click',function(){
      
       var h = parseInt($(this).attr('alt'));
       
       $('.selected').removeClass('selected');
       $(this).addClass('selected');
       clearInterval(playanimate);
       playanimate = null;
       $('#ccspause').attr('src',baseurl+'img/play.png');
        $('#ccspause').attr('id','ccsplay');
        playanimate = null;
       nodeClick(h);
      
       
    });
    function nodeClick(pos){
        weatherbyhour=true;
       
       arraytiled[0].setOpacity(0);
       arraytiled[1].setOpacity(0);
       arraytiled[2].setOpacity(0);
       arraytiled[postiled].setOpacity(0);
       postiled = pos;
       getmarker(0,0,0,0,(27-postiled));
       if(showccs==true){
            arraytiled[postiled].setOpacity(1);
       }else{
            arraytiled[postiled].setOpacity(0);
       }
       
       normalBtnWeather();
       
       $('#valueFromTo').html((27-postiled-1)+' - '+(27-postiled)+'hr');
    }
    
    function play(){
        if(postiled<26){
            weatherbyhour=true;
            arraytiled[postiled].setOpacity(0);
            postiled++;
            $('#node'+(postiled-1)).removeClass('selected');
            $('#node'+postiled).addClass('selected');
            nodeClick(postiled);
            if(showccs==true){
                arraytiled[postiled].setOpacity(1);
            }else{
                arraytiled[postiled].setOpacity(0);
            }
        }else{
            arraytiled[26].setOpacity(0);
            weatherbyhour=false;
            clearInterval(playanimate);
            playanimate = null;
            getmarker(0,0,0,0,selected);
            postiled = 2;
            $('.selected').removeClass('selected');
            //nodeClick(postiled);
            $('#valueFromTo').html('0 - 24hr');
            if(selected==24){
                if(showccs==true){
                    arraytiled[0].setOpacity(1);
                    $('#btn24h').attr('src',baseurl+'img/report24hr_1.png');
                    $('#valueFromTo').html('0 - 24hr');
                }
            }
            if(selected==48){
                if(showccs==true){
                arraytiled[1].setOpacity(1);
                $('#btn48h').attr('src',baseurl+'img/report48hr_1.png')
                $('#valueFromTo').html('0 - 48hr');
            }
            }
            if(selected==72){
                if(showccs==true){
                arraytiled[2].setOpacity(1);
                $('#btn72h').attr('src',baseurl+'img/report72hr_1.png')
                $('#valueFromTo').html('0 - 72hr');
            }
            }
            $('#ccspause').attr('src',baseurl+'img/play.png');
            $('#ccspause').attr('id','ccsplay');
        }
    }
    $('#ccsback').click(function(){
        if(postiled>3){
            weatherbyhour=true;
            
            arraytiled[0].setOpacity(0);
            arraytiled[1].setOpacity(0);
            arraytiled[2].setOpacity(0);
            arraytiled[postiled].setOpacity(0);
            postiled--;
             $('#node'+(postiled+1)).removeClass('selected');
            $('#node'+postiled).addClass('selected');
            normalBtnWeather();
            nodeClick(postiled);
            
            if(showccs==true){
                arraytiled[postiled].setOpacity(1);
            }else{
                arraytiled[postiled].setOpacity(0);
            }
            
        }else{
        }
        $('#ccspause').attr('src',baseurl+'img/play.png');
        $('#ccspause').attr('id','ccsplay');
        clearInterval(playanimate);
        playanimate = null;
        
    });
    $('#ccsnext').click(function(){
        clearInterval(playanimate);
        playanimate = null;
        if(postiled<26){
            weatherbyhour=true;
            arraytiled[0].setOpacity(0);
            arraytiled[1].setOpacity(0);
            arraytiled[2].setOpacity(0);
            arraytiled[postiled].setOpacity(0);
            postiled++;
           
            $('#node'+(postiled-1)).removeClass('selected');
            $('#node'+postiled).addClass('selected');
            normalBtnWeather();
            nodeClick(postiled);
            if(showccs==true){
                arraytiled[postiled].setOpacity(1);
            }else{
                arraytiled[postiled].setOpacity(0);
            }
            
            
        }
        $('#ccspause').attr('src',baseurl+'img/play.png');
        $('#ccspause').attr('id','ccsplay');
        
    });
    $(document).on('click','#ccsplay',function(){
        $(this).attr('src',baseurl+'img/pause.png');
        $(this).attr('id','ccspause');
        $('.selected').removeClass('selected');
        if(showccs==true){
            arraytiled[postiled].setOpacity(1);
        }else{
            arraytiled[postiled].setOpacity(0);
        }
        normalBtnWeather();
        arraytiled[0].setOpacity(0);
        arraytiled[1].setOpacity(0);
        arraytiled[2].setOpacity(0);
        if(postiled==26){
            arraytiled[26].setOpacity(0);
            postiled = 2;
        }
        playanimate = setInterval(function(){play()},animSpeed);
    });
    $(document).on('click','#ccspause',function(){
        $(this).attr('src',baseurl+'img/play.png');
        $(this).attr('id','ccsplay');
        
        clearInterval(playanimate);
        playanimate = null;
    });
    $('#btn24h').on('click',function(){
        selected = 24;
        clearInterval(playanimate);
        playanimate = null;
        //
        weatherbyhour=false;
        getmarker(0,0,0,0,24);
        if(showccs==true){
             arraytiled[0].setOpacity(1);
        }
        arraytiled[1].setOpacity(0);
        arraytiled[2].setOpacity(0);
        arraytiled[postiled].setOpacity(0);
       $(this).attr('src',baseurl+'img/report24hr_1.png');
       $('#btn48h').attr('src',baseurl+'img/report48hr.png'); 
       $('#btn72h').attr('src',baseurl+'img/report72hr.png');
       
        $('.selected').removeClass('selected');
        $('#valueFromTo').html('0 - 24hr');
        $('#ccspause').attr('src',baseurl+'img/play.png');
        $('#ccspause').attr('id','ccsplay');
        postiled=2;
    });
    $('#btn48h').on('click',function(){
        selected = 48;
        clearInterval(playanimate);
        playanimate = null;
        weatherbyhour=false;
        getmarker(0,0,0,0,48);
        arraytiled[postiled].setOpacity(0);
        arraytiled[0].setOpacity(0);
        if(showccs==true){
            arraytiled[1].setOpacity(1);
        }
        arraytiled[2].setOpacity(0);
        $(this).attr('src',baseurl+'img/report48hr_1.png');
        $('#btn24h').attr('src',baseurl+'img/report24hr.png'); 
        $('#btn72h').attr('src',baseurl+'img/report72hr.png'); 
        $('.selected').removeClass('selected');
        $('#valueFromTo').html('0 - 48hr');
        
        clearInterval(playanimate);
        $('#ccspause').attr('src',baseurl+'img/play.png');
        $('#ccspause').attr('id','ccsplay');
        postiled=2;
    });
    $('#btn72h').on('click',function(){
        selected = 72;
        clearInterval(playanimate);
        playanimate = null;
        weatherbyhour=false;
        getmarker(0,0,0,0,72);
        arraytiled[postiled].setOpacity(0);
        arraytiled[0].setOpacity(0);
        arraytiled[1].setOpacity(0);
        if(showccs==true){
            arraytiled[2].setOpacity(1);
        }
       $(this).attr('src',baseurl+'img/report72hr_1.png');
       $('#btn48h').attr('src',baseurl+'img/report48hr.png'); 
       $('#btn24h').attr('src',baseurl+'img/report24hr.png'); 
       $('.selected').removeClass('selected');
       $('#valueFromTo').html('0 - 72hr');
       //$('#hourtooltip span').html(0+' - '+72+'hr');
        //tooltip.pop(this, '#hourtooltip',{ sticky:false, position:0,offsetY: 20,sticky:0,duration:0 });
        
        $('#ccspause').attr('src',baseurl+'img/play.png');
        $('#ccspause').attr('id','ccsplay');
        postiled=2;
    });
     $('#viewccs').click(function(){
       if($(this).is(":checked")){
            showccs=true;
            arraytiled[postiled].setOpacity(1);
            if(postiled<3){
                if(selected==24){
                    arraytiled[0].setOpacity(1);
                }
                if(selected==48){
                    arraytiled[1].setOpacity(1);
                }
                if(selected==72){
                    arraytiled[2].setOpacity(1);
                }
            }else{

            }
            
            
       }else{
            showccs=false;
            arraytiled[postiled].setOpacity(0);
            arraytiled[0].setOpacity(0);
            arraytiled[1].setOpacity(0);
            arraytiled[2].setOpacity(0);
       }
    });
   
    $('#speed').on('click',function(){
        var type = $(this).attr('alt');
        if(type==0){
            $(this).attr("src",baseurl+"img/speed_medium.png");
            $(this).attr("alt",1);
            animSpeed = 1000;
            if(playanimate!=null){
                clearInterval(playanimate);
                playanimate = setInterval(function(){play()},animSpeed);
            }
            
            //$('#ccspause').attr('src',baseurl+'img/play.png');
            //$('#ccspause').attr('id','ccsplay');

            
            //clearInterval(playanimate);
            //alert(animSpeed);
        }
        if(type==1){
            $(this).attr("src",baseurl+"img/speed_fast.png");
            $(this).attr("alt",2);
            animSpeed = 500;
            if(playanimate!=null){
                clearInterval(playanimate);
                playanimate = setInterval(function(){play()},animSpeed);
            }
        }
        if(type==2){
            $(this).attr("src",baseurl+"img/speed_slow.png");
            $(this).attr("alt",0);
            animSpeed = 1500;
            if(playanimate!=null){
                clearInterval(playanimate);
                playanimate = setInterval(function(){play()},animSpeed);
            }
            
            //alert(animSpeed);
        }
    });
    
   //setTimeout(function(){
        //initAnimate(map,arraytiled);
     //},7000);
     
   
    
    
    
})