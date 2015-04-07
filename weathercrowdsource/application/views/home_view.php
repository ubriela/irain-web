<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <link rel="shortcut icon" href="<?php echo base_url();?>img/logoFinal.png"/>
    <!-- CSS -->
    <link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url();?>css/style.css"/> 
    
    <link type="text/css" rel="stylesheet" href="<?php echo base_url();?>themes/1/tooltip.css" />
    <!-- End CSS -->
    <title>Welcome to iRain</title>
    <!-- JAVASCRIPT -->
    
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="<?php echo base_url();?>js/jquery.velocity.min.js"></script>
    
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&language=en"></script>
    <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markermanager/src/markermanager.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <script>
         var baseurl = '<?php echo base_url();?>';    
    </script>
    <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerwithlabel/src/markerwithlabel.js"></script>
    <script src="<?php echo base_url();?>js/function.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>themes/1/tooltip.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/notify.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jstz-1.0.4.min.js"></script>
    
    
    
    <script src="<?php echo base_url();?>js/home.js"></script>
    
    <!-- END JAVASCRIPT -->
  </head>
  <body>
  <div id="wrapper">
      <?php
        $this->load->view('sidebar');
      ?>
    
    <div id="map-canvas"></div>
    <div id="boxtop" style="margin-left: -120px!important;">
        <input id="pac-input" class="controls" type="text" placeholder="Search Box"/>
       
        
        <input id="btnmylocation" class="btn btn-default" type="image" src="<?php echo base_url();?>img/my_location_ic.png"/>     
    </div>
     <div id="boxbottom" style="margin-left: -130px!important;">
        <?php
            $this->load->view('controller');
        ?>
        
     </div>
     <div id="logos">
        <img src="<?php echo base_url();?>img/CHRSlogo.png" height="70px" width="70px"/>
        <img src="<?php echo base_url();?>img/unnamed.jpg" height="70px" width="70px"/>
     </div>
     
        
    </div>
    <div id="overlay">
        <?php
            $this->load->view('taskmanager');
            $this->load->view('posttask');
            $this->load->view('response');
            $this->load->view('setting');
            $this->load->view('update');
            $this->load->view('weather');
            $this->load->view('about');
              
        ?>
        <img src="<?php echo base_url()?>img/loading.gif" id="loading" width="50" height="50"/>
    </div>
    </div>
    <div class="hide">
        <div id="exit">
            <h3>Do you want logout?</h3>
            <button type="button" class="btn btn-default" id="yes">Yes</button>
            <button type="button" class="btn btn-default" id="no" style="margin-left: 30px;">No</button>
        </div>
        <audio id="xyz" src="<?php echo base_url();?>sound/notifycation.mp3" preload="auto"></audio>
    </div>
  </body>
</html>