<script>
    var $datasets = <?php echo json_encode($datasets); ?>;
</script>

<body onload="load()">

    <div id="tabs_dataset">
        <ul>
            <p>
                <b>Prepared datasets</b>
            </p>
            <li><a href="#tabs_dataset-1">(1) Release Clean Datasets</a></li>
            <li><a href="#tabs_dataset-2">(2) Select A Dataset</a></li>
        </ul>

        <div id="tabs_dataset-1">
            <table cellpadding="3">
                <tr>
                    <td>
                        Dataset <div id='jqxdropdowndataset'>
                        </div>
                    </td>
                    <td>Privacy Budget <div id='jqxdropdownbudget'>
                        </div></td> 
                </tr>
                <tr>
                    <td> Budget Parameter <div id='jqxdropdownpercent'>
                        </div></td>

                    <td>            Customized Granularity <div id='jqxdropdownlocalness'>
                        </div></td>
                </tr>
            </table>
            <button type="submit" id="publish_dataset" style="left: 70px;"
                    onClick="publishDataset()"/>Publish Data</button>
        </div>

        <div id="tabs_dataset-2">
            <div>
                Dataset
                <div id='jqxdropdowndatasets'></div>
            </div>
                </br>
            <div>
                <input class="toggle_button" type="button" onclick="toggleHeatmap()" id ="heatmap" value="Show Heatmap"/>
            </div>

            <div>
                <input class="toggle_button" type="button" value="Show Boundary" id="boundary"
                       onClick="showBoundary('false')"/>
            </div>
        </div>

    </div>

    <div id="tabs_setting">
        <ul>
            <p>
                <b>GR Construction Parameters</b>
            </p>
            <li><a href="#tabs_setting-1">(3) Algorithm Parameters</a></li>
            <li><a href="#tabs_setting-2">GUI Parameters</a></li>

        </ul>

        <div id="tabs_setting-1"  class="parameter_setting">
            <table cellpadding=3">
                <tr>
                    <td>
                        Algorithm <div id='jqxdropdownalgos'>
                        </div>
                    </td>
                    <td>Heuristic <div id='jqxdropdownheuristic'>
                        </div></td> 
                    <td>Sub-cell Optimization <div id='jqxdropdownsubcell'>
                        </div></td>
                </tr>
                <tr>
                    <td> Expected Utility <div id='jqxdropdownus'>
                        </div></td>

                    <td>            Acceptance Rate (AR) <div id='jqxdropdownars'>
                        </div></td> 
                    <td>Maximum AR <div id='jqxdropdownmars'>
                        </div></td>
                </tr>
            </table>
            <button type="submit" id="update_params" style="left: 160px;"
                    onClick="updateParameters()"/>Update</button>
        </div>
        <div id="tabs_setting-2">
            <form name="GUI_delay" action="geocast_view.php"
                  onsubmit="set_delay();
                          return false" id="geocast_delay">
                Geocast Delay (In ms) 
                </br>
                <input type="text" name="delay" value="100"><br>
                <button type="input" value="Submit">Update</button>
            </form>
        </div>

    </div>
    
    <div id="tabs_query">
        <ul>
            <p>
                <b>(4) Geocast Queries</b>
            </p>
            <li><a href="#tabs-1">History</a></li>
            <li><a href="#tabs-2">Test</a></li>

        </ul>

        <div id="tabs-1">
            <div id="auto-row" colspan="1">
            </div>
        </div>

        <div id="tabs-2">
            <form name="input" action="geocast_view.php"
                  onsubmit="drawTestTask();
                          return false" id = "geocast_test_submit">
                Task (lat,lng) <input type="text" name="coordinate"><br>
                <button type="submit" value="Submit">Submit</button>
            </form>          
        </div>
        <button type="button" value="Clear map" id="clear_map" style="left: 35px;position:relative;"
                onClick="clearMap()">Clear Map</button>        
    </div>    

    <div id="usage">
        <iframe src="geocast/instruction" frameborder="2" width="220" height="100%"></iframe>
    </div>

    <div id="body_container" class="wrapper-rect white">
        <div id="map_canvas"></div>
    </div>
</body>