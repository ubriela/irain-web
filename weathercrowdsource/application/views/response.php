<div class="row clearfix">
		<div class="col-md-12 column">
        
			<form role="form" id="responsetask">
            
            <fieldset><legend>iRain - Task response</legend>
				<div class="form-group">
					 <label for="exampleInputPassword1">Please report weather at your location</label><input type="text" class="form-control" id="responsetitle" disabled="true" style="display:none;"/>
				</div>
		        <div class="form-group">
					 <label for="exampleInputPassword1">Request location</label><input type="text" class="form-control" id="responselocation" disabled="true"/>
				</div>
                <div class="form-group">
                <label for="exampleInputPassword1">Weather: </label> <label id="code"></label><br />
                    <button type="button" id="btnrain"></button>
                    <button type="button" id="btnsnow"></button>
                    <button type="button" id="btnnone"></button>
                    
                </div>
                 <div class="form-group" id="conlevel">
					 <label for="exampleInputPassword1">Level</label>
                     <select class="form-control" id="level">
                    <option value="0">Light</option>
                    <option value="1">Moderate</option>
                    <option value="2">Heavy</option>
                </select>   
				</div>
                <div class="form-group">
					 <label for="exampleInputPassword1">Time</label>
                     <select class="form-control" id="timeresponse">
                    <option value="0">now</option>
                    <?php
                        for($i=1;$i<25;$i++){
                            echo " <option value=$i>$i".'hour ago</option>';
                        }
                    ?>
                   
                </select>   
				</div>
				<button type="button" class="btn btn-default" id="btnresponse" disabled="true">Response</button>
                <button type="button" class="btn btn-default btnback">Back</button>
			</fieldset>
            </form>
            
		</div>
	</div>