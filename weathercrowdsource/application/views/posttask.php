<form class="form-horizontal" method="post" role="form" id="post">
    <fieldset>
        <legend>iRain - Post a task</legend>
    
                        <div class="form-group">
        					 <label for="inputEmail3" class="col-sm-2 control-label">Title:</label>
        					<div class="col-sm-10">
        						<select class="form-control">
                                    <option value="Rain or not rain">Rain or not rain?</option>
                                </select>
        					</div>
        				</div>
                        <div class="form-group">
        					 <label for="inputEmail3" class="col-sm-2 control-label">Location:</label>
        					<div class="col-sm-10">
        						<input type="text" class="form-control" id="location" disabled="true"/>
        					</div>
        				</div>
                        <div class="form-group">
        					 <label for="inputEmail3" class="col-sm-2 control-label">Lat:</label>
        					<div class="col-sm-10">
        						<input type="text" class="form-control" id="lat" disabled="true"/>
        					</div>
        				</div>
                        <div class="form-group">
        					 <label for="inputEmail3" class="col-sm-2 control-label">Lng:</label>
        					<div class="col-sm-10">
        						<input type="text" class="form-control" id="lng" disabled="true"/>
        					</div>
        				</div>
                        
                        <div class="form-group">
        					 <label for="inputEmail3" class="col-sm-2 control-label">Radius:</label>
        					<div class="col-sm-10">
                                <select class="form-control" id="radius">
                                    <option value="1000">1000 m</option>
                                    <option value="2000">2000 m</option>
                                    <option value="5000">5000 m</option>
                                    <option value="10000">10000 m</option>
                                </select>
        						
        					</div>
        				</div>
                        <div class="form-group">
        					<div class="col-sm-offset-2 col-sm-10">
        						 <button type="submit" class="btn btn-default" id="btnpost">Post task</button>
                                 <button type="button" class="btn btn-default" id="btnback">Back</button>
        					</div>
				        </div>    
                        </fieldset>              
                    </form>