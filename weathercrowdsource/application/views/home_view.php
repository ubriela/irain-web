<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>iRain</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content=""/>
  <meta name="author" content=""/>

	<!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
	<!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
	<!--script src="js/less-1.3.3.min.js"></script-->
	<!--append ‘#!watch’ to the browser URL, then refresh the page. -->
	
	<link href="<?php echo base_url()?>css/bootstrap.min.css" rel="stylesheet"/>
	<link href="<?php echo base_url()?>css/home.css" rel="stylesheet"/>

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons -->
  <link rel="shortcut icon" href="<?php echo base_url();?>img/icon.png"/>
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url()?>img/icon.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url()?>img/icon.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url()?>img/icon.png">
  <link rel="apple-touch-icon-precomposed" href="img/icon.png"/>
  
  
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/login.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>js/bootstrap.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
    <script>
        $(document).ready(function(){
            var baseurl = '<?php echo base_url();?>';
            var numbertask = 12;
            var offset = 0;
            var deleteArray = new Array();
            function initialize() {
                var none = baseurl+"img/mark_none_ic.png";
                var rain = baseurl+"img/mark_rain_ic.png";
                var snow = baseurl+"img/mark_snow_ic.png";
                var markers = [];
                var myLatlng = new google.maps.LatLng(40.71278369999998, -74.00594130000002);
                var minzoom = 6;
                var mapOptions = {
                  zoom: minzoom,
                  center: myLatlng,
                  mapTypeId: google.maps.MapTypeId.ROADMAP,
                  scrollwheel: true,
                  disableDoubleClickZoom: false  
              };
              var input = /** @type {HTMLInputElement} */(
                  document.getElementById('pac-input'));
              
              var map = new google.maps.Map(document.getElementById('map'),mapOptions);
               map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                
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
                                var marker = new google.maps.Marker({
                                    map: map,
                                    icon: image,
                                    title: item.lat+","+item.lng,
                                    position: location
                                });
                                google.maps.event.addListener(marker, 'click', function(event) {       
                                    var getlatlng = event.latLng;
                                     var lat = getlatlng.lat();
                                     var lng = getlatlng.lng();
                                     $('#lat').val(lat);
                                     $('#lng').val(lng);
                                     var islogin = false;
                                     <?php
                                        if(isset($username)){
                                            echo "$('#overlay').removeClass('hide');";
                                            echo "$('#post').show();";
                                            echo "islogin=true;";
                                        }
                                        
                                     ?>
                                     if(islogin){
                                        $.post(baseurl+'index.php/geocrowd/getplace',{lat:lat,lng:lng},function(data){
                                            $('#location').val(data); 
                                        });
                                     }
                                    
                                });
                                markers.push(marker);
                           });
                      }
                    });   
              }

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
                // For each place, get the icon, place name, and location.
                markers = [];
                var bounds = new google.maps.LatLngBounds();
                for (var i = 0, place; place = places[i]; i++) {
                  bounds.extend(place.geometry.location);   
                } 
                map.fitBounds(bounds); 
                map.setOptions({
                   zoom:minzoom
                });           
              });
              // [END region_getplaces]
            
              // Bias the SearchBox results towards places that are within the bounds of the
              // current map's viewport.
               google.maps.event.addListener(map, 'idle', function(){
                   getmarker();
                   google.maps.event.addListener(map, 'bounds_changed', function() {
                       var bounds = map.getBounds();
                       searchBox.setBounds(bounds); 
                   });
                   google.maps.event.addListener(map, 'click', function(event){
                         var getlatlng = event.latLng;
                         var lat = getlatlng.lat();
                         var lng = getlatlng.lng();
                         $('#lat').val(lat);
                         $('#lng').val(lng);
                         var islogin = false;
                         <?php
                            if(isset($username)){
                                echo "$('#overlay').removeClass('hide');";
                                echo "$('#post').show();";
                                echo "islogin=true;";
                            }
                            
                         ?>
                         if(islogin){
                            $.post(baseurl+'index.php/geocrowd/getplace',{lat:lat,lng:lng},function(data){
                                $('#location').val(data); 
                            });
                         }
                         
                   });  
                });
            }
            function convertDate(dateString){
                var p = dateString.split(/\D/g);
                return [p[2],p[1],p[0] ].join("-");
            }
            function loadTask(numtask,numoffset){
                $.ajax({
                   type: 'POST',
                   url: baseurl+'index.php/requester/submitted_tasks',
                   data: 'number='+numtask+"&offset="+numoffset,
                   success:function(data){
                        if(data.status=='success'){
                            $('#container-tasks').html('');
                            var now = '<?php echo date('Y-m-d');?>';
                            var completeimage = baseurl+'img/complete.png';
                            var expiredimage = baseurl+'img/expired.png';
                            var arrayjson = data.msg;
                             $.each(arrayjson, function(i, item) {
                                    var cellID = '<td>'+item.taskid+'</td>';
                                    var cellTitle = '<td>'+item.title+'</td>';
                                    var cellLat = '<td>'+item.lat+'</td>';
                                    var cellLng = '<td>'+item.lng+'</td>';
                                    var cellStart = '<td>'+item.startdate+'</td>';
                                    var cellEnd = '<td>'+item.enddate+'</td>';
                                    var cellComplete = '<td></td>';
                                    var cellExpired = '<td></td>';
                                    var cellDelete = '<td class="center"><input class="check" type="checkbox" id='+item.taskid+'></td>';
                                    if(item.iscompleted==1){
                                        cellComplete='<td class=center><img src='+completeimage+' width=30 height=30/></td>';
                                    }else{
                                        if(item.enddate<now){
                                            cellExpired = '<td class=center><img src='+expiredimage+' width=30 height=30/></td>';
                                        }
                                    }
                                    $('#container-tasks').append('<tr>'+cellID+cellTitle+cellLat+cellLng+cellStart+cellEnd+cellComplete+cellExpired+cellDelete+'</tr>');
                             });
                                 
                        }else{
                            offset-=12;
                        }
                   }
                });
            }
            loadTask(numbertask,offset);
           
            
            google.maps.event.addDomListener(window, 'load', initialize);
    });
    </script>
</head>

<body>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="row clearfix">
                
				<div class="col-md-12 column" id="right">
                    <input id="pac-input" class="controls" type="text" placeholder="Search Box"/>
					<div class="row clearfix right-container show" id="map">     
				    </div>
				</div>
                <div id="overlay" class="hide">
                    <?php
                        if(!isset($username)){
                            require_once('loginform.php');
                        }else{
                            require_once('posttask.php');
                            require_once('profilepanel.php');
                            require_once('responsepanel.php');
                        }      
                    ?>
                </div>
                <div class="list-group" id="container_menu">
                    <?php
                        if(isset($username)){
                            include_once('after.php');
                        }else{
                            include_once('before.php');
                        }
                    ?>
    				
    			</div>
                
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function()
{
    var baseurl = '<?php echo base_url();?>';
     function createtask(title,lat,lng,requestdate,startdate,enddate,type,radius){
                return{
                    userid:'asdasd',
                    title: title,
                    lat: lat,
                    lng: lng,
                    requestdate: requestdate,
                    startdate: startdate,
                    enddate: enddate,
                    type: type,
                    radius: radius
                }
            }
    function post_from_file(filename){
                var url = baseurl+'xml/'+filename;
                var tasks = new Array();
                if (window.XMLHttpRequest)
                  {// code for IE7+, Firefox, Chrome, Opera, Safari
                  xmlhttp=new XMLHttpRequest();
                  }
                else
                  {// code for IE6, IE5
                  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                  }
                xmlhttp.open("GET",url,false);
                xmlhttp.send();
                xmlDoc=xmlhttp.responseXML; 
                var x=xmlDoc.getElementsByTagName("task");
                for (i=0;i<x.length;i++)
                  { 
                        var title = x[i].getElementsByTagName("title")[0].childNodes[0].nodeValue;
                        var lat = x[i].getElementsByTagName("lat")[0].childNodes[0].nodeValue;
                        var lng = x[i].getElementsByTagName("lng")[0].childNodes[0].nodeValue;
                        var requestdate = x[i].getElementsByTagName("requestdate")[0].childNodes[0].nodeValue;
                        var startdate = x[i].getElementsByTagName("startdate")[0].childNodes[0].nodeValue;
                        var enddate = x[i].getElementsByTagName("enddate")[0].childNodes[0].nodeValue;
                        var type = x[i].getElementsByTagName("type")[0].childNodes[0].nodeValue;
                        var radius = x[i].getElementsByTagName("radius")[0].childNodes[0].nodeValue;
                        tasks.push(createtask(title,lat,lng,requestdate,startdate,enddate,type,radius));
                  
                  }
                  var jsontask = JSON.stringify(tasks);
                  $.ajax({
                        type: 'POST',
                        url: baseurl+'index.php/requester/post_from_file',
                        data: 'arraytasks='+jsontask,
                        success:function(data){
                            alert('Post success: '+data.msg+' tasks');
                        }
                  });
                  
            }
	var options = { 
    beforeSend: function() 
    {
    	$("#progress").show();
    	//clear everything
    	$("#bar").width('0%');
    	$("#message").html("");
		$("#percent").html("0%");
    },
    uploadProgress: function(event, position, total, percentComplete) 
    {
    	$("#bar").width(percentComplete+'%');
    	$("#percent").html(percentComplete+'%');

    
    },
    success: function() 
    {
        $("#bar").width('100%');
    	$("#percent").html('100%');

    },
	complete: function(response) 
	{
		$("#message").html("<font color='green'>"+response.responseText+"</font>");
        var link = $('#file').val();
        var clean=link.split('\\').pop();
        post_from_file(clean);
	},
	error: function()
	{
		$("#message").html("<font color='red'> ERROR: unable to upload files</font>");

	}
     
}; 
     $("#myForm").ajaxForm(options);
     $(document).on('click',"#btnback",function(event){
                 event.preventDefault();
                 $('#overlay').addClass('hide');
                 $('#post').hide();
            });
            $(document).on('click',"#btnpost",function(event){
                 event.preventDefault();
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
                            $('#post').hide();
                            $('#overlay').addClass('hide');
                        }else{
                            alert('error');
                        }
                    }
                 });
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
                                loadTask(numbertask);
                                numbertask = 12;
                                offset = 0;
                            }
                        }
                    });
                }
            });
            $(document).on('click','#next',function(){
                var count = $('#container-tasks').find('tr').length;
                if(count==numbertask){
                    offset+=12;
                    loadTask(numbertask,offset);
                    deleteArray = new Array();
                }
            });
            $(document).on('click','#prev',function(){
                if(offset!=0){
                    offset-=12;
                    loadTask(numbertask,offset);
                    deleteArray = new Array();
                }
            });
            $(document).on('click','#refresh',function(){
                offset=0;
                loadTask(numbertask,offset);
            });
            $('.hover').hover(function(){
                $('.itemmenu').removeClass('hide');
            },function(){
                $('.itemmenu').addClass('hide');
            });
            $('#clickregister').on('click',function(){
                $('#overlay').removeClass('hide');
               $('form.current').addClass('hide');
               $('form.current').removeClass('current');
               $('#registerform').addClass('current');
               $('#registerform').removeClass('hide');
            });
            $('#clicklogin').click(function(){
                $('#overlay').removeClass('hide');
               $('form.current').addClass('hide');
               $('form.current').removeClass('current');
               $('#loginform').addClass('current');
               $('#loginform').removeClass('hide');
            });
            $('.back').on('click',function(){
                $('#overlay').addClass('hide');
                $('form.current').addClass('hide');
               $('form.current').removeClass('current');
               $('#container_profile').addClass('hide');
            });
            $('#logout').click(function(){
                var r = confirm("Do you want logout?");
                if (r == true) {
                    window.location = baseurl+'index.php/home/logout';
                } else {
                    
                }
            });
            $('#btnlogin').click(function(event){
                event.preventDefault();

                var username = $('#username').val();
                var hashpass = SHA512($('#password').val());
                $.ajax({
                   type: 'POST',
                   url: baseurl+'index.php/user/login',
                   data: "username="+username+"&password="+hashpass,
                   success:function(data){
                        if(data.status=='success'){
                            window.location="<?php echo base_url()?>index.php/home";
                        }else{
                            alert("Invalid username or password");
                        }    
                   }
                });
            });
            $('#btnregister').click(function(event){
                event.preventDefault();
                var check = true;
                var res_username = $('#resusername').val();
                var res_email = $('#email').val();
                var res_pass = $('#respassword').val();
                var rppass = $('#rppassword').val();
                var hasspass = SHA512(res_pass);
                if(res_username==''){
                    $('#error_username').removeClass('hide');
                    check = false;
                }else{
                    $('#error_username').addClass('hide');
                }
                if(!validateEmail(res_email)){
                    $('#error_email').removeClass('hide');
                    check = false;   
                }else{
                    $('#error_email').addClass('hide');
                }
                if(res_pass==''){
                    $('#error_pass').removeClass('hide');
                    check = false;
                }else{
                    $('#error_pass').addClass('hide');
                    if(res_pass!=rppass){
                        $('#error_rppass').removeClass('hide');
                        check = false;
                    }else{
                        $('#error_rppass').addClass('hide');
                    }
                }
                if(check){
                    $.post(baseurl+'index.php/user/register',{username:res_username,email:res_email,password:hasspass,repeatpw:hasspass},function(data){
                        if(data.status=='success'){
                            alert('Your account has been create');
                            $('#loginform').removeClass('hide');
                            $('#registerform').addClass('hide');
                        }else{
                            alert('username or email already exists');
                        }
                    });
                }
                
            });
            $('#profile_panel').click(function(){
               $('#overlay').removeClass('hide');
               $('#container_profile').removeClass('hide');
               $('#container_response').addClass('hide');
               
            });
           
            $('#response_panel').click(function(){
            $('#overlay').removeClass('hide');
               $('#container_response').removeClass('hide');
               $('#container_profile').addClass('hide');
               $.post(baseurl+'index.php/worker/gettask',function(data){
                    if(data.status=='success'){
                        var array = data.msg;
                        var lat = array.lat;
                        var lng = array.lng;
                        $.post(baseurl+'index.php/geocrowd/getplace',{lat:lat,lng:lng},function(data){
                                $('#reslocation').html('Location: '+data); 
                            });
                            $('#restitle').html('Title: '+array.title);
                            
                            $('#resstart').html('Start date: '+array.startdate);
                            $('#resend').html('End date: '+array.enddate);
                        
                    }
               }); 
            });
            $(function(){
                
            });
           
});

</script>
</body>
</html>
