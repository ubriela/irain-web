<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="lolkittens" />
    <link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo base_url();?>css/style.css"/> 
    <link href="<?php echo base_url();?>css/admin.css" rel="stylesheet"/>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerwithlabel/src/markerwithlabel.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/spin.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/admin.js"></script>
    
	<title>iRain - Manager</title>
    <script>
         var baseurl = '<?php echo base_url();?>';
    </script>
</head>

<body>
<?php
    if(!$this->session->userdata('type') || $this->session->userdata('type')!=1){
        
        echo 
        '<div class="row clearfix">
		<div class="col-md-12 column" id="loginform">
			<form role="form" >
            
            <fieldset>
            <legend>iRain - Admin</legend>
				<div class="form-group">
					 <label for="exampleInputEmail1">Username</label><input type="text" class="form-control" id="username" />
				</div>
				<div class="form-group">
					 <label for="exampleInputPassword1">Password</label><input type="password" class="form-control" id="password" />
				</div>
				 <button type="button" class="btn btn-default" id="btnlogin">Login</button>
                
                </fieldset>
			</form>
		</div>
	</div>';
    }else{
        $this->load->view('manager');
    }
?>


</body>
</html>