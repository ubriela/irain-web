<div class="row clearfix" id="containertaskmanager">
		<div class="col-md-12 column" >
            <input type="image" src="<?php echo base_url()?>img/closeBtn.gif" class="btnback" style="float: right;"/>
			<h3>
				Task Manager
			</h3>
            
            <button type="button" class="btn btn-default" id="refresh">Refresh</button>
            
            <select class="btn btn-default" id="loadtype">
                <option value="0">Pending</option>
                <option value="1">Completed</option>
                <option value="2">Expired</option>
            </select>
            <button type="button" class="btn btn-default" id="btndel">Delete</button>
            
            
			<table class="table" id="tabletask">
				<thead>
					<tr>
						<th>
							Location
						</th>
						
                        <th>
							Completed
						</th>
                        <th>
							Expired
						</th>
                        <th>
							Delete
						</th>
					</tr>
				</thead>
				<tbody id="containertask">
					
				</tbody>
			</table>
            
		</div>
        
	</div>
