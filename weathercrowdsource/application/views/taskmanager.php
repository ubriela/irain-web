<div class="row clearfix" style="background: white;height: 100%;overflow: auto;" id="taskmanager">
		<div class="col-md-12 column" >
			<h3>
				Task manager
			</h3>
            <button type="button" class="btn btn-default btnback">Back</button>
            <button type="button" class="btn btn-default" id="refresh">Refresh</button>
            <button type="button" class="btn btn-default" id="btndel">Delete</button>
            <select class="btn btn-default" id="loadtype">
                <option value="0">Wait</option>
                <option value="1">Completed</option>
                <option value="2">Expired</option>
            </select>
			<table class="table">
				<thead>
					<tr>
						<th>
							Title
						</th>
						<th>
							Location
						</th>
						<th>
							Start date
						</th>
						<th>
							End date
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
        <img src="<?php echo base_url()?>img/loading.gif" id="loading"/>
	</div>