<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$CSP_URL = 'http://geocrowd2.cloudapp.net';

class Geocast extends CI_Controller {

    function __construct() {
        parent :: __construct();
        $this->load->helper(array(
            'form',
            'url'
        ));
        $this->load->library('form_validation');
        $this->load->model('task_model', '', True);
    }

    /**
     * Index Page for this controller.
     *
     */
    public function index() {
        global $CSP_URL;
        $response = file_get_contents($CSP_URL . '/dataset');
        $response = json_decode($response);
        $data['datasets'] = $response;
        $data['CSP_URL'] = $CSP_URL;
        $this->load->view('templates/header');
        $this->load->view('geocast_view', $data);
        $this->load->view('templates/footer.php');
    }

    public function tasks() {
        //the following code segment is to load coordinates from txt file
        $dataset = $_GET['dataset'];
        $tasks = array();
        if ($dataset == 'gowallala') {
            $data = $this->task_model->history_tasks();
            foreach ($data as $item) {
                $task = round($item['Lng'], 6, PHP_ROUND_HALF_DOWN) . ',' . round($item['Lat'], 6, PHP_ROUND_HALF_DOWN);
                log_message('error', var_export($task, True));
                $tasks[] = $task;
            }
        } else {
            $file = 'res/' . $dataset . '.txt';

            $handle = @fopen($file, 'r');
            if ($handle) {
                while (!feof($handle)) {
                    $line = fgets($handle, 4096);
                    $tasks[] = $line;
                }
                fclose($handle);
            }
        }

        // log_message('error', var_export($tasks, True));
        // prepare xml data
        if (!empty($tasks)) {
            header('Content-type: text/xml');
            echo "<tasks>";
            foreach ($tasks as $task) {
                $item = explode(',', $task);
                echo "<task>";
                echo "<lat>" . round($item[0], 6, PHP_ROUND_HALF_DOWN) . "</lat>";
                echo "<lng>" . round($item[1], 6, PHP_ROUND_HALF_DOWN) . "</lng>";
                echo "</task>";
            }
            echo "</tasks>";
        }
    }

    public function download_dataset() {
        $this->load->helper('download');

        if (isset($_GET['name']))
            $dataset = $_GET['name'];
        else
            return False;

        $file = 'res/' . $dataset . '.dat';

        $data = file_get_contents($file);
        echo $data;
    }

}
