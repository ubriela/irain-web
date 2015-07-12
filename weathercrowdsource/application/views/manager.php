<div class="container" id="test" style="height: 100%;">
	<div class="row clearfix" style="height: 100%;">
		<div class="col-md-12 column">
			<div class="tabbable" id="tabs-313941" >
				<ul class="nav nav-tabs">
                    <li class="active" id="show-responses">
						<a href="#panel-response" data-toggle="tab">Responses</a>
					</li>
                    <li id="show-users">
						<a href="#panel-users" data-toggle="tab">Users</a>
					</li>
					<li id="show-tasks">
						<a href="#panel-tasks" data-toggle="tab">Tasks</a>
					</li>
					
                    <li>
						<a href="<?php echo base_url()?>index.php/admin/logout" >Logout</a>
					</li>
				</ul>
				<div class="tab-content">
                    <div class="tab-pane" id="panel-users">
						<table class="table-bordered table table-hover table-condensed" id="tableusers">
                            <thead>
                                <tr>
                                    <th>
                                        Userid
                                    </th>
            						<th>
            							User name
            						</th>
            						
                                    
                                    <th>
            							Created date
            						</th>
                                    
                                    <th>
            							Action
            						</th>
            					</tr>
                            </thead>
                            <tbody>
                                <tr>
                                    
                                </tr>
                               
                            </tbody>
                        </table>
                        <div>
                            <p>Page <input type="number" value="1" min="1" max="100" style="width:70px">/<span class='numpage'>90</span></p>
                            <ul class="pagination" id="userspag">
                                <li class="previous"><a href="#" >Previous</a></li>
                                <li class="next"><a href="#" >Next</a></li>
                                <li class="go"><a href="#" >Go</a></li>
                            </ul>
                        </div>
                        
					</div>
					<div class="tab-pane" id="panel-tasks">
						<table class="table-bordered table table-hover table-condensed" id="tabletasks">
                            <thead>
                                <tr>
                                    <th>
            							Taskid
            						</th>
            						<th>
            							Location request
            						</th>
            						
                                    <th>
            							Request date
            						</th>
                                    <th>
            							Query method
            						</th>
                                    <th>
            							Completed
            						</th>
                                    
            					</tr>
                            </thead>
                            <tbody>
                                <tr>
                                    
                                </tr>
                               
                            </tbody>
                        </table>
                        <div>
                            <p>Page <input type="number" value="1" min="1" max="100" style="width:70px">/<span>90</span>
                                
                            </p>
                            <ul class="pagination" id="taskspag">
                                <li class="previous"><a href="#" >Previous</a></li>
                                <li class="next"><a href="#" >Next</a></li>
                                <li class="go"><a href="#" >Go</a></li>
                            </ul>
                        </div>
					</div>
					<div class="tab-pane active" id="panel-response">
                        
						
                        <div id="map-canvas" style="width: 100%;min-height: 600px;"></div>
                        <div class="panel-group" id="panel-137710" style="position: absolute; top: 100px; right: 15px; max-width: 700px;max-height: 500px;overflow: auto;">
            				<div class="panel panel-default">
            					<div class="panel-heading">
            						 <a class="panel-title" data-toggle="collapse" data-parent="#panel-137710" href="#panel-element-319821">Options</a>
            					</div>
            					<div id="panel-element-319821" class="panel-collapse collapse in">
            						<div class="panel-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td><label>From:</label></td>
                                                    <td><input type="date" id="weather-from" value="<?php echo date('Y-m-d'); ?>"/></td>
                                                        
                                                </tr>
                                                <tr>
                                                    <td><label>To:</label></td>
                                                    <td><input type="date" id="weather-to" value="<?php echo date('Y-m-d'); ?>"/></td>
                                                        
                                                </tr>
                                                <tr>
                                                    <td><label>Type:</label></td>
                                                    <td><select id="weather-type">
                                                        <option value="-1">All</option>
                                                        <option value="0">No Rain/Snow</option>
                                                        <option value="1">Rain</option>
                                                        <option value="2">Snow</option>
                                                    </select></td>
                                                        
                                                </tr>
                                            </tbody>
                                        </table>
                                        <p><button type="button" class="btn btn-default" id="weather-get" style="font-weight: bold;">Get weather</button></p>
            						</div>
            					</div>
            				</div>
            				
            			</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    
    <div id="overlay">
        <?php
            $this->load->view('profile');
        ?>

    </div>
</div>