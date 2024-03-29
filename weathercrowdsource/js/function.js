
function getObjectPlace(lat,lng){
    $('#typequery').val(1);
    $('#divradius').hide();
    var geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(lat, lng);
                   //alert("Else loop" + latlng);
    geocoder.geocode({'latLng': latlng}, function(results, status){
    //alert("Else loop1");
        if (status == google.maps.GeocoderStatus.OK){
            if (results[0]){
                var add= results[0].formatted_address ;
                var  value=add.split(",");
                count=value.length;
                country=value[count-1];
                state=value[count-2];
                city=value[count-3];
                if(state==null){
                    $('#location').val(country);
                    $('#typequery').val(2);
                }else{
                     $('#location').val(state);
                }
                $('#typequery').attr('disabled',false);
                $('#btnposttask').attr('disabled',false);
                
            }else {
                //alert("");
                $('#location').val(lat+", "+lng);
                $('#typequery').attr('disabled',true);
            }
        }else{
        	lat = lat.toFixed(3);
        	lng = lng.toFixed(3);
            $('#location').val(lat+", "+lng);
                $('#typequery').attr('disabled',true);
                $('#typequery').val(3);
                $('#btnposttask').attr('disabled',false);
                $('#divradius').show();
            //document.getElementById("location").innerHTML="Geocoder failed due to: " + status;
            //alert("Geocoder failed due to: " + status);
        }
    });
    
}
function msToTime(duration) {
        var milliseconds = parseInt((duration%1000)/100)
            , seconds = parseInt((duration/1000)%60)
            , minutes = parseInt((duration/(1000*60))%60)
            , hours = parseInt((duration/(1000*60*60))%24);

        if(hours>0){
            if(hours==1){
                return hours+" hour ago";
            }else{
                return hours+" hours ago";
            }
            
        }else{
            if(minutes<=1){
                return minutes+" minute ago";
            }else{
                return minutes+" minutes ago";
            }
        }
    }
function getNormalizedCoord(coord, zoom) {
      var y = coord.y;
      var x = coord.x;
    
      // tile range in one direction range is dependent on zoom level
      // 0 = 1 tile, 1 = 2 tiles, 2 = 4 tiles, 3 = 8 tiles, etc
      var tileRange = 1 << zoom;
    
      // don't repeat across y-axis (vertically)
      if (y < 0 || y >= tileRange) {
        return null;
      }
    
      // repeat across x-axis
      if (x < 0 || x >= tileRange) {
        x = (x % tileRange + tileRange) % tileRange;
      }
    
      return {
        x: x,
        y: y
      };
    }
function data24h(arraytiled) {
        var element25 = new google.maps.ImageMapType({
                    getTileUrl: function(coord, zoom) { 
                        var normalizedCoord = getNormalizedCoord(coord, zoom);
                        if (!normalizedCoord) {
                            return null;
                        }
                        var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                        if(zoom>0 && zoom<7){
                            return 'http://persiann.eng.uci.edu/htdocs/apps/gmaps/24h/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png"; 
                        }else{
                            return null;
                        }
                    },
                    tileSize: new google.maps.Size(256,256),
                    isPng: true,
                    opacity:1
                    
                });
            arraytiled.push(element25);
             var element26 = new google.maps.ImageMapType({
                    getTileUrl: function(coord, zoom) { 
                        var normalizedCoord = getNormalizedCoord(coord, zoom);
                        if (!normalizedCoord) {
                            return null;
                        }
                        var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                        if(zoom>0 && zoom<7){
                            return 'http://persiann.eng.uci.edu/htdocs/apps/gmaps/48h/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png"; 
                        }else{
                            return null;
                        }
                    },
                    tileSize: new google.maps.Size(256,256),
                    isPng: true,
                    opacity:0
                });
            arraytiled.push(element26);
             var element27 = new google.maps.ImageMapType({
                    getTileUrl: function(coord, zoom) { 
                        var normalizedCoord = getNormalizedCoord(coord, zoom);
                        if (!normalizedCoord) {
                            return null;
                        }
                        var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                        if(zoom>0 && zoom<7){
                            return 'http://persiann.eng.uci.edu/htdocs/apps/gmaps/72h/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png"; 
                        }else{
                            return null;
                        }
                    },
                    tileSize: new google.maps.Size(256,256),
                    isPng: true,
                     opacity:0
                });
            arraytiled.push(element27);
    var element1 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h23/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
    arraytiled.push(element1);
    var element2 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h22/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element2);
            var element3 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h21/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element3);
            var element4 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h20/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element4);
             var element5 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h19/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element5);
             var element6 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h18/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element6);
             var element7 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h17/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element7);
             var element8 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h16/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element8);
             var element9 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h15/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element9);
             var element10 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h14/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element10);
             var element11 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h13/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element11);
             var element12 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h12/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element12);
             var element13 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h11/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element13);
             var element14 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h10/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element14);
             var element15 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h09/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element15);
             var element16 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h08/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element16);
             var element17 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h07/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element17);
             var element18 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h06/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element18);
             var element19 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h05/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element19);
             var element20 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h04/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element20);
             var element21 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h03/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element21);
             var element22 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h02/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element22);
             var element23 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h01/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element23);
             var element24 = new google.maps.ImageMapType({
                            getTileUrl: function(coord, zoom) { 
                                var normalizedCoord = getNormalizedCoord(coord, zoom);
                                if (!normalizedCoord) {
                                    return null;
                                }
                                var y = Math.pow(2,zoom)-normalizedCoord.y-1;
                                if(zoom>0 && zoom<7){
                                    return 'http://persiann.eng.uci.edu/htdocs/app_anim/p1h00/'+zoom + "/" + normalizedCoord.x + "/" + y + ".png";
                                }else{
                                    return null;
                                }
                            },
                            tileSize: new google.maps.Size(256,256),
                            isPng: true,
                            opacity:0
                });
            arraytiled.push(element24);
            
            
}
function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
    }
    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
        }
        return "";
    }
    function checkCookie() {
        var username = getCookie("username");
        var password = getCookie("password");
        if (username!="" && password!="") {
            $('#username').val(username);
            $('#password').val(password);
            document.getElementById("checkremember").checked = true;
            //login(username,password,true);
        }else{
            $('#username').val(null);
            $('#password').val(null);
            document.getElementById("checkremember").checked = false;
        }
    }
 function login(username,password,remember){
        var hashpass = SHA512(password);
        var target = document.getElementById('loginform');
        var spinner = new Spinner().spin(target);
        $.ajax({
            type: 'POST',
            url: baseurl+'index.php/user/login',
            data: "username="+username+"&password="+hashpass,
            success:function(data){
                if(data.status=='success'){
                    if(remember){
                        setCookie("username",username,30);
                        setCookie("password",$('#password').val(),30);
                    }else{
                        setCookie("username","",30);
                        setCookie("password","",30);
                    }
                    
                    window.location= baseurl+'index.php/home';
                }else{
                    $.notify.defaults({ className: "error" });
                    $('#btnlogin').notify('Invalid username or password!',{position:"right middle",className:'error'});
                
                    //$.notify('Invalid username or password!','warn');
                }  
              spinner.stop();  
        }
        });
    }
function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 
    function SHA512(str) {
  function int64(msint_32, lsint_32) {
    this.highOrder = msint_32;
    this.lowOrder = lsint_32;
  }

  var H = [new int64(0x6a09e667, 0xf3bcc908), new int64(0xbb67ae85, 0x84caa73b),
      new int64(0x3c6ef372, 0xfe94f82b), new int64(0xa54ff53a, 0x5f1d36f1),
      new int64(0x510e527f, 0xade682d1), new int64(0x9b05688c, 0x2b3e6c1f),
      new int64(0x1f83d9ab, 0xfb41bd6b), new int64(0x5be0cd19, 0x137e2179)];

  var K = [new int64(0x428a2f98, 0xd728ae22), new int64(0x71374491, 0x23ef65cd),
      new int64(0xb5c0fbcf, 0xec4d3b2f), new int64(0xe9b5dba5, 0x8189dbbc),
      new int64(0x3956c25b, 0xf348b538), new int64(0x59f111f1, 0xb605d019),
      new int64(0x923f82a4, 0xaf194f9b), new int64(0xab1c5ed5, 0xda6d8118),
      new int64(0xd807aa98, 0xa3030242), new int64(0x12835b01, 0x45706fbe),
      new int64(0x243185be, 0x4ee4b28c), new int64(0x550c7dc3, 0xd5ffb4e2),
      new int64(0x72be5d74, 0xf27b896f), new int64(0x80deb1fe, 0x3b1696b1),
      new int64(0x9bdc06a7, 0x25c71235), new int64(0xc19bf174, 0xcf692694),
      new int64(0xe49b69c1, 0x9ef14ad2), new int64(0xefbe4786, 0x384f25e3),
      new int64(0x0fc19dc6, 0x8b8cd5b5), new int64(0x240ca1cc, 0x77ac9c65),
      new int64(0x2de92c6f, 0x592b0275), new int64(0x4a7484aa, 0x6ea6e483),
      new int64(0x5cb0a9dc, 0xbd41fbd4), new int64(0x76f988da, 0x831153b5),
      new int64(0x983e5152, 0xee66dfab), new int64(0xa831c66d, 0x2db43210),
      new int64(0xb00327c8, 0x98fb213f), new int64(0xbf597fc7, 0xbeef0ee4),
      new int64(0xc6e00bf3, 0x3da88fc2), new int64(0xd5a79147, 0x930aa725),
      new int64(0x06ca6351, 0xe003826f), new int64(0x14292967, 0x0a0e6e70),
      new int64(0x27b70a85, 0x46d22ffc), new int64(0x2e1b2138, 0x5c26c926),
      new int64(0x4d2c6dfc, 0x5ac42aed), new int64(0x53380d13, 0x9d95b3df),
      new int64(0x650a7354, 0x8baf63de), new int64(0x766a0abb, 0x3c77b2a8),
      new int64(0x81c2c92e, 0x47edaee6), new int64(0x92722c85, 0x1482353b),
      new int64(0xa2bfe8a1, 0x4cf10364), new int64(0xa81a664b, 0xbc423001),
      new int64(0xc24b8b70, 0xd0f89791), new int64(0xc76c51a3, 0x0654be30),
      new int64(0xd192e819, 0xd6ef5218), new int64(0xd6990624, 0x5565a910),
      new int64(0xf40e3585, 0x5771202a), new int64(0x106aa070, 0x32bbd1b8),
      new int64(0x19a4c116, 0xb8d2d0c8), new int64(0x1e376c08, 0x5141ab53),
      new int64(0x2748774c, 0xdf8eeb99), new int64(0x34b0bcb5, 0xe19b48a8),
      new int64(0x391c0cb3, 0xc5c95a63), new int64(0x4ed8aa4a, 0xe3418acb),
      new int64(0x5b9cca4f, 0x7763e373), new int64(0x682e6ff3, 0xd6b2b8a3),
      new int64(0x748f82ee, 0x5defb2fc), new int64(0x78a5636f, 0x43172f60),
      new int64(0x84c87814, 0xa1f0ab72), new int64(0x8cc70208, 0x1a6439ec),
      new int64(0x90befffa, 0x23631e28), new int64(0xa4506ceb, 0xde82bde9),
      new int64(0xbef9a3f7, 0xb2c67915), new int64(0xc67178f2, 0xe372532b),
      new int64(0xca273ece, 0xea26619c), new int64(0xd186b8c7, 0x21c0c207),
      new int64(0xeada7dd6, 0xcde0eb1e), new int64(0xf57d4f7f, 0xee6ed178),
      new int64(0x06f067aa, 0x72176fba), new int64(0x0a637dc5, 0xa2c898a6),
      new int64(0x113f9804, 0xbef90dae), new int64(0x1b710b35, 0x131c471b),
      new int64(0x28db77f5, 0x23047d84), new int64(0x32caab7b, 0x40c72493),
      new int64(0x3c9ebe0a, 0x15c9bebc), new int64(0x431d67c4, 0x9c100d4c),
      new int64(0x4cc5d4be, 0xcb3e42b6), new int64(0x597f299c, 0xfc657e2a),
      new int64(0x5fcb6fab, 0x3ad6faec), new int64(0x6c44198c, 0x4a475817)];

  var W = new Array(64);
  var a, b, c, d, e, f, g, h, i, j;
  var T1, T2;
  var charsize = 8;

  function utf8_encode(str) {
    return unescape(encodeURIComponent(str));
  }

  function str2binb(str) {
    var bin = [];
    var mask = (1 << charsize) - 1;
    var len = str.length * charsize;

    for (var i = 0; i < len; i += charsize) {
      bin[i >> 5] |= (str.charCodeAt(i / charsize) & mask) << (32 - charsize - (i % 32));
    }

    return bin;
  }

  function binb2hex(binarray) {
    var hex_tab = "0123456789abcdef";
    var str = "";
    var length = binarray.length * 4;
    var srcByte;

    for (var i = 0; i < length; i += 1) {
      srcByte = binarray[i >> 2] >> ((3 - (i % 4)) * 8);
      str += hex_tab.charAt((srcByte >> 4) & 0xF) + hex_tab.charAt(srcByte & 0xF);
    }

    return str;
  }

  function safe_add_2(x, y) {
    var lsw, msw, lowOrder, highOrder;

    lsw = (x.lowOrder & 0xFFFF) + (y.lowOrder & 0xFFFF);
    msw = (x.lowOrder >>> 16) + (y.lowOrder >>> 16) + (lsw >>> 16);
    lowOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

    lsw = (x.highOrder & 0xFFFF) + (y.highOrder & 0xFFFF) + (msw >>> 16);
    msw = (x.highOrder >>> 16) + (y.highOrder >>> 16) + (lsw >>> 16);
    highOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

    return new int64(highOrder, lowOrder);
  }

  function safe_add_4(a, b, c, d) {
    var lsw, msw, lowOrder, highOrder;

    lsw = (a.lowOrder & 0xFFFF) + (b.lowOrder & 0xFFFF) + (c.lowOrder & 0xFFFF) + (d.lowOrder & 0xFFFF);
    msw = (a.lowOrder >>> 16) + (b.lowOrder >>> 16) + (c.lowOrder >>> 16) + (d.lowOrder >>> 16) + (lsw >>> 16);
    lowOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

    lsw = (a.highOrder & 0xFFFF) + (b.highOrder & 0xFFFF) + (c.highOrder & 0xFFFF) + (d.highOrder & 0xFFFF) + (msw >>> 16);
    msw = (a.highOrder >>> 16) + (b.highOrder >>> 16) + (c.highOrder >>> 16) + (d.highOrder >>> 16) + (lsw >>> 16);
    highOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

    return new int64(highOrder, lowOrder);
  }

  function safe_add_5(a, b, c, d, e) {
    var lsw, msw, lowOrder, highOrder;

    lsw = (a.lowOrder & 0xFFFF) + (b.lowOrder & 0xFFFF) + (c.lowOrder & 0xFFFF) + (d.lowOrder & 0xFFFF) + (e.lowOrder & 0xFFFF);
    msw = (a.lowOrder >>> 16) + (b.lowOrder >>> 16) + (c.lowOrder >>> 16) + (d.lowOrder >>> 16) + (e.lowOrder >>> 16) + (lsw >>> 16);
    lowOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

    lsw = (a.highOrder & 0xFFFF) + (b.highOrder & 0xFFFF) + (c.highOrder & 0xFFFF) + (d.highOrder & 0xFFFF) + (e.highOrder & 0xFFFF) + (msw >>> 16);
    msw = (a.highOrder >>> 16) + (b.highOrder >>> 16) + (c.highOrder >>> 16) + (d.highOrder >>> 16) + (e.highOrder >>> 16) + (lsw >>> 16);
    highOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

    return new int64(highOrder, lowOrder);
  }

  function maj(x, y, z) {
    return new int64(
      (x.highOrder & y.highOrder) ^ (x.highOrder & z.highOrder) ^ (y.highOrder & z.highOrder),
      (x.lowOrder & y.lowOrder) ^ (x.lowOrder & z.lowOrder) ^ (y.lowOrder & z.lowOrder)
    );
  }

  function ch(x, y, z) {
    return new int64(
      (x.highOrder & y.highOrder) ^ (~x.highOrder & z.highOrder),
      (x.lowOrder & y.lowOrder) ^ (~x.lowOrder & z.lowOrder)
    );
  }

  function rotr(x, n) {
    if (n <= 32) {
      return new int64(
       (x.highOrder >>> n) | (x.lowOrder << (32 - n)),
       (x.lowOrder >>> n) | (x.highOrder << (32 - n))
      );
    } else {
      return new int64(
       (x.lowOrder >>> n) | (x.highOrder << (32 - n)),
       (x.highOrder >>> n) | (x.lowOrder << (32 - n))
      );
    }
  }

  function sigma0(x) {
    var rotr28 = rotr(x, 28);
    var rotr34 = rotr(x, 34);
    var rotr39 = rotr(x, 39);

    return new int64(
      rotr28.highOrder ^ rotr34.highOrder ^ rotr39.highOrder,
      rotr28.lowOrder ^ rotr34.lowOrder ^ rotr39.lowOrder
    );
  }

  function sigma1(x) {
    var rotr14 = rotr(x, 14);
    var rotr18 = rotr(x, 18);
    var rotr41 = rotr(x, 41);

    return new int64(
      rotr14.highOrder ^ rotr18.highOrder ^ rotr41.highOrder,
      rotr14.lowOrder ^ rotr18.lowOrder ^ rotr41.lowOrder
    );
  }

  function gamma0(x) {
    var rotr1 = rotr(x, 1), rotr8 = rotr(x, 8), shr7 = shr(x, 7);

    return new int64(
      rotr1.highOrder ^ rotr8.highOrder ^ shr7.highOrder,
      rotr1.lowOrder ^ rotr8.lowOrder ^ shr7.lowOrder
    );
  }

  function gamma1(x) {
    var rotr19 = rotr(x, 19);
    var rotr61 = rotr(x, 61);
    var shr6 = shr(x, 6);

    return new int64(
      rotr19.highOrder ^ rotr61.highOrder ^ shr6.highOrder,
      rotr19.lowOrder ^ rotr61.lowOrder ^ shr6.lowOrder
    );
  }

  function shr(x, n) {
    if (n <= 32) {
      return new int64(
       x.highOrder >>> n,
       x.lowOrder >>> n | (x.highOrder << (32 - n))
      );
    } else {
      return new int64(
       0,
       x.highOrder << (32 - n)
      );
    }
  }

  str = utf8_encode(str);
  strlen = str.length*charsize;
  str = str2binb(str);

  str[strlen >> 5] |= 0x80 << (24 - strlen % 32);
  str[(((strlen + 128) >> 10) << 5) + 31] = strlen;

  for (var i = 0; i < str.length; i += 32) {
    a = H[0];
    b = H[1];
    c = H[2];
    d = H[3];
    e = H[4];
    f = H[5];
    g = H[6];
    h = H[7];

    for (var j = 0; j < 80; j++) {
      if (j < 16) {
       W[j] = new int64(str[j*2 + i], str[j*2 + i + 1]);
      } else {
       W[j] = safe_add_4(gamma1(W[j - 2]), W[j - 7], gamma0(W[j - 15]), W[j - 16]);
      }

      T1 = safe_add_5(h, sigma1(e), ch(e, f, g), K[j], W[j]);
      T2 = safe_add_2(sigma0(a), maj(a, b, c));
      h = g;
      g = f;
      f = e;
      e = safe_add_2(d, T1);
      d = c;
      c = b;
      b = a;
      a = safe_add_2(T1, T2);
    }

    H[0] = safe_add_2(a, H[0]);
    H[1] = safe_add_2(b, H[1]);
    H[2] = safe_add_2(c, H[2]);
    H[3] = safe_add_2(d, H[3]);
    H[4] = safe_add_2(e, H[4]);
    H[5] = safe_add_2(f, H[5]);
    H[6] = safe_add_2(g, H[6]);
    H[7] = safe_add_2(h, H[7]);
  }

  var binarray = [];
  for (var i = 0; i < H.length; i++) {
    binarray.push(H[i].highOrder);
    binarray.push(H[i].lowOrder);
  }
  return binb2hex(binarray);
}
function normalBtnWeather(){
        $('#btn24h').attr('src',baseurl+'img/report24hr.png');
        $('#btn48h').attr('src',baseurl+'img/report48hr.png'); 
        $('#btn72h').attr('src',baseurl+'img/report72hr.png'); 
    }
function initAnimate(map,arraytiled){
        map.overlayMapTypes.push(arraytiled[0]);
        map.overlayMapTypes.push(arraytiled[1]);
        map.overlayMapTypes.push(arraytiled[2]);
        map.overlayMapTypes.push(arraytiled[3]);
        map.overlayMapTypes.push(arraytiled[4]);
        map.overlayMapTypes.push(arraytiled[5]);
        map.overlayMapTypes.push(arraytiled[6]);
        map.overlayMapTypes.push(arraytiled[7]);
        map.overlayMapTypes.push(arraytiled[8]);
        map.overlayMapTypes.push(arraytiled[9]);
        map.overlayMapTypes.push(arraytiled[10]);
        map.overlayMapTypes.push(arraytiled[11]);
        map.overlayMapTypes.push(arraytiled[12]);
        map.overlayMapTypes.push(arraytiled[13]);
        map.overlayMapTypes.push(arraytiled[14]);
        map.overlayMapTypes.push(arraytiled[15]);
        map.overlayMapTypes.push(arraytiled[16]);
        map.overlayMapTypes.push(arraytiled[17]);
        map.overlayMapTypes.push(arraytiled[18]);
        map.overlayMapTypes.push(arraytiled[19]);
        map.overlayMapTypes.push(arraytiled[20]);
        map.overlayMapTypes.push(arraytiled[21]);
        map.overlayMapTypes.push(arraytiled[22]);
        map.overlayMapTypes.push(arraytiled[23]);
        map.overlayMapTypes.push(arraytiled[24]);
        map.overlayMapTypes.push(arraytiled[25]);
        map.overlayMapTypes.push(arraytiled[26]);
    
    
}
