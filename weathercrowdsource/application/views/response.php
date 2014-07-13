<div class="row clearfix">
		<div class="col-md-12 column">
        
			<form role="form" id="responsetask">
            <fieldset><legend>iRain - Response task</legend>
				<div class="form-group">
					 <label for="exampleInputEmail1">Your location</label><input type="text" class="form-control" id="currentlocation" />
				</div>
                
				<div class="form-group">
					 <label for="exampleInputPassword1">Title</label><input type="text" class="form-control" id="responsetitle" disabled="true" />
				</div>
		        <div class="form-group">
					 <label for="exampleInputPassword1">Request location</label><input type="text" class="form-control" id="responselocation" disabled="true"/>
				</div>
                <div class="form-group">
                <label for="exampleInputPassword1">Weather</label>
                <select class="form-control" id="code">
                    <option value="0">None</option>
                    <option value="1">Rain</option>
                    <option value="2">Snow</option>
                </select> 
                </div>
                 <div class="form-group">
					 <label for="exampleInputPassword1">Level</label>
                     <select class="form-control" id="level">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
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