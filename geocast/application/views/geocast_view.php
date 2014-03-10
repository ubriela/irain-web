<script>
    var $datasets = <?php echo json_encode($datasets); ?>;
</script>

<body onload="load()">

    <div>
        <p>
            <label for="dataset">Select dataset:</label>
        </p>
        <ol id="dataset">
            <?php
            //log_message('error', var_export($datasets->names, True));
            if ($datasets) {
                echo '<li class="ui-widget-content" value="0">' . $datasets->names[0] . '</li>' . "\n";
                for ($i = 1; $i < count($datasets->names); $i++) {
                    echo '<li class="ui-widget-content" value="' . $i . '">' . $datasets->names[$i] . '</li>' . "\n";
                }
            }
            ?>
        </ol>
    </div>
    <div id="map_canvas"></div>

    <div>
        <input class="toggle_button" type="button" onclick="toggleHeatmap()" id ="heatmap" value="Show Heatmap"/>
    </div>
    <div>
        <input class="toggle_button" type="button" value="Show Boundary" id="boundary"
               onClick="showBoundary('false')"/>
    </div>
    <div id="tabs">
        <ul>
            <p>
                <b>Geocast Queries</b>
            </p>
            <li><a href="#tabs-2">Test</a></li>
            <li><a href="#tabs-1">History</a></li>

        </ul>

        <div id="tabs-1">
            <div id="auto-row" colspan="1">
            </div>
            <button type="button" value="Clear map" id="clear_map"
                    onClick="clearMap()">Clear Map</button>
        </div>

        <div id="tabs-2">
            <form name="input" action="geocast_view.php"
                  onsubmit="drawTestTask();
                          return false" id = "geocast_test_submit">
                Task (lat,lng) <input type="text" name="coordinate"><br>
                <button type="submit" value="Submit">Submit</button>
            </form>          
        </div>

    </div>

    <div id="tabs_setting">
        <ul>
            <p>
                <b>Settings</b>
            </p>
            <li><a href="#tabs_setting-1">Algorithms</a></li>
            <li><a href="#tabs_setting-2">GUI</a></li>


        </ul>

        <div id="tabs_setting-1"  class="parameter_setting">
            Algorithm <div id='jqxdropdownalgos'>
            </div>
            Acceptance Rate (AR) <div id='jqxdropdownars'>
            </div>
            Maximum AR: <div id='jqxdropdownmars'>
            </div>
            Expected Utility: <div id='jqxdropdownus'>
            </div>
            Heuristic <div id='jqxdropdownheuristic'>
            </div>
            Sub-cell Optimization: <div id='jqxdropdownsubcell'>
            </div>
            <button type="submit" id="update_params"
                    onClick="updateParameters()"/>Update</button>
        </div>

        <div id="tabs_setting-2">
            <form name="GUI_delay" action="geocast_view.php"
                  onsubmit="set_delay();
                          return false" id="geocast_delay">
                Geocast Delay (In ms) <input type="text"
                                          name="delay"><br>
                <button type="input" value="Submit">Update</button>
            </form>
        </div>

    </div>


</div>

</body>