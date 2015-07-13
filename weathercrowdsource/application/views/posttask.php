<div class="row clearfix">
		<div class="col-md-12 column" id="containerposttask">
        <input type="image" src="<?php echo base_url()?>img/closeBtn.gif" class="btnback" style="float: right;"/>
			<form role="form" >
            
            <fieldset>
            <legend>iRain - Post a task</legend>
			
				<div class="form-group">
					 <label for="exampleInputPassword1">Location</label><input type="text" disabled="true" class="form-control" id="location"/>
				</div>
                <div class="form-group" style="display: none;">
					 <label for="exampleInputPassword1">Latitude</label><input type="text" disabled="true" class="form-control" id="lat"/>
				</div>
                <div class="form-group" style="display: none;">
					 <label for="exampleInputPassword1">Longitude</label><input type="text" disabled="true" class="form-control" id="lng"/>
				</div>
                <div class="form-group">
					 <label for="exampleInputPassword1">Query Method</label>
                     <select id="typequery" class="form-control">
                        <option value="0">City</option>
                        <option value="1" selected="true">State</option>
                        <option value="2">Country</option>
                        <option value="3">Circle</option>
                     </select>
				</div>
                <div class="form-group" id="divradius" style="display: none;">
					 <label for="exampleInputPassword1">Radius</label>
                     <select id="radius" class="form-control">
                        <option value="10000">10000 m</option>
                        <option value="5000">5000 m</option>
                        <option value="2000">2000 m</option>
                        <option value="1000">1000 m</option>
                     </select>
				</div>
				<button type="button" class="btn btn-default" id="btnposttask" disabled="true">Post task</button>

                </fieldset>
			</form>
		</div>
	</div>