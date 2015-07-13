<div class="row clearfix">
		<div class="col-md-12 column" id="containerlocation">
        <input type="image" src="<?php echo base_url()?>img/closeBtn.gif" class="btnback" style="float: right;"/>
			<form >
            
            <fieldset><legend>iRain - Update Location</legend>
				<div class="form-group">
					 <label for="exampleInputEmail1">Current Location</label>
                     <input type="text" class="form-control" id="uplocation_address" />
                     <input type="text" class="form-control" id="uplocation_lat" value="0" style="display:none;" />
                     <input type="text" class="form-control" id="uplocation_lng" value="0" style="display:none;"/>
				</div>
				<button type="button" class="btn btn-default" id="btnup" disabled="true">Update</button>
               
			</fieldset>
            </form>
            
		</div>
</div>