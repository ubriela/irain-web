<div class="container" id="test" style="height: 100%;">
	<div class="row clearfix" style="height: 100%;">
		<div class="col-md-12 column">
			<div class="tabbable" id="tabs-313941" >
				<ul class="nav nav-tabs">
                    <li class="active">
						<a href="#panel-users" data-toggle="tab">Users</a>
					</li>
					<li id="show-tasks">
						<a href="#panel-tasks" data-toggle="tab">Tasks</a>
					</li>
					<li id="show-responses">
						<a href="#panel-response" data-toggle="tab">Responses</a>
					</li>
                    <li>
						<a href="<?php echo base_url()?>index.php/admin/logout" >Logout</a>
					</li>
				</ul>
				<div class="tab-content">
                    <div class="tab-pane active" id="panel-users">
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
                        <ul class="pagination" id="userspag">
            				<li></li>
            			</ul>
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
                        <ul class="pagination" id="taskspag">
            				<li></li>
            			</ul>
					</div>
					<div class="tab-pane" id="panel-response">
                        
						
                        <div id="map-canvas" style="width: 100%;min-height: 600px;"></div>
                        <div class="panel-group" id="panel-137710" style="position: absolute; top: 100px; right: 15px; max-width: 700px;max-height: 500px;overflow: auto;">
            				<div class="panel panel-default">
            					<div class="panel-heading">
            						 <a class="panel-title" data-toggle="collapse" data-parent="#panel-137710" href="#panel-element-319821">Responses</a>
            					</div>
            					<div id="panel-element-319821" class="panel-collapse collapse in">
            						<div class="panel-body">
            							<table class="table-bordered table table-hover table-condensed" id="tableresponses">
                            <thead>
                                <tr>
                                    
            						<th>
            							Location response
            						</th>
            						
                                    <th>
            							Response
            						</th>
                                    <th>
            							Response date
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
                        
                        <ul class="pagination" id="responsespag">
            				<li></li>
            			</ul>
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