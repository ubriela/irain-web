<div id="bottominner">
        <div style="margin-bottom: -15px;">Weather <span id="valueFromTo">0 - 24hr</span> (Lagtime: <span id="valueLagtime"></span>)
                <div>
                    <?php
                         //echo "<input id='node3' alt='3' type='button' value='' class='btn  nodeweather selected' style='height: 2px;width: 15px; padding: 3px 5px;'/>";
                        for($i=3;$i<=26;$i++){
                            
                            echo "<input id='node$i' alt='$i' type='button' value='' class='btn btn-default nodeweather'/>";
                        }
                    ?>
                    
                </div>
                <input type="image" src="<?php echo base_url();?>img/back.png" id="ccsback"/>
                <input type="image" src="<?php echo base_url();?>img/play.png" id="ccsplay"/>
                <input type="image" src="<?php echo base_url();?>img/next.png" id="ccsnext"/>
             </div>  
            <div>
            <label style="float: left;font-weight: normal;margin-bottom: -7px;">Latest Rain Totals</label>
            <label style="float: right;margin-top: -23px;">
                <p style="margin-bottom: 0px;font-weight: normal;">Speed<input type="image" src="<?php echo base_url();?>img/speed_medium.png" id="speed" alt="1"/></p>
                <span style="font-weight: normal!important;">View PERSIANN-CCS</span><input type="checkbox" id="viewccs" checked="true" style="vertical-align: middle;height: 13px;width: 13px;margin-bottom: 6px;font-weight: normal!important;margin-left: 2px;"/></label></div>
            <div style="clear: both;"></div>
            <div style="text-align: left;">
                <input type="image" src="<?php echo base_url();?>img/report24hr_1.png" id="btn24h" style=""/>
                <input type="image" src="<?php echo base_url();?>img/report48hr.png" id="btn48h"/>
                <input type="image" src="<?php echo base_url();?>img/report72hr.png" id="btn72h"/>
                <img src="<?php echo base_url();?>img/legend_mini.png" style="float: right;" height="38" width="330"/>
            </div>
             
        </div>