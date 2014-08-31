$(document).ready(function(){
    var currentlat=0;
    var currentlng=0;
    var adminlat =0;
    var adminlng = 0;
    var taskid =0;
    var map;
    var code=0;
    var markers = [];
    var markersnone = [];
    var markersrain = [];
    var markerssnow = [];
    var xhr = null;
    var xhr1 = null;
    var none = baseurl+"img/mark_none_ic.png";
    var rain = baseurl+"img/mark_rain_ic.png";
    var snow = baseurl+"img/mark_snow_ic.png";
    var completeimage = baseurl+'img/complete.png';
    var expiredimage = baseurl+'img/expired.png';
    var deleteimage = baseurl+'img/delete.png';
    var gridsize = 10;
    var noneStyle = [{textColor: 'yellow',textSize: 21, url: none,height: 37,width: 32}];
    var rainStyle = [{textColor: 'yellow',textSize: 21, url: rain,height: 37,width: 32}];
    var snowStyle = [{textColor: 'yellow',textSize: 21, url: snow,height: 37,width: 32}];
    var noneOptions = {gridSize: gridsize,styles: noneStyle};
    var rainOptions = {gridSize: gridsize,styles: rainStyle};
    var snowOptions = {gridSize: gridsize,styles: snowStyle};
    var mcnone;
    var mcrain;
    var mcsnow;
    var scWidth = screen.width;
    var scHeight = screen.height;
    var deleteArray = new Array();
    $('#newtask').hide();
    hideall();
   
   function rndStr() {
        x=Math.random().toString(36).substring(7).substr(0,5);
        while (x.length!=5){
            x=Math.random().toString(36).substring(7).substr(0,5);
        }
        return x;
    }
    function currentlocation(){
        $.post(baseurl+'index.php/requester/currentlocation',function(data){
            var arrayjson = data.msg;
            adminlat = arrayjson.lat;
            adminlng = arrayjson.lng;
            $('#locationweather').val(arrayjson.adress); 
        });
    } 
    
    function response(level){
        $('#loading').show()
        var timeresponse = $('#timeresponse').val();
        var now = new Date((new Date())*1-1000*3600*timeresponse);
        var GMTdate = new Date(now.valueOf() + now.getTimezoneOffset() * 60000); 
        var currenttime = GMTdate.getFullYear()+'-'+(GMTdate.getMonth()+1)+'-'+GMTdate.getDate()+' '+GMTdate.getHours()+':'+GMTdate.getMinutes()+':'+GMTdate.getSeconds();
        $.post(baseurl+'index.php/worker/task_response_web',{taskid:taskid,responsecode:code,responsedate:currenttime,level:level},function(data){
           if(data.status=='success'){
                hideall();
                $.notify('Thank for your response','success');
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
        $.post(baseurl+'index.php/weather',{lat:adminlat,lng:adminlng,code:code,time:currenttime,level:level},function(data){
           if(data.status=='success'){
                hideall();
                $.notify('Thank for your report','success');
                $('#locationweather').val('');
                code=0;    
           }else{
                $.notify('error','error');
                $('#loading').hide();
           }
        });
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
                    var title = 'none'; 
                    if(item.response_code==1){
                        icons=rain;
                        title = 'Rain';
                    }
                    if(item.response_code==2){
                        icons=snow;
                        title = 'Snow';
                    }
                    var image = {
                        url: icons,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(9, 18),
                        scaledSize: new google.maps.Size(20, 20)
                    };
                        
                              // Create a marker for each place.
                    var marker = new MarkerWithLabel({
                        map: map,
                        icon: image,
                        title: title,              
                        position: location               
                    });
                    google.maps.event.addListener(marker, 'click', function(event){
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
                 $('#responsetitle').val(arrayjson.title);
                $('#responselocation').val(arrayjson.place);
                $('#newtask').show();
               
                
                
                }   
            }
        });
    }
    getTask();
    setInterval(function(){getTask()},60000);
    function hideall(){
        $('#overlay').hide();
        $('#taskmanager').hide();
        $('#posttask').hide();
        $('#loading').hide();
        $('#btnposttask').attr('disabled',true);
        $('#responsetask').hide();
        $('#adminboard').hide();
        $('#uplocationform').hide();
        $('#conlevel').hide();
        $('#upavatar').hide();
        $('#weatherform').hide();    
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
    loadTasktype(0);
    hideall();
    $('#refresh').click(function(){
       var type = $('#loadtype').val();
       loadTasktype(type); 
    });
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
      var input2 = (document.getElementById('uplocation'));
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
        currentlat = place.geometry.location.lat();
        currentlng = place.geometry.location.lng();
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
         for (var i = 0, marker; marker = markers[i]; i++) {
              marker.setMap(null);
         }
        // For each place, get the icon, place name, and location.
        markers = [];
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
            google.maps.event.addListener(marker, 'click', function(event){
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
                  
            bounds.extend(place.geometry.location);
        }
        
        map.fitBounds(bounds);
        map.setZoom(8);
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
      google.maps.event.addListener(map, 'zoom_changed', function() {  
            //getmarker();
      });
      google.maps.event.addListener(map, 'click', function(event){
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
                               
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    $('.btnback').click(function(){
        hideall();
    });
    
    $('#logout').click(function(){
        
       tooltip.pop(this, '#exit',{ sticky:true, position:4,offsetY: -100 });
       
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
       $('#taskmanager').show(200); 
    });
    $('#showresponse').click(function(){
        hideall();
        if(taskid==0){
            $.notify('You have no task!','warn');
        }else{
             $('#overlay').show();
             $('#responsetask').show(200);
        }
       
    });
    $(document).on('click',"#btnposttask",function(){
        $('#loading').show();
        var title = $('#title').val();
        var lat = $('#lat').val();
        var lng = $('#lng').val();
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
            data: 'title='+title+'&lat='+lat+'&lng='+lng+'&requestdate='+requestdate+'&startdate='+requestdate+'&enddate='+enddate+'&type=0'+'&radius='+radius,
            success:function(data){
                if(data.status=='success'){
                    hideall();
                    $.notify('Post success','success');
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
    $('#type').change(function(){
        var SW_lat = map.getBounds().getSouthWest().lat();
        var SW_lng = map.getBounds().getSouthWest().lng();
        var NE_lat = map.getBounds().getNorthEast().lat();
        var NE_lng = map.getBounds().getNorthEast().lng();
        getmarker(SW_lat,SW_lng,NE_lat,NE_lng,mcnone,mcrain,mcsnow);
    });
    $('#showupdate').click(function(){
       hideall();
       $('#uplocation').val('');
       $('#overlay').show();
       $('#uplocationform').show(200);   
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
            data: 'title='+title+'&lat='+lat+'&lng='+lng+'&requestdate='+requestdate+'&startdate='+requestdate+'&enddate='+enddate+'&type=0'+'&radius='+radius,
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
       
       tooltip.pop(this, '#demo',{ sticky:false, position:0,offsetY: 20 });
    }) ;  
    $('#btnnone').click(function(){ 
        code = 0;
        response(0);
    }) ;
    $('#btnsnow').click(function(){
        code = 2;
        tooltip.pop(this, '#demo1',{ sticky:false, position:0,offsetY: 20 });
    }) ;
    $('#btnrain1').click(function(){
       code = 1;
       
       tooltip.pop(this, '#demo1',{ sticky:false, position:0,offsetY: 20 });
    }) ;  
    $('#btnnone1').click(function(){ 
        code = 0;
        report(0);
    }) ;
    $('#btnsnow1').click(function(){
        code = 2;
        tooltip.pop(this, '#demo1',{ sticky:false, position:0,offsetY: 20 });
    }) ;
    
    
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
    $('#avatar').click(function(){
      
    });
    $('#weather').click(function(){
       $('#overlay').show();
       currentlocation();
       $('#weatherform').show(200); 
    });
    $('#btnup').click(function(){
        $('#loading').show();
        var now = new Date();
        var currenttime = now.getFullYear()+'-'+(now.getMonth()+1)+'-'+now.getDate()+' '+now.getHours()+':'+now.getMinutes()+':'+now.getSeconds();
        $.post(baseurl+'index.php/worker/location_report',{lat:currentlat,lng:currentlng,datetime:currenttime},function(data){
           if(data.status=='success'){
                hideall();
               $.notify("Your location has been update!","success",{
                    globalPosition:'top center',
                    elementPosition: 'top center'
                });
               
               $('#btnup').attr('disabled',true); 
           }else{
                $.notify("ERROR","error");
           } 
        });
    });      
})