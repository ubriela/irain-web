<div class="row clearfix">
		<div class="col-md-12 column" id="loginform">
		<input type="image" src="<?php echo base_url()?>img/closeBtn.gif" class="btnback" style="float: right;"/>
			<form role="form" >
            
            <fieldset>
            <legend>iRain - Login</legend>
				<div class="form-group">
					 <label for="exampleInputEmail1">Username</label><input type="text" class="form-control" id="username" />
				</div>
				<div class="form-group">
					 <label for="exampleInputPassword1">Password</label><input type="password" class="form-control" id="password" />
				</div>
				<div class="checkbox">
					 <label><input type="checkbox" ng-model="rememberMe" id="checkremember"/> Remember me</label>
				</div> <button type="button" class="btn btn-default" id="btnlogin">Login</button>
                
                </fieldset>
			</form>
		</div>
	</div>