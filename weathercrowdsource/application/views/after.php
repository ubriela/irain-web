<div class="list-group-item hover" id="profile_panel">
    <img src="<?php echo base_url();?>img/secrecy-icon.png" width="30" height="30"/><span class="itemmenu hide">Profile</span>
</div>
<div class="list-group-item hover" id="response_panel">
    <img src="<?php echo base_url();?>img/secrecy-icon.png" width="30" height="30"/><span class="itemmenu hide">Response</span>
</div>
<div class="list-group-item hover" id="tasks_panel">
                <img src="<?php echo base_url();?>img/secrecy-icon.png" width="30" height="30"/><span class="itemmenu hide">Tasks manager</span>
            </div>
<?php
    if($username=='nghiairain'){
        echo '
            <div class="list-group-item hover" id="admin_panel">
                <img src="'.base_url().'img/secrecy-icon.png" width="30" height="30"/><span class="itemmenu hide">Admin boards</span>
            </div>
        
        ';
    }
?>
<div class="list-group-item hover" id="logout">
    <img src="<?php echo base_url();?>img/secrecy-icon.png" width="30" height="30"/><span class="itemmenu hide">logout</span>
</div>
