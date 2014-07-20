$(document).ready(function(){
    var currentlat=0;
    var currentlng=0;
    var taskid =0;
    var map;
    var markers = [];
    var markersnone = [];
    var markersrain = [];
    var markerssnow = [];
    var xhr = null;
    var none = baseurl+"img/mark_none_ic.png";
    var rain = baseurl+"img/mark_rain_ic.png";
    var snow = baseurl+"img/mark_snow_ic.png";
    var completeimage = baseurl+'img/complete.png';
    var expiredimage = baseurl+'img/expired.png';
    var gridsize = 10;
    var noneStyle = [{textColor: 'red',fontSize: 15, url: none,height: 37,width: 32}];
    var rainStyle = [{textColor: 'red',fontSize: 15, url: rain,height: 37,width: 32}];
    var snowStyle = [{textColor: 'red',fontSize: 15, url: snow,height: 37,width: 32}];
    var noneOptions = {gridSize: gridsize,styles: noneStyle};
    var rainOptions = {gridSize: gridsize,styles: rainStyle};
    var snowOptions = {gridSize: gridsize,styles: snowStyle};
    var mcnone;
    var mcrain;
    var mcsnow;
    var deleteArray = new Array();
     var iw1 = new google.maps.InfoWindow({
        content: "Home For Sale"                                
    });
    $('#newtask').hide();
    hideall();
   function rndStr() {
    x=Math.random().toString(36).substring(7).substr(0,5);
    while (x.length!=5){
        x=Math.random().toString(36).substring(7).substr(0,5);
    }
    return x;
}
function getmarker(SW_lat,SW_lng,NE_lat,NE_lng,clunone,clurain,clusnow){
                    //var SW_lat = map.getBounds().getSouthWest().lat();
                    //var SW_lng = map.getBounds().getSouthWest().lng();
                    //var NE_lat = map.getBounds().getNorthEast().lat();
                    //var NE_lng = map.getBounds().getNorthEast().lng();
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
                        
                              // Create a marker for each place.
                                var marker = new MarkerWithLabel({
                                    map: map,
                                    icon: image,
                                    title: item.response_date,
                                    
                                    position: location
                                    //labelContent: "$425K",
                                   //labelAnchor: new google.maps.Point(22, 0),
                                   //labelClass: "labels", // the CSS class for the label
                                   //labelStyle: {opacity: 0.75}
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
    
        loadinfo();
    
    
    function getTask(){
        $.post(baseurl+'index.php/worker/gettask',function(data){
            if(data.status=='success'){
                var arrayjson = data.msg;
                if(arrayjson.taskid==taskid){
                    //return;
                }else{
                taskid = arrayjson.taskid;
                $('#newtask').show();
                $('#responsetitle').val(arrayjson.title);
                $.post(baseurl+'index.php/geocrowd/getplace',{lat:arrayjson.lat,lng:arrayjson.lng},function(data){
                    $('#responselocation').val(data);
                });
                
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
        $('#uplocation').hide();
    }
    function loadTasktype(type){
        $('#loading').show();
        var now = new Date();
        var currenttime = now.getFullYear()+'-'+(now.getMonth()+1)+'-'+now.getDate()+' '+now.getHours()+':'+now.getMinutes()+':'+now.getSeconds();
                $.ajax({
                   type: 'POST',
                   url: baseurl+'index.php/requester/submitted_tasks_type',
                   data:'type='+type+"&time="+currenttime,
                   success:function(data){
                    $('#containertask').html('');
                        if(data.status=='success'){
                            var arrayjson = data.msg;
                             $.each(arrayjson, function(i, item) {
                                    var there = new Date(item.enddate);
                                    //if(there>now){
                                    
                                    //var cellID = '<td>'+item.taskid+'</td>';
                                    var cellTitle = '<td>'+item.title+'</td>';
                                    //var cellLat = '<td>'+item.lat+'</td>';
                                    //var cellLng = '<td>'+item.lng+'</td>';
                                    var cellLocation = '<td></td>';
                                    var cellStart = '<td class=center>'+item.startdate+'</td>';
                                    var cellEnd = '<td class=center>'+item.enddate+'</td>';
                                    var cellComplete = '<td class=center></td>';
                                    var cellExpired = '<td class=center></td>';
                                    var cellDelete = '<td class="center"><input class="check" type="checkbox" id='+item.taskid+'></td>';
                                    if(item.iscompleted==1){
                                        cellComplete='<td class=center><img src='+completeimage+' width=30 height=30/></td>';
                                    }else{
                                        if(there<now){
                                            cellExpired = '<td class=center><img src='+expiredimage+' width=30 height=30/></td>';
                                        }
                                    }
                                    $.post(baseurl+'index.php/geocrowd/getplace',{lat:item.lat,lng:item.lng},function(data){
        cellLocation = '<td>'+data+'</td>';
        $('#containertask').append('<tr  class=active title='+item.taskid+'>'+cellTitle+cellLocation+cellStart+cellEnd+cellComplete+cellExpired+cellDelete+'</tr>'); 
        if(i==arrayjson.length-1){
                                $('#loading').hide();
                              } 
    });
    //}
                                    
                                   
                             });
                                 
                        }else{
                            $('#loading').hide();
                            $('#containertask').append('<tr  class=active><td class=center colspan="7">No tasks</td></tr>');
                        }
                   }
                });
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
  var input1 = (document.getElementById('currentlocation'));
  var input2 = (document.getElementById('uplocation'));
  //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  var searchBox = new google.maps.places.SearchBox(
    /** @type {HTMLInputElement} */(input));
    var searchBox2 = new google.maps.places.Autocomplete((input2));
  var searchBox1 = new google.maps.places.Autocomplete(
    /** @type {HTMLInputElement} */(input1));
    google.maps.event.addListener(searchBox1, 'place_changed', function () {
            var place = searchBox1.getPlace();
            currentlat = place.geometry.location.lat();
            currentlng = place.geometry.location.lng();
            $('#btnresponse').attr('disabled',false);
            

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
          bounds.extend(place.geometry.location);
        }
    
        map.fitBounds(bounds);
        
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
    hideall();
    $.post(baseurl+'index.php/geocrowd/getplace',{lat:lat,lng:lng},function(data){
        $('#location').val(data);
        $('#btnposttask').attr('disabled',false); 
    });
    $('#overlay').show();
    $('#posttask').show();     
                       
  });
                           
}
function loadreporttask(taskid){

                                    
                                         
                                    
                                        
                                        
                                    
}
google.maps.event.addDomListener(window, 'load', initialize);
$('.btnback').click(function(){
    hideall();
});

$('#logout').click(function(){
   var r = confirm("Do you want logout?");
    if (r == true) {
        window.location= baseurl+'index.php/home/logout';
    } else {
        
    } 
});


$('#showtask').click(function(){
   hideall();
   $('#overlay').show();
   $('#taskmanager').show(); 
});
$('#showresponse').click(function(){
    hideall();
    if(taskid==0){
        alert('You have no task!');
    }else{
         $('#overlay').show();
         $('#responsetask').show();
    }
   
});
$(document).on('click',"#btnposttask",function(){
                 var title = $('#title').val();
                 var lat = $('#lat').val();
                 var lng = $('#lng').val();
                 var now = new Date();
                 var tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                 var requestdate = now.getFullYear()+'-'+(now.getMonth()+1)+'-'+now.getDate()+' '+now.getHours()+':'+now.getMinutes()+':'+now.getSeconds();
                 var enddate = tomorrow.getFullYear()+'-'+(tomorrow.getMonth()+1)+'-'+tomorrow.getDate()+' '+tomorrow.getHours()+':'+tomorrow.getMinutes()+':'+tomorrow.getSeconds();
                 var radius = $('#radius').val();
                 $.ajax({
                    type: 'POST',
                    url: baseurl+'index.php/requester/task_request',
                    data: 'title='+title+'&lat='+lat+'&lng='+lng+'&requestdate='+requestdate+'&startdate='+requestdate+'&enddate='+enddate+'&type=0'+'&radius='+radius,
                    success:function(data){
                        if(data.status=='success'){
                            alert('Post success');
                            hideall();
                        }else{
                            alert('error');
                        }
                    }
                 });
});

$('#loadtype').change(function(){
   var type = $(this).val();
   loadTasktype(type);
});
$('#btnresponse').click(function(){
    $('#loading').show();
    var code = $('#code').val();
    var level = $('#level').val();
    var now = new Date();
    var timeresponse = $('#timeresponse').val();
    var currenttime = now.getFullYear()+'-'+(now.getMonth()+1)+'-'+now.getDate()+' '+(now.getHours()-timeresponse)+':'+now.getMinutes()+':'+now.getSeconds();
    $.post(baseurl+'index.php/worker/task_response',{taskid:taskid,lat:currentlat,lng:currentlng,responsecode:code,responsedate:currenttime,level:level},function(data){
       if(data.status=='success'){
            hideall();
            alert('You have response a task!');
            taskid=0;
            $('#newtask').hide();
            $('#currentlocation').val('');
            $('#responsetitle').val('');
            $('#btnresponse').attr('disabled',true);
            $('#responselocation').val('');
       }else{
            alert('error');
            $('#loading').hide();
       }
    });
});
$('#showadmin').click(function(){
   hideall();
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
            $(document).on('click','#btndel',{url:baseurl},function(event){
                var elements = deleteArray.join(',');
                if(deleteArray.length!=0){
                    $.ajax({
                        type: 'POST',
                        url: event.data.url+'index.php/requester/delete_tasks',
                        data: 'taskids='+elements,
                        success:function(data){
                            if(data.status=='success'){
                                for(i=0;i<deleteArray.length;i++){
                                    $('tr[title='+deleteArray[i]+']').hide();
                                }
                            }
                        }
                    });
                }
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
       $('#overlay').show();
       $('#uplocation').show(); 
    });       
    $('#btnup').click(function(){
        $('#loading').show();
        var now = new Date();
        var currenttime = now.getFullYear()+'-'+(now.getMonth()+1)+'-'+now.getDate()+' '+now.getHours()+':'+now.getMinutes()+':'+now.getSeconds();
        $.post(baseurl+'index.php/worker/location_report',{lat:currentlat,lng:currentlng,datetime:currenttime},function(data){
           if(data.status=='success'){
               alert('Update success');
               hideall();
               $('#btnup').attr('disabled',true); 
           } 
        });
    });      
})