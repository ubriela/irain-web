<form class="form-horizontal hide" method="post" role="form" id="loginform">
                    <fieldset>
                        <legend>iRain - Login</legend>
        				<div class="form-group">
                        
        					 <label for="inputEmail3" class="col-sm-2 control-label">Acount:</label>
        					<div class="col-sm-10">
        						<input type="text" class="form-control" id="username" placeholder="Username or Email"/>
        					</div>
        				</div>
        				<div class="form-group">
        					 <label for="inputPassword3" class="col-sm-2 control-label">Password:</label>
        					<div class="col-sm-10">
        						<input type="password" class="form-control" id="password" placeholder="Your Password"/>
        					</div>
        				</div>
        				<div class="form-group">
        					<div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
        							 <label><input type="checkbox" id="remember"/> Remember me</label>
                                     
        						</div>
        					</div>
        				</div>
        				<div class="form-group">
        					<div class="col-sm-offset-2 col-sm-10">
        						 <button type="submit" class="btn btn-default" id="btnlogin">Sign in</button>
                                 <button type="button" class="btn btn-default back">Back</button>
        					</div>
        				</div>
                        </fieldset>
        			</form>
                    <form class="form-horizontal hide" method="post" role="form" id="registerform">
                    <fieldset>
                    <legend>iRain - Register</legend>
        				<div class="form-group">
        					 <label for="inputEmail3" class="col-sm-3 control-label">Username:</label>
        					<div class="col-sm-9">
        						<input type="text" class="form-control" id="resusername" placeholder="Username"/>
                                <label for="inputEmail3" class="col-sm-0 control-label hide error" id="error_username">Enter Username</label>
        					</div>
        				</div>
                        <div class="form-group">
        					 <label for="inputEmail3" class="col-sm-3 control-label">Email:</label>
        					<div class="col-sm-9">
        						<input type="email" class="form-control" id="email" placeholder="Email"/>
                                <label for="inputEmail3" class="col-sm-0 control-label hide error" id="error_email">Email is not valid</label>
        					</div>
        				</div>
        				<div class="form-group">
        					 <label for="inputPassword3" class="col-sm-3 control-label">Password:</label>
        					<div class="col-sm-9">
        						<input type="password" class="form-control" id="respassword" placeholder="Your Password"/>
                                <label for="inputEmail3" class="col-sm-0 control-label hide error" id="error_pass">Enter Password</label>
        					</div>
        				</div>
        					<div class="form-group">
        					 <label for="inputPassword3" class="col-sm-3 control-label">RepeatPassword:</label>
        					<div class="col-sm-9">
        						<input type="password" class="form-control" id="rppassword" placeholder="Repeat Password"/>
                                <label for="inputEmail3" class="col-sm-0 control-label hide error" id="error_rppass">Password does not match</label>
        					</div>
        				</div>
        				<div class="form-group">
        					<div class="col-sm-offset-3 col-sm-10">
        						 <button type="submit" class="btn btn-default" id="btnregister">Register</button>
                                 <button type="button" class="btn btn-default back">Back</button>
        					</div>
        				</div>
                        </fieldset>
        			</form>