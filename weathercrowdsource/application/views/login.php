<!DOCTYPE html>
<html ng-app="myApp">
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
    <meta charset="utf-8"/>
    <!-- CSS -->
    <link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo base_url();?>css/style.css"/> 
    <link type="text/css" rel="stylesheet" href="<?php echo base_url();?>themes/1/tooltip.css" />
    <link rel="shortcut icon" href="<?php echo base_url();?>img/logoFinal.png"/>
    <!-- End CSS -->
    <title>Welcome to iRain</title>
    <!-- JAVASCRIPT -->
    
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&language=en&sensor=false&libraries=visualization,places"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>themes/1/tooltip.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/notify.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/spin.js"></script>
    <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerwithlabel/src/markerwithlabel.js"></script>
    
    <script src="<?php echo base_url();?>js/function.js"></script>
   
    <script>
         var baseurl = '<?php echo base_url();?>';
         var config = {
        			docs: "<?php echo base_url(SWAGGER_DOCS); ?>"
         };
    </script>
    

   
    
    <script src="<?php echo base_url();?>js/login.js"></script>
     
    <!-- END JAVASCRIPT -->
  </head>
  <body>
    <div id="map-canvas" style="width: 100%!important;"></div>
    <div id="boxtop">      
        <input id="pac-input" class="controls" type="text" placeholder="Search Box"/>
    </div>
    
    <div class="lightbox" id="boxbottom">
         <?php
            $this->load->view('controller');
         ?>
         
         
        
        
    </div>
    <div id="boxlogo">
    
        
    </div>
    <div id="logos">
        <a href="https://play.google.com/store/apps/details?id=irain.app" target="_blank"><img src="<?php echo base_url();?>img/logoFinal.png" height="70" width="70"/></a>
        <a href="http://chrs.web.uci.edu" target="_blank"><img src="<?php echo base_url();?>img/CHRSlogo.png" height="70px" width="70px"/></a>
        <img src="<?php echo base_url();?>img/unnamed.jpg" height="70px" width="70px"/>
     </div>
          
    <div id="overlay">
     
        
        <?php
            
            include('loginform.php');
            include('registerform.php');
        ?>
    </div>
    
    
    
  </body>
</html>