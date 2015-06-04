$(document).ready(function(){
    var loadTasks = false;
    var loadResponses = false;
    var map;
    var markers = [];
    var xhr = null;
    var iw1;
    var none = baseurl+"img/mark_none_ic.png";
    var rainlv3 = baseurl+"img/mark_rain_ic_lv3.png";
    var rainlv2 = baseurl+"img/mark_rain_ic_lv2.png";
    var rainlv1 = baseurl+"img/mark_rain_ic_lv1.png";
    var snowlv3 = baseurl+"img/mark_snow_ic_lv3.png";
    var snowlv2 = baseurl+"img/mark_snow_ic_lv2.png";
    var snowlv1 = baseurl+"img/mark_snow_ic_lv1.png";
    var noimage = baseurl+"img/noimage.png";
    $('#btnlogin').click(function(){
        var username = $('#username').val();
        var password = $('#password').val()
        //var hashpass = SHA512($('#password').val());
        $.ajax({
            type: 'POST',
            url: baseurl+'index.php/admin/login',
            data: "username="+username+"&password="+password,
            success:function(data){
                if(data.status=='success'){
                    window.location= baseurl+'index.php/admin';
                } 
            }
        });
    });
    function listUsers(offset){
        var target = document.getElementById('test');
        var spinner = new Spinner().spin(target);
        $.ajax({
            type: 'POST',
            url: baseurl+'index.php/admin/listUsers',
            data: "offset="+offset,
            success:function(data){
                if(data.status=='success'){
                    $('#tableusers tbody tr').remove();
                    $("#tableusers tbody").append("<tr></tr>");
                    var arr = data.msg;
                    for(i=0;i<arr.length;i++){
                        var userid = arr[i].userid
                        var username = arr[i].username;
                        var firstname = arr[i].firstname;
                        var lastname = arr[i].lastname;
                        var createdate = arr[i].created_date;
                        var chanelid = arr[i].channelid;
                        var actionDel = '<button type="button" class="btn btn-default btn-sm info-del" name="'+userid+'"><span class="glyphicon glyphicon-trash"></span></button>';
                        var actionView = '<button type="button" class="btn btn-default btn-sm info-view" name="'+userid+'"><span class="glyphicon glyphicon-user"></span></button>';
                        var row = "<tr id='user"+i+"'><td>"+userid+"</td><td>"+username+"</td><td class='text-center'>"+createdate+"</td><td class='text-center'>"+actionView+actionDel+"</td></tr>";
                        $('#tableusers tr:last').after(row);
                        
                    }
                    
                    
                } 
                spinner.stop();
            }
        });
    }
    function listTasks(offset){
        var target = document.getElementById('test');
        var spinner = new Spinner().spin(target);
        $.ajax({
            type: 'POST',
            url: baseurl+'index.php/admin/listTasks',
            data: "offset="+offset,
            success:function(data){
                if(data.status=='success'){
                    $('#tabletasks tbody tr').remove();
                    $("#tabletasks tbody").append("<tr></tr>");
                    
                    var arr = data.msg;
                    for(i=0;i<arr.length;i++){
                        var taskid = arr[i].taskid;
                        var locationrequest = arr[i].place.replace("unknown,", "");
                        
                        var requestdate = arr[i].request_date;
                        var querymethod = "circle";
                        if(arr[i].type==0){
                            querymethod = "city";
                        }
                        if(arr[i].type==1){
                            querymethod = "state";
                        }
                        if(arr[i].type==2){
                            querymethod = "country";
                        }
                        var completed = "false";
                        if(arr[i].iscompleted==1){
                            completed = "true";
                        }
                        var row = "<tr><td class='text-center'>"+taskid+"</td><td>"+locationrequest+"</td><td class='text-center'>"+requestdate+"</td><td class='text-center'>"+querymethod+"</td><td class='text-center'>"+completed+"</td></tr>";
                        $('#tabletasks tr:last').after(row);
                        
                    }
                    
                    
                } 
                spinner.stop();
            }
        });
    }
    function getPagUser(){
        $.ajax({
            type: 'POST',
            url: baseurl+'index.php/admin/getNumUsers',
            success:function(data){
                if(data.status=='success'){
                    var next = 0;
                    var num = data.msg;
                    for(i=0;i<num;i++){
                        var pag = '<li rel="'+next+'"><a href="#">'+(i+1)+'</a></li>';
                        if(i==0){
                            pag = '<li class="active" rel="'+next+'"><a href="#">'+(i+1)+'</a></li>';
                        }
                        next+=15;
                        $('#userspag li:last').before(pag);
                    }
                    
                    
                } 
            }
        });
    }
    function getPagTask(){
        $.ajax({
            type: 'POST',
            url: baseurl+'index.php/admin/getNumTasks',
            success:function(data){
                if(data.status=='success'){
                    var next = 0;
                    var num = data.msg;
                    for(i=0;i<num;i++){
                        var pag = '<li rel="'+next+'"><a href="#">'+(i+1)+'</a></li>';
                        if(i==0){
                            pag = '<li class="active" rel="'+next+'"><a href="#">'+(i+1)+'</a></li>';
                        }
                        next+=15;
                        $('#taskspag li:last').before(pag);
                    }
                    
                    
                } 
            }
        });
    }
    function getPagResponse(){
        $.ajax({
            type: 'POST',
            url: baseurl+'index.php/admin/getNumResponses',
            success:function(data){
                if(data.status=='success'){
                    var next = 0;
                    var num = data.msg;
                    if(num==0){
                        return;
                    }
                        for(i=0;i<num;i++){
                            var pag = '<li rel="'+next+'"><a href="#">'+(i+1)+'</a></li>';
                            if(i==0){
                                pag = '<li class="active" rel="'+next+'"><a href="#">'+(i+1)+'</a></li>';
                            }
                            next+=15;
                            $('#responsespag li:last').before(pag);
                        }
                    
                    
                    
                    
                } 
            }
        });
    }
    function listResponses(offset){
        var target = document.getElementById('test');
        var spinner = new Spinner().spin(target);
        for (var i = 0, marker; marker = markers[i]; i++) {
                        marker.setMap(null);
                    }          
                    markers = [];
        if( xhr != null ) {
                        xhr.abort();
                        xhr = null;
                    }
        xhr = $.ajax({
            type: 'POST',
            url: baseurl+'index.php/admin/listResponses',
            data: "offset="+offset,
            success:function(data){
                if(data.status=='success'){
                    $('#tableresponses tbody tr').remove();
                    $("#tableresponses tbody").append("<tr></tr>");
                    
                    var arr = data.msg;
                    $.each(arr, function(i, item) {
                   
                        var taskid = item.taskid;
                        var locationresponse = item.worker_place.replace("unknown,", "");
                        var responsedate = item.response_date_server;
                        var response = "";
                        var responsecode = item.response_code;
                        var id =item.id;
                        
                        var location = new google.maps.LatLng(item.lat, item.lng);
                        var icons=none;
                        var weather = "No Rain/Snow";
                        
                        
                        if(item.response_code==0){
                            icons = none;
                            weather = "No Rain/Snow";
                            response = "No Rain/Snow";
                        }
                        if(item.response_code==1 && item.level==0){
                            icons = rainlv3;
                            weather = "LIGHT RAIN";
                            response = "Rain(Light)";
                        }
                        if(item.response_code==1 && item.level==1){
                            icons = rainlv2;
                            weather = "MODERATE RAIN";
                            response = "Rain(Moderate)";
                        }
                        if(item.response_code==1 && item.level==2){
                            icons = rainlv1;
                            weather = "HEAVY RAIN";
                            response = "Rain(Heavy)";
                        }
                         if(item.response_code==2 && item.level==0){
                            icons = snowlv3;
                            weather = "LIGHT SNOW";
                            response = "Snow(Light)";
                        }
                        if(item.response_code==2 && item.level==1){
                            icons = snowlv2;
                            weather = "MODERATE SNOW";
                            response = "Snow(Moderate)";
                        }
                        if(item.response_code==2 && item.level==2){
                            icons = snowlv1;
                            weather = "HEAVY SNOW";
                            response = "Snow(Heavy)";
                        }
                        var image = {
                            url: icons
                            
                        };
                            
                                  // Create a marker for each place.
                        
                        var actionDel = '<button type="button" class="btn btn-default btn-sm res-del" name="'+id+'"><span class="glyphicon glyphicon-trash"></span></button>';
                        var actionView = '<button type="button" class="btn btn-default btn-sm res-view" name="'+i+'"><span class="glyphicon glyphicon-eye-open"></span></button>';
                        var row = "<tr><td>"+locationresponse+"</td><td class='text-center'>"+response+"</td><td class='text-center'>"+responsedate+"</td><td class='text-center'>"+actionDel+actionView+"</td></tr>";
                        $('#tableresponses tr:last').after(row);
                        var marker = new MarkerWithLabel({
                            map: map,
                            icon: image,
                            labelVisible:false,
                            labelContent: locationresponse,  
                            position: location               
                        });
                        google.maps.event.addListener(marker, 'mouseover', function(event){

                            var contentString = marker.get('labelContent');
                          
                            iw1 = new google.maps.InfoWindow({
                                        content: "<p style='text-align:center;min-width:250px;min-height:30px'><b>"+locationresponse+"</b><br/>"+weather+", "+responsedate+"</p>"                                
                                    }); 
                            
                            iw1.open(map,this);   
                                                       
                        }); 
                        google.maps.event.addListener(marker, 'mouseout', function(event){
                            iw1.close(map,this);                               
                        });                       
                        markers.push(marker);
                        
                    });
                    
                    
                    
                    
                } 
                spinner.stop();
            }
            
        });
    }
    
    listUsers(0);
    getPagUser();
    
    
    $("#tableresponses button.res-del").live("click",function(){
        var id = $(this).attr('name');
        var r = confirm("Do you want delete?");
         if (r == true) {
            $.ajax({
                type: 'POST',
                url: baseurl+'index.php/admin/deleteResponse',
                data: "id="+id,
                success:function(data){
                    if(data.status=='success'){
                        var offset = $('#responsespag li.active').attr("rel");
                            listResponses(offset);
                       
                    } 
                }
            });
         }
        
        
    });
    $("#tableresponses button.res-view").live("click",function(){
        var pos = $(this).attr('name');
        toLocation(pos);
        
    });
    $("#tableusers button.info-del").live("click",function(){
        var r = confirm("Do you want delete?");
        var userid = $(this).attr('name');
        if (r == true) {
            $.ajax({
                type: 'POST',
                url: baseurl+'index.php/admin/deleteUser',
                data: "userid="+userid,
                success:function(data){
                    if(data.status=='success'){
                        var offset = $('#taskspag li.active').attr("rel");
                        listUsers(offset);
                    }
                }
                
            });
        } else {
            
        }
    });
    $("#tableusers button.info-view").live("click",function(){
        var userid = $(this).attr("name");
        $('#overlay').show();
        var target = document.getElementById('profile');
        var spinner = new Spinner().spin(target);
        $.ajax({
            type: 'POST',
            url: baseurl+'index.php/admin/getUserInfo',
            data: "userid="+userid,
            success:function(data){
                if(data.status=='success'){
                    var arr = data.msg;
                    var userid = arr[0].userid
                    var username = arr[0].username;
                    var firstname = arr[0].firstname;
                    var lastname = arr[0].lastname;
                    var createdate = arr[0].created_date;
                    var chanelid = arr[0].channelid;
                    var avatar = arr[0].avatar;
                    if(firstname==null){
                        firstname='null';
                    }
                    if(lastname==null){
                        lastname='null';
                    }
                    if(avatar==null){
                        avatar = noimage;
                    }
                    $('#info-userid').html(userid);
                    $('#info-username').html(username);
                    $('#info-firstname').html(firstname);
                    $('#info-lastname').html(lastname);
                    $('#info-createdate').html(createdate);
                    $('#info-channel').html(chanelid);
                    $('.label-info').html(username);
                    $('#info-image').attr('src',avatar);
                } 
                spinner.stop();
            }
            
        });
        $.ajax({
            type: 'POST',
            url: baseurl+'index.php/admin/getUserNumTasks',
            data: "userid="+userid,
            success:function(data){
                    
                    $('#info-request').html(data);
                
            }
            
        });
        $.ajax({
            type: 'POST',
            url: baseurl+'index.php/admin/getUserNumResponses',
            data: "userid="+userid,
            success:function(data){
                   
                    $('#info-response').html(data);
                
            }
            
        });
    });
    $('#userspag li:not(.next,.previous)').live("click",function(){
        $('#userspag li.active').attr('class','');
        $(this).attr('class','active');
        var offset = $(this).attr("rel");
        if(offset==null){
            return;
        }
        listUsers(offset);
    });
    $('#taskspag li:not(.next,.previous)').live("click",function(){
        $('#taskspag li.active').attr('class','');
        $(this).attr('class','active');
        var offset = $(this).attr("rel");
        if(offset==null){
            return;
        }
        listTasks(offset);
    });
    $('#responsespag li:not(.next,.previous)').live("click",function(){
        $('#responsespag li.active').attr('class','');
        $(this).attr('class','active');
        var offset = $(this).attr("rel");
        if(offset==null){
            return;
        }
        listResponses(offset);
    });
    $('#responsespag li.next').live("click",function(){
        alert("click");
        return;
    });
    $('#show-tasks').click(function(){
        if(!loadTasks){
            loadTasks = true;
            listTasks(0);
            getPagTask();
           
        }
        
    });
    $('#show-responses').click(function(){
        if(!loadResponses){
            loadResponses = true;
            initialize();
            listResponses(0);
            getPagResponse();  
            
        }
        
        
    });
     function initialize() {
        var mapCanvas = document.getElementById('map-canvas');
        var mapOptions = {
          center: new google.maps.LatLng(44.5403, -78.5463),
          zoom: 3,
          mapTypeId: google.maps.MapTypeId.HYBRID
        }
        map = new google.maps.Map(mapCanvas, mapOptions);
     }
     
     
     function toLocation(i){
        $('#panel-element-319821').collapse('toggle');
        var marker = markers[i];
        var mylocation = marker.position;
                        map.panTo(mylocation);
     }
     $('input.btnback').click(function(){
        $('#overlay').hide();
        clearInfo();
     });
     function clearInfo(){
        $('#info-userid').html('');
        $('#info-username').html('');
        $('#info-firstname').html('');
        $('#info-lastname').html('');
        $('#info-createdate').html('');
        $('#info-channel').html('');
        $('#info-request').html('');
        $('#info-response').html('');
        $('.label-info').html('');
        $('#info-image').attr('src',noimage);
     }
      
})