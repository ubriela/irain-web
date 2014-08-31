<div id="container_profile" class="hide">
    <div class="row clearfix">
		<div class="col-md-3 column" id="leftinfo">
			<img alt="140x140" src="<?php echo base_url().$avatar;?>" class="img-thumbnail" /> <span class="label label-primary"><?php echo $username?></span>
		</div>
		<div class="col-md-9 column" id="rightinfo">
			<div class="list-group">
				 <a href="#" class="list-group-item active">My info</a>
                 
				<div class="list-group-item">
					First name: <?php echo $firstname;?><span class="badge" id="edit_username">edit</span>
				</div>
				<div class="list-group-item">
					Last name: <?php echo $lastname;?><span class="badge">edit</span>
				</div>
                <div class="list-group-item">
					Phone number: <?php echo $phone_number;?><span class="badge">edit</span>
				</div> 
                <div class="list-group-item">
					Email: <?php echo $email;?>
				</div>
                  
                <div class="list-group-item">
					Create date: <?php echo $created_date;?>
				</div> 
                <a class="list-group-item active">My contributions</a>
                <div class="list-group-item">
					Tasks request: <?php echo $numrequest;?>
				</div>
                <div class="list-group-item">
					Tasks response: <?php echo $numresponse;?>
				</div>  
			</div>
             <button type="button" class="btn btn-default back">Back</button>
		</div>
	</div>
</div>