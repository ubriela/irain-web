<!DOCTYPE html>
<head>
    <title>Geocast Admin</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" >
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="js/geocast.js"></script>
</head>

<body onload="load()">
    <div style="width: 100%;overflow:auto;">

        <div style="float:left;">

            <form name="input" action="firstPhp.php" onsubmit="Query(); return false">
                Coordinates <input type="text" name="coordinate"><br>
                <input type="submit" value="GeoCastQuery">
            </form>

            <!--
            <form action="main.php" method="post" name="Tasks_History" onsubmit="Visualize_Task_Selected(); return false">
                <select name="task_name" id="task_name">
                    <option selected="selected">Please Choose One:</option>
                    <?php
					// define file
                    //the following code segment is to load coordinates from txt file to a drop down list
                    $file = 'res/tasks.txt';

                    $handle = @fopen($file, 'r');
                    if ($handle) {
                        while (!feof($handle)) {
                            $line = fgets($handle, 4096);
                            $item = explode('|', $line);
                            echo '<option value="' . $item[0] . '">' . $item[0] . '</option>' . "\n";
                        }
                        fclose($handle);
                    }
                    ?>
                </select>
                <input type="submit" name="submit" value="Visualize" />
            </form>
             -->

            <input type="button" value="Show Boundary" id="Boundary"
                   onClick="Show_Boundary()">
            <br>





            </input>      
        </div>
        <div style="float:left;">
            <div id="map" style="width: 680px; height: 600px"></div>
        </div>
        <div style="float:left;">
            <form name="input" action="firstPhp.php" onsubmit="Query(); return false">
                Setting Menu Come Here<br>         
                Setting Menu Come Here<br>
                Setting Menu Come Here<br>
                Setting Menu Come Here<br>
                Setting Menu Come Here<br>
                Setting Menu Come Here<br>
                Setting Menu Come Here<br>
                Setting Menu Come Here<br>

            </form>
        </div>
    </div>


</body>

</html>