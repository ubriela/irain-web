$(document).ready(function(){
    var currentlat=0;
    var currentlng=0;
    var currentAddress = "";
    var adminlat =0;
    var adminlng = 0;
    var taskid =0;
    var map;
    var code=0;
    var markers = [];
    var mymarker = null;
    var xhr = null;
    var xhr1 = null;
    var none = baseurl+"img/mark_none_ic.png";
    var rainlv3 = baseurl+"img/mark_rain_ic_lv3.png";
    var rainlv2 = baseurl+"img/mark_rain_ic_lv2.png";
    var rainlv1 = baseurl+"img/mark_rain_ic_lv1.png";
    var snowlv3 = baseurl+"img/mark_snow_ic_lv3.png";
    var snowlv2 = baseurl+"img/mark_snow_ic_lv2.png";
    var snowlv1 = baseurl+"img/mark_snow_ic_lv1.png";
    var deleteArray = new Array();
    var iw1;
    var playanimate;
    var arraytiled = [];
    var lagtime;
    var selected = 24;
    var showccs = true;
    var animSpeed = 1000;
    var weatherbyhour = false;
    var objectPlace;
    $('#newtask').hide();
    hideall();
    $.ajax({
        type:'POST',
        url: baseurl+'index.php/weather/getlagtime',
        success: function(data){
            var now = new Date();
            var GMTdate = new Date(now.valueOf() + now.getTimezoneOffset() * 60000);
            GMTdate.setHours(GMTdate.getHours() - data -1);
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
            //alert(GMTdate.toLocaleDateString());
            
        }
     });
     function toMylocation(){
                var mylocation = new google.maps.LatLng( $('#uplocation_lat').val(),  $('#uplocation_lng').val());
                map.panTo(mylocation);
    }
    function currentlocation(){
        $.post(baseurl+'index.php/requester/currentlocation',function(data){
            if(data.status=='success'){
                var arrayjson = data.msg;
                $('#uplocation_lat').val(arrayjson.lat);
                $('#uplocation_lng').val(arrayjson.lng);
                $('#uplocation_address').val(arrayjson.address);
                $('#locationweather').val(arrayjson.address);
                //alert(currentAddress);
                toMylocation();
            }
        });
    }
    
    function response(level){
        $('#loading').show()
        var timeresponse = $('#timeresponse').val();
        var now = new Date((new Date())*1-1000*3600*timeresponse);
        var GMTdate = new Date(now.valueOf() + now.getTimezoneOffset() * 60000); 
        var currenttime = GMTdate.getFullYear()+'-'+(GMTdate.getMonth()+1)+'-'+GMTdate.getDate()+' '+GMTdate.getHours()+':'+GMTdate.getMinutes()+':'+GMTdate.getSeconds();
        var lat = $('#uplocation_lat').val();
        var lng = $('#uplocation_lng').val();
        var address = $('#uplocation_address').val();
        if(lat ==0 || lng == 0 || address ==""){
             $('#pac-input').notify("Please update your location first",{position:"bottom center"},"warn");
             return;   
        }
        $.post(baseurl+'index.php/worker/task_response',{taskid:taskid,responsecode:code,responsedate:currenttime,level:level,lat:lat,lng:lng,address:address},function(data){
           if(data.status=='success'){
                hideall();
                $.notify('Thanks for response','success');
                taskid=0;
                $('#newtask').hide();
                $('#responsetitle').val('');
                $('#btnresponse').attr('disabled',true);
                $('#responselocation').val('');
                code=0;    
           }else{
                $.notify('error','error');
                $('#loading').hide();
           }
        });
    }
    function report(level){
        $('#loading').show()
        var timeresponse = $('#timeresponse1').val();
        var now = new Date((new Date())*1-1000*3600*timeresponse);
        var GMTdate = new Date(now.valueOf() + now.getTimezoneOffset() * 60000);
        var currenttime = GMTdate.getFullYear()+'-'+(GMTdate.getMonth()+1)+'-'+GMTdate.getDate()+' '+GMTdate.getHours()+':'+GMTdate.getMinutes()+':'+GMTdate.getSeconds();
        var lat = $('#uplocation_lat').val();
        var lng = $('#uplocation_lng').val();
        var address = $('#uplocation_address').val();
        if(lat ==0 || lng == 0 || address ==""){
             $('#weather').notify("Please update your location first",{position:"right middle",className:'warn'});
             return;   
        }
        $.post(baseurl+'index.php/weather',{lat:lat,lng:lng,code:code,time:currenttime,level:level,address:address},function(data){
           if(data.status=='success'){
                hideall();
                $('#weather').notify("Thanks for your report",{position:"right middle",className:'success'});
                //$('#locationweather').val('');
                code=0;    
           }else{
                $('#weather').notify("error",{position:"right middle",className:'error'});
                $('#loading').hide();
           }
        });
    }
    
    data24h(arraytiled);
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
                        getObjectPlace(lat,lng);
                        
                        $('#overlay').show();
                        $('#containerposttask').show();     
                                                       
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
    function loadinfo(){
        $.post(baseurl+'index.php/user/getAllinfo',function(data){
            if(data.status=='success'){
                $('#containerinfo').html('');
                var arrayjson = data.msg;
                $.each(arrayjson, function(i, item) {
                    var cellID = '<td class=center>'+item.userid+'</td>';
                    var cellUsername = '<td>'+item.username+'</td>';
                    var cellCreate = '<td class=center>'+item.created_date+'</td>';
                    var cellnumre = '<td class=center>'+item.numrequest+'</td>';
                    var cellnumres = '<td class=center>'+item.numresponse+'</td>';
                    $('#containerinfo').append('<tr>'+cellID+cellUsername+cellCreate+cellnumre+cellnumres+'</tr>');
                });
            } 
        });   
    }
    function getTask(){
        
        $.post(baseurl+'index.php/worker/gettask',function(data){
            if(data.status=='success'){
                var arrayjson = data.msg;
                if(arrayjson.taskid==taskid){
                    return;
                }else{
                    taskid = arrayjson.taskid;
                    $('#newtask').show();

                    $('#responselocation').val(arrayjson.place);
                    document.getElementById('xyz').play();
                    $('#newtask').notify("Please report weather at your location. Thank you!",{position:"right middle",className:'success'});

               
                }   
            }else{
                $('#newtask').hide();
                taskid = 0;
            }
        });
    }
    getTask();
    setInterval(function(){getTask()},10000);
    function hideall(){
        $('#containersetting').hide();
        $('#overlay').hide();
        $('#containerabout').hide();
        $('#containertaskmanager').hide();
        $('#containerposttask').hide();
        $('#loading').hide();
        $('#btnposttask').attr('disabled',true);
        $('#containerresponsetask').hide();
        $('#containerlocation').hide();
        $('#conlevel').hide();
        $('#containerweather').hide();    
    }
    function loadpendingtask(){
        $('#tabletask').html('');
        var tz = jstz.determine(); 
        var now = new Date();
        var timezone = tz.name();
        var GMTdate = new Date(now.valueOf() + now.getTimezoneOffset() * 60000); 
        var currenttime = GMTdate.getFullYear()+'-'+(GMTdate.getMonth()+1)+'-'+GMTdate.getDate()+' '+GMTdate.getHours()+':'+GMTdate.getMinutes()+':'+GMTdate.getSeconds();
        $.post(baseurl+'index.php/requester/list_pending_task',{time:currenttime,timezone:timezone},function(data){
            $('#tabletask').html(data);
        });
    }
    function loadcompletedtask(){
        $('#tabletask').html('');
        var tz = jstz.determine(); 
        var timezone = tz.name();
        $.post(baseurl+'index.php/requester/list_completed_task',{timezone:timezone},function(data){
            $('#tabletask').html(data);
        });
    }
    function loadexpiredtask(){
        $('#tabletask').html('');
        var tz = jstz.determine();
        var now = new Date();
        var timezone = tz.name();
        var GMTdate = new Date(now.valueOf() + now.getTimezoneOffset() * 60000); 
        var currenttime = GMTdate.getFullYear()+'-'+(GMTdate.getMonth()+1)+'-'+GMTdate.getDate()+' '+GMTdate.getHours()+':'+GMTdate.getMinutes()+':'+GMTdate.getSeconds();
        $.post(baseurl+'index.php/requester/list_expired_task',{time:currenttime,timezone:timezone},function(data){
            $('#tabletask').html(data);
        });
    }
    function loadTasktype(type){
        if(type==0){
            loadpendingtask();
            $('#btndel').hide();
        }
        if(type==1){
            loadcompletedtask();
            $('#btndel').show();
        }
        if(type==2){
            loadexpiredtask();
           $('#btndel').show();
        }
    }
    $.notify.defaults({
        autoHide: true,
        autoHideDelay: 1500,
        globalPosition: 'top center',
    });
    loadTasktype(0);
    hideall();
    $('#refresh').click(function(){
       var type = $('#loadtype').val();
       loadTasktype(type); 
    });
  
    


    function initialize() {  
      var myLatlng = new google.maps.LatLng(38, -97);
      var zoom = 4;
      var mapOptions = {
        minZoom:zoom,
        zoom:zoom,
        center: myLatlng,
         
        mapTypeId: google.maps.MapTypeId.HYBRID,
        scrollwheel: true,
        disableDoubleClickZoom: false,
        disableDefaultUI: false,
        mapTypeControl: true,
        panControl: false,
        zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.RIGHT_CENTER
                
            },
        streetViewControl: false
        
      };
      map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
      
      getmarker(0,0,0,0,24);
      //get3dayMarker();
      currentlocation();
      
      // Create the search box and link it to the UI element.
      var input = /** @type {HTMLInputElement} */(document.getElementById('pac-input'));
      var input2 = (document.getElementById('uplocation_address'));
      var input3 = (document.getElementById('locationweather'));
       //var input4 = /** @type {HTMLInputElement} */(document.getElementById('pac-input1'));
      //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input4);
      var searchBox = new google.maps.places.SearchBox(
        /** @type {HTMLInputElement} */(input));
      var searchBox2 = new google.maps.places.Autocomplete((input2));
      var searchBox3 = new google.maps.places.Autocomplete((input3));
      google.maps.event.addListener(searchBox3, 'place_changed', function () {
        var place = searchBox3.getPlace();
        adminlat = place.geometry.location.lat();
        adminlng = place.geometry.location.lng();
      });
      google.maps.event.addListener(searchBox2, 'place_changed', function () {
        var place = searchBox2.getPlace();
         $('#uplocation_lat').val(place.geometry.location.lat()); 
        $('#uplocation_lng').val(place.geometry.location.lng());
        //alert(currentlat+" "+currentlng);
        //getObjectPlace(currentlat,currentlng);
        $('#btnup').attr('disabled',false);
      });
      // [START region_getplaces]
      // Listen for the event fired when the user selects an item from the
      // pick list. Retrieve the matching places for that item.
      google.maps.event.addListener(searchBox, 'places_changed', function() {
         var places = searchBox.getPlaces();
         if (places.length == 0) {
              return;
         }
         
        // For each place, get the icon, place name, and location.
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0, place; place = places[i]; i++) {
            
             // Create a marker for each place.
             var marker = new MarkerWithLabel({
                    map: map,
                    labelVisible:false,
                    title:place.name,
                    position: place.geometry.location               
                });
           
            google.maps.event.addListener(marker, 'rightclick', function(event){
                var getlatlng = event.latLng;
                var lat = getlatlng.lat();
                var lng = getlatlng.lng();
                $('#lat').val(lat);
                $('#lng').val(lng);
                getObjectPlace(lat,lng);
                
                $('#overlay').show();
                $('#containerposttask').show();     
                                       
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
        var bounds = map.getBounds();
        searchBox.setBounds(bounds);
        var SW_lat = map.getBounds().getSouthWest().lat();
        var SW_lng = map.getBounds().getSouthWest().lng();
        var NE_lat = map.getBounds().getNorthEast().lat();
        var NE_lng = map.getBounds().getNorthEast().lng();
        //getmarker(SW_lat,SW_lng,NE_lat,NE_lng,24,1);
        //google.maps.event.clearListeners(map,'bounds_changed'); 
      });
      google.maps.event.addListener(map, 'dragend', function() {
        if(mymarker!=null){
            mymarker.setMap(null);
        }
                        
      });
      
      google.maps.event.addListener(map, 'rightclick', function(event){
                var getlatlng = event.latLng;
                var lat = getlatlng.lat();
                var lng = getlatlng.lng();
                $('#lat').val(lat);
                $('#lng').val(lng);
                getObjectPlace(lat,lng);
                
                $('#overlay').show();
                $('#containerposttask').show();   
                           
      });
      google.maps.event.addListener(map, 'zoom_changed', function() {
            if (map.getZoom()>=7) {
                $('#pac-input').notify("no satellite rainfall data at current zoom level",{position:"bottom center"},'warn')
                
             }
       
      });
      google.maps.event.addListener(map, 'ide', function() {
        
      });
       //map.overlayMapTypes.push(arraytiled[24]);
      //map.overlayMapTypes.push(arraytiled[25]);
      //map.overlayMapTypes.push(arraytiled[26]);
                               
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    
    $('.btnback').click(function(){
        hideall();
        return;
    });
    
    $('#logout').click(function(){
        
       tooltip.pop(this, '#exit',{ sticky:true, position:4,offsetX:50,offsetY: -100,duration:0});
       
    });
    $('#no').click(function(){
        tooltip.hide();
    });
    $('#yes').click(function(){
       window.location = baseurl+'index.php/home/logout'; 
    });
    $('#showtask').click(function(){
       hideall();
       $('#overlay').show();
       $('#btndel').hide();
       $('#containertaskmanager').show(200); 
    });
    $('#showabout').click(function(){
         $('#overlay').show();
         $('#containerabout').show(200);
    });
    $('#showresponse').click(function(){
        hideall();
        if(taskid==0){
            $.notify.defaults({ className: 'warn' });
            $('#showresponse').notify('You have no task!',{position: "right middle" });
        }else{
             $('#overlay').show();
             $('#containerresponsetask').show(200);
        }
       
    });
    $(document).on('click',"#btnposttask",function(){
        $('#loading').show();
        var lat = $('#lat').val();
        var lng = $('#lng').val();
        var type = $('#typequery').val();
                     //var place = $('#location').val();
        var now = new Date();
        var GMTdate = new Date(now.valueOf() + now.getTimezoneOffset() * 60000);
        var tomorrow = new Date();
        tomorrow.setDate(now.getDate() + 1);
        var GMTtomorrow = new Date(tomorrow.valueOf() +tomorrow.getTimezoneOffset() * 60000);
        var requestdate = GMTdate.getFullYear()+'-'+(GMTdate.getMonth()+1)+'-'+GMTdate.getDate()+' '+GMTdate.getHours()+':'+GMTdate.getMinutes()+':'+GMTdate.getSeconds();
        var enddate = GMTtomorrow.getFullYear()+'-'+(GMTtomorrow.getMonth()+1)+'-'+GMTtomorrow.getDate()+' '+GMTtomorrow.getHours()+':'+GMTtomorrow.getMinutes()+':'+GMTtomorrow.getSeconds();
        var radius = $('#radius').val();
        $.ajax({
            type: 'POST',
            url: baseurl+'index.php/requester/task_request',
            data: 'lat='+lat+'&lng='+lng+'&requestdate='+requestdate+'&startdate='+requestdate+'&enddate='+enddate+'&type='+type+'&radius='+radius,
            success:function(data){
                if(data.status=='success'){
                    hideall();
                    $.notify(' Successful post','success');
                }else{
                    $('#loading').hide();
                    $.notify('Error','error');             
                }
            }
        });
    });
    $('#loadtype').change(function(){
       var type = $(this).val();
       loadTasktype(type);
    });
    
    $('#showadmin').click(function(){
       hideall();
       loadinfo();
       $('#overlay').show();
       $('#adminboard').show();
        
    });
    $('#adminrefresh').click(function(){
        loadinfo();
    });
    $(document).on('click','.check',function(){
        var member = $(this);
        var taskid = member.attr('id');
        if($(this).is(':checked')){
            deleteArray.push(taskid);    
        }else{
            var i = deleteArray.indexOf(taskid);
            deleteArray.splice(i,1);          
        }    
    }); 
    $('#btndel').click(function(){
        var type = $('#loadtype').val();
        var now = new Date();
        var GMTdate = new Date(now.valueOf() + now.getTimezoneOffset() * 60000); 
        var currenttime = GMTdate.getFullYear()+'-'+(GMTdate.getMonth()+1)+'-'+GMTdate.getDate()+' '+GMTdate.getHours()+':'+GMTdate.getMinutes()+':'+GMTdate.getSeconds();
        $.post(baseurl+'index.php/requester/delete_tasks',{type:type,time:currenttime},function(){
            loadTasktype(type);
        });
    });
    $('#setting').click(function(){
        hideall();
        $('#overlay').show();
        $('#containersetting').show(200);
    });
    
    $('#showupdate').click(function(){
       hideall();
       currentlocation();
       $('#overlay').show();
       $('#containerlocation').show(200);   
    });
    $('#adminrequest').click(function(){
         var title = 'Please report weather at your location';
         var lat = adminlat;
         var lng = adminlng;
         var now = new Date();
         var tomorrow = new Date();
         tomorrow.setDate(tomorrow.getDate() + 1);
         var requestdate = now.getFullYear()+'-'+(now.getMonth()+1)+'-'+now.getDate()+' '+now.getHours()+':'+now.getMinutes()+':'+now.getSeconds();
         var enddate = tomorrow.getFullYear()+'-'+(tomorrow.getMonth()+1)+'-'+tomorrow.getDate()+' '+tomorrow.getHours()+':'+tomorrow.getMinutes()+':'+tomorrow.getSeconds();
         var radius = 1000;
         $.ajax({
            type: 'POST',
            url: baseurl+'index.php/requester/task_request',
            data: 'title='+title+'&lat='+lat+'&lng='+lng+'&requestdate='+requestdate+'&startdate='+requestdate+'&enddate='+enddate+'&type=1'+'&radius='+radius,
            success:function(data){
                if(data.status=='success'){
                    $.notify('Request success','success');
                    $('#adminrequest').attr('disabled',true);
                }else{
                    $.notify('Request fail','error');
                }
            }
         });
    });    
    $('#btnrain').click(function(){
       code = 1;
        
       tooltip.pop(this, '#demo',{ sticky:false, position:0,offsetY: 20,duration:0 });
    }) ;  
    $('#btnnone').click(function(){ 
        code = 0;
        response(0);
    }) ;
    $('#btnsnow').click(function(){
        code = 2;
        tooltip.pop(this, '#demo',{ sticky:false, position:0,offsetY: 20,duration:0 });
    }) ;
    $('#btnrain1').click(function(){
       code = 1;
       
       tooltip.pop(this, '#demo1',{ sticky:false, position:0,offsetY: 20,duration:0 });
       $('#mcttCo').attr('style', 'left: 100px !important');
    }) ;  
    $('#btnnone1').click(function(){ 
        code = 0;
        report(0);
    }) ;
    $('#btnsnow1').click(function(){
        code = 2;
        tooltip.pop(this, '#demo1',{ sticky:false, position:0,offsetY: 20,duration:0 });
       
    }) ;
    
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
        normalBtnWeather();
       //var x=document.getElementById("#"+h);
       arraytiled[0].setOpacity(0);
       arraytiled[1].setOpacity(0);
       arraytiled[2].setOpacity(0);
       arraytiled[postiled].setOpacity(0);
       postiled = pos;
       arraytiled[postiled].setOpacity(1);
       getmarker(0,0,0,0,(27-postiled));
       $('#valueFromTo').html((27-postiled-1)+' - '+(27-postiled)+'hr');
    }
    $('#light').click(function(){
        response(0)
    });
    $('#moderate').click(function(){
        response(1)
    });
    $('#heavy').click(function(){
        response(2)
    });
    $('#light1').click(function(){
        report(0)
    });
    $('#moderate1').click(function(){
        report(1)
    });
    $('#heavy1').click(function(){
        report(2)
    });
    
    $('#weather').click(function(){
        var lat = $('#uplocation_lat').val();
        var lng = $('#uplocation_lng').val();
        var address = $('#uplocation_address').val();
        if(lat ==0 || lng == 0 || address ==""){
            
             $('#weather').notify("Please update your location first",{position:"right middle",className:'warn'});
             return;   
        }else{
            $('#overlay').show();
       
        //$("#locationweather").val(currentAddress)
            $('#containerweather').show(200); 
        }
       
    });
    
    
    $('#btnmylocation').click(function(){
       toMylocation();
        
    });
    
    $('#btnup').click(function(){
        $('#loading').show();
        var now = new Date();
        var GMTdate = new Date(now.valueOf() + now.getTimezoneOffset() * 60000); 
        var currenttime = GMTdate.getFullYear()+'-'+(GMTdate.getMonth()+1)+'-'+GMTdate.getDate()+' '+GMTdate.getHours()+':'+GMTdate.getMinutes()+':'+GMTdate.getSeconds();
        var lat =  $('#uplocation_lat').val();
        var lng =  $('#uplocation_lng').val()
        var address =  $('#uplocation_address').val();
        $.post(baseurl+'index.php/worker/location_report',{lat:lat,lng:lng,address:address},function(data){
           if(data.status=='success'){
                hideall();
                $('#showupdate').notify("Your location has been updated!",{position:'right middle',className:'success'});
               
                currentlocation();
                var mylocation = new google.maps.LatLng(lat, lng);
                mymarker.setMap(null);
                mymarker = new MarkerWithLabel({
                    map: map,
                    labelVisible:false,
                    title:address,
                    position: mylocation               
                });
                map.panTo(mylocation);
               
               $('#btnup').attr('disabled',true); 
           }else{
                $.notify("ERROR","error");
           } 
        });
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
    var postiled = 2;
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
        }
    });
    $('#typequery').change(function(){
        var type = $(this).val();
        var lat = $('#lat').val();
        var lng = $('#lng').val();
        var geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(lat, lng);
       if(type==3){
            $('#divradius').show();
       }else{
            $('#divradius').hide();
       }
        if(type==0){
            geocoder.geocode({'latLng': latlng}, function(results, status){
                if (status == google.maps.GeocoderStatus.OK){
                    if (results[0]){
                        var add= results[0].formatted_address ;
                        var  value=add.split(",");
                        count=value.length;
                        country=value[count-1];
                        state=value[count-2];
                        city=value[count-3];
                        if(city==null){
                            
                            $('#location').val(lat+", "+lng);
                        }else{
                            $('#location').val(city);
                        }
                        
                        
                    }else {
                        //alert("address not found");
                        $('#location').val('unknown');
                        $('#btnposttask').attr('disabled',true);
                    }
                }else{
                    //document.getElementById("location").innerHTML="Geocoder failed due to: " + status;
                    //alert("Geocoder failed due to: " + status);
                }
            });
        }
        if(type==1){
            geocoder.geocode({'latLng': latlng}, function(results, status){
                if (status == google.maps.GeocoderStatus.OK){
                    if (results[0]){
                        var add= results[0].formatted_address ;
                        var  value=add.split(",");
                        count=value.length;
                        country=value[count-1];
                        state=value[count-2];
                        city=value[count-3];
                        
                        if(state==null){
                           
                            $('#location').val(lat+", "+lng);
                        }else{
                            $('#location').val(state);
                        }
                        
                    }else {
                        //alert("address not found");
                        $('#location').val('unknown');
                        $('#btnposttask').attr('disabled',true);
                    }
                }else{
                    //document.getElementById("location").innerHTML="Geocoder failed due to: " + status;
                    //alert("Geocoder failed due to: " + status);
                }
            });
        }
        if(type==2){
            geocoder.geocode({'latLng': latlng}, function(results, status){
                if (status == google.maps.GeocoderStatus.OK){
                    if (results[0]){
                        var add= results[0].formatted_address ;
                        var  value=add.split(",");
                        count=value.length;
                        country=value[count-1];
                        state=value[count-2];
                        city=value[count-3];
                        
                        if(country==null){
                           
                            $('#location').val(lat+", "+lng);
                        }else{
                            $('#location').val(country);
                        }
                        
                    }else {
                        //alert("address not found");
                        $('#location').val('unknown');
                        $('#btnposttask').attr('disabled',true);
                    }
                }else{
                    //document.getElementById("location").innerHTML="Geocoder failed due to: " + status;
                    //alert("Geocoder failed due to: " + status);
                }
            });
        }
        if(type==3){
            geocoder.geocode({'latLng': latlng}, function(results, status){
                if (status == google.maps.GeocoderStatus.OK){
                    if (results[0]){
                        var add= results[0].formatted_address ;
                        var  value=add.split(",");
                        count=value.length;
                        country=value[count-1];
                        state=value[count-2];
                        city=value[count-3];
                        if(add==null){
                            $('#location').val(lat+", "+lng);
                        }else{
                            $('#location').val(add);
                        }
                        
                        
                    }else {
                        $('#location').val('unknown');
                        $('#btnposttask').attr('disabled',true);
                    }
                }else{
                    //document.getElementById("location").innerHTML="Geocoder failed due to: " + status;
                    //alert("Geocoder failed due to: " + status);
                }
            });
        }
        
    
                   //alert("Else loop" + latlng);
    
    });
    
   setTimeout(function(){
       initAnimate(map,arraytiled);
   },8000);
})
