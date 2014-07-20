<div id="sidebar" class="col-md-2 column">
    <div id="info">
        <img src="<?php echo base_url().$avatar?>" width="150" height="150" id="avatar"/><br />
        <label id="lbusername"><?php echo $username?></label>
    </div>
    <div id="nav">
        <ul>
            <li id="showupdate">Update location</li>
            <li id="showresponse">Task Response      <label style="color: red;" id="newtask">New</label></li>
            <li id="showtask">Tasks manager</li>
            <?php 
            if($username=='irainvn'){
                echo '<li id=showadmin>Admin boards</li>';
            }
            ?>
            
            <li id="logout">Logout</li>
        </ul>
    </div>
</div>