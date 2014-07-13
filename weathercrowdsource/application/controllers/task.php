<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of task
 *
 * @author ubriela
 */
class Task extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('task_model');

        $this->load->helper('json_response');
        $this->load->helper('url');
    }

    function index() {
//        echo 'not yet';
    }

    function task_request() {
        if (isset($_GET['taskid'])) {
            $taskid = $_GET['taskid'];
            $lat = $_GET['lat'];
            $lng = $_GET['lng'];
        } else
            return False;
        $this->task_model->task_request($taskid, $lat, $lng);
    }
    
    
    function get_taskinfo() {
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            return;
    	}
    	// get task info
        $taskid = $_POST['taskid'];
    	$task_info = $this->task_model->get_taskinfo($taskid);
        $taskresponse = $this->task_model->get_taskresponse($taskid);
        
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(array('status' => 'success', "msg" => array('info' => $task_info,'responses' => $taskresponse))));
    }
    
    private function _json_response($data) {
        $this->output->set_content_type('application/json');
        if ($data) {
            $this->output->set_output(json_encode(array('status' => 'success', "msg" => $data)));
        } else {
            $this->output->set_output(json_encode(array('status' => 'error', "msg" => '0')));
        }
    }

}
