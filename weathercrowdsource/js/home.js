$(document).ready(function(){
    var currentlat=0;
    var currentlng=0;
    var taskid =0;
    var deleteArray = new Array();
    var completeimage = baseurl+'img/complete.png';
                            var expiredimage = baseurl+'img/expired.png';
    $('#newtask').hide();
   function rndStr() {
    x=Math.random().toString(36).substring(7).substr(0,5);
    while (x.length!=5){
        x=Math.random().toString(36).substring(7).substr(0,5);
    }
    return x;
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
    if(admin){
        loadinfo();
    }
    
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
    if(islogin){
         getTask();
         setInterval(function(){getTask()},60000);
    }
   
    function hideall(){
        $('#overlay').hide();
        $('#loginform').hide();
        $('#registerform').hide();
        $('#taskmanager').hide();
        $('#posttask').hide();
        $('#loading').hide();
        $('#btnposttask').attr('disabled',true);
        $('#responsetask').hide();
        $('#adminboard').hide();
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
    
    
    /**function loadTask(numtask,numoffset){
                $.ajax({
                   type: 'POST',
                   url: baseurl+'index.php/requester/submitted_tasks',
                   data:'number='+numtask+"&offset="+numoffset,
                   success:function(data){
                        if(data.status=='success'){
                            $('#containertask').html('');
                            var completeimage = baseurl+'img/complete.png';
                            var expiredimage = baseurl+'img/expired.png';
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
    });
    //}
                                    
                                    
                             });
                                 
                        }
                   }
                });
            }*/
    loadTasktype(0);
    hideall();
$('#refresh').click(function(){
   var type = $('#loadtype').val();
   loadTasktype(type); 
});
function initialize() {
    var none = baseurl+"img/mark_none_ic.png";
                var rain = baseurl+"img/mark_rain_ic.png";
                var snow = baseurl+"img/mark_snow_ic.png";
  var markers = [];
  var myLatlng = new google.maps.LatLng(40.71278369999998, -74.00594130000002);
  var zoom = 2;
  var mapOptions = {
      minZoom:2,
      maxZoom:6,
      zoom:zoom,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      scrollwheel: true,
      disableDoubleClickZoom: false,
      disableDefaultUI: true
  };
  var iw1 = new google.maps.InfoWindow({
    content: "Home For Sale"
                                   
  });
  var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
  // Create the search box and link it to the UI element.
  var input = /** @type {HTMLInputElement} */(document.getElementById('pac-input'));
  var input1 = (document.getElementById('currentlocation'));
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  var searchBox = new google.maps.places.SearchBox(
    /** @type {HTMLInputElement} */(input));
  var searchBox1 = new google.maps.places.Autocomplete(
    /** @type {HTMLInputElement} */(input1));
    google.maps.event.addListener(searchBox1, 'place_changed', function () {
            var place = searchBox1.getPlace();
            currentlat = place.geometry.location.lat();
            currentlng = place.geometry.location.lng();
            $('#btnresponse').attr('disabled',false);
            //document.getElementById('cityLat').value = place.geometry.location.lat();
            //document.getElementById('cityLng').value = place.geometry.location.lng();
            //alert("This function is working!");
            //alert(place.name);
           // alert(place.address_components[0].long_name);

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
      

      markers.push(marker);

      bounds.extend(place.geometry.location);
    }

    map.fitBounds(bounds);
    
    getmarker();
  });
  // [END region_getplaces]

  // Bias the SearchBox results towards places that are within the bounds of the
  // current map's viewport.
  google.maps.event.addListener(map, 'bounds_changed', function() {
    var bounds = map.getBounds();
    searchBox.setBounds(bounds);
    getmarker();
    google.maps.event.clearListeners(map,'bounds_changed');
     
  });
  google.maps.event.addListener(map, 'dragend', function() {
    for (var i = 0, marker; marker = markers[i]; i++) {
      marker.setMap(null);
    }
    markers = [];
    getmarker();
                    
  });
  google.maps.event.addListener(map, 'click', function(event){
    if(islogin){
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
     }                    
  });
  
  function getmarker(){
                    var SW_lat = map.getBounds().getSouthWest().lat();
                    var SW_lng = map.getBounds().getSouthWest().lng();
                    var NE_lat = map.getBounds().getNorthEast().lat();
                    var NE_lng = map.getBounds().getNorthEast().lng();
                    $.ajax({
                      type: "POST",
                      url: baseurl+"index.php/weather/getallreport",
                      data:"swlat="+SW_lat+"&swlng="+SW_lng+"&nelat="+NE_lat+"&nelng="+NE_lng+"&startdate=1979-01-01 00:00:00&enddate=2015-01-01 00:00:00",
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
                                    title: item.taskid,
                                    
                                    position: location
                                    //labelContent: "$425K",
                                   //labelAnchor: new google.maps.Point(22, 0),
                                   //labelClass: "labels", // the CSS class for the label
                                   //labelStyle: {opacity: 0.75}
                                });
                                
                                 google.maps.event.addListener(marker, "click", function (e) {
                                    
                                    iw1.setContent(table);
                                    iw1.setPosition(this.getPosition());
                                    iw1.open(map);
                                    
                                     
                                 });
                                google.maps.event.addListener(marker, 'click', function(event) {       
                                    var getlatlng = event.latLng;
                                     var lat = getlatlng.lat();
                                     var lng = getlatlng.lng();
                                     $('#lat').val(lat);
                                     $('#lng').val(lng);
                                     var islogin = false;
                                     /**
                                     if(islogin){
                                        $.post(baseurl+'index.php/geocrowd/getplace',{lat:lat,lng:lng},function(data){
                                            $('#location').val(data); 
                                        });
                                     }**/
                                    
                                });
                                markers.push(marker);
                           });
                      }
                    });   
              }
              
               
}
function loadreporttask(taskid){
    var table = '<table><thead><tr><th>Location</th><th>Response</th><th>Response date</th></tr></thead><tbody>';
                                    $.post(baseurl+'index.php/weather/getreport',{taskid:taskid},function(data){
                                         $.each(data, function(i, item) {
                                            
                                            var cellLocation = '<td></td>';
                                            var cellResponsecode = '<td>'+item.response_code+'</td>';
                                            var cellResponsedate = '<td>'+item.response_date+'</td>';
                                            
                                            $.post(baseurl+'index.php/geocrowd/getplace',{lat:item.lat,lng:item.lng},function(data){
                                            cellLocation = '<td>'+data+'</td>';
                                            var row = '<tr>'+cellLocation+cellResponsecode+cellResponsedate+'</tr>';
                                           table+=row;
                                            alert(table);
                                         });
                                         
                                    });
                                         
                                    
                                        
                                        
                                        
                                    });
}

google.maps.event.addDomListener(window, 'load', initialize);
$('#showlogin').click(function(){
    $('#overlay').show();
    $('#loginform').show();
});
$('#showregister').click(function(){
    $('#overlay').show();
    $('#registerform').show();
});
$('.btnback').click(function(){
    hideall();
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
            $('#login').hide();
        }else{
            alert("Invalid username or password");
        }    
    }
    });
});
$('#logout').click(function(){
   var r = confirm("Do you want logout?");
    if (r == true) {
        window.location= baseurl+'index.php/home/logout';
    } else {
        
    } 
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
                            alert('Your account has been create');
                            hideall();
                            $('#overlay').show();
                            $('#loginform').show();
                            $('#username').val(res_username);
                            $('#password').val(res_pass);
                        }
                    });
                    }else{
                         alert('Username already exists');
                        return;
                    }
                });
                
                
            });
$('#showprofile').click(function(){
   hideall();
   
});
$('#showtask').click(function(){
   hideall();
   $('#overlay').show();
   $('#taskmanager').show(); 
});
$('#showresponse').click(function(){
    hideall();
    $('#overlay').show();
    $('#responsetask').show();
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
    var code = $('#code').val();
    var level = $('#level').val();
    var now = new Date();
    var timeresponse = $('#timeresponse').val();
    var currenttime = now.getFullYear()+'-'+(now.getMonth()+1)+'-'+now.getDate()+' '+(now.getHours()-timeresponse)+':'+now.getMinutes()+':'+now.getSeconds();
    $.post(baseurl+'index.php/worker/task_response',{taskid:taskid,lat:currentlat,lng:currentlng,responsecode:code,responsedate:currenttime,level:level},function(data){
       if(data.status=='success'){
            hideall();
            $('#newtask').hide();
            $('#currentlocation').val('');
            $('#btnresponse').attr('disabled',true);
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
})