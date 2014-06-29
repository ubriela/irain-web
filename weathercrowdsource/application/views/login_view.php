<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>Welcome to iRain</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content=""/>
  <meta name="author" content=""/>

	<!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
	<!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
	<!--script src="js/less-1.3.3.min.js"></script-->
	<!--append ‘#!watch’ to the browser URL, then refresh the page. -->
	
	<link href="css/bootstrap.min.css" rel="stylesheet"/>
	<link href="css/style.css" rel="stylesheet"/>

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons -->
  
  <link rel="shortcut icon" href="<?php echo base_url();?>img/icon.png"/>
  
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
    <script type="text/javascript" src="js/login.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#btnlogin').click(function(event){
                event.preventDefault();

                var username = $('#inputEmail3').val();
                var hashpass = SHA512($('#inputPassword3').val());
                $.ajax({
                   type: 'POST',
                   url: "<?php echo base_url();?>index.php/user/login",
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
        });
   
    </script>
</head>

<body>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<form class="form-horizontal" method="post" role="form">
				<div class="form-group">
					 <label for="inputEmail3" class="col-sm-2 control-label">Acount:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="inputEmail3" placeholder="Username or Email"/>
					</div>
				</div>
				<div class="form-group">
					 <label for="inputPassword3" class="col-sm-2 control-label">Password:</label>
					<div class="col-sm-10">
						<input type="password" class="form-control" id="inputPassword3" placeholder="Your Password"/>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
							 <label><input type="checkbox"/> Remember me</label>
                             <label>Register</label>
                             <label>Forgotpassword</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						 <button type="submit" class="btn btn-default" id="btnlogin">Sign in</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>
