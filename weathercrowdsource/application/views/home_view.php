<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <!-- CSS -->
    <link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url();?>css/style.css"/> 
    <!-- End CSS -->
    <title>Welcome to iRain</title>
    <!-- JAVASCRIPT -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>js/function.js"></script>
    <script>
         var baseurl = '<?php echo base_url();?>';
         var islogin = <?php
            if(isset($userid)){
                echo 'true';
            }else{
                echo 'false';
            };
         ?>;
         var admin =  <?php
            if(isset($username)){
                if($username=='nghiairain'){
                    echo 'true';
                }else{
                    echo 'false';
                }
            }else{
                echo 'false';
            }
         ?>;
        
         
         
    </script>
    <script src="<?php echo base_url();?>js/home.js"></script>
    
    <!-- END JAVASCRIPT -->
  </head>
  <body>
    <?php
        if(isset($userid)){
            include('sidebar.php');
        }else{
            
        }
        
    ?>
    <input id="pac-input" class="controls" type="text" placeholder="Search Box"/>
    <div id="map-canvas"></div>
    <div id="overlay">
        <?php
            include('loginform.php');
            include('registerform.php');
            include('taskmanager.php');
            include('posttask.php');
            include('response.php');
            include('admin.php');
        ?>
    </div>
    <?php
        if(!isset($userid)){
            echo '
                <div id="login">
                    <label id="showlogin">Login</label> |
                    <label id="showregister">Register</label>
                </div>
            ';
        }
    ?>
  </body>
</html>