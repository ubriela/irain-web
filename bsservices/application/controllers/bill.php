<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of worker
 *
 * @author ubriela
 */
class Bill extends CI_Controller {

    var $data = array();

    function __construct() {
        parent::__construct();
        $this->load->helper('date');
        $this->load->helper('form');
        $this->load->model('bill_model', '', True);
        $this->load->helper('url');

        $this->load->library('user_agent');

        // If user is logged in redirect to home page     
        if (!$this->session->userdata('signed_in')) {
            log_message("error", "a");
            log_message("error", "You are not authorized to access, please login");
            $this->response("error", "You are not authorized to access, please login");
        } else {
            log_message("error", "b");
            $session_data = $this->session->all_userdata();
            $this->data['userid'] = $session_data['userid'];
            $this->data['username'] = $session_data['username'];
        }
    }

    function index() {
        echo 'not yet';
    }

    function save_bill() {
        $this->load->helper('uuid');

        // Get userid from session
        log_message('debug', var_export($this->session->all_userdata(), True));
        $session_data = $this->session->all_userdata();
        $data = json_decode($this->input->post('mydata'));
        $success = $this->bill_model->save_bill($data, $session_data);
        if ($success)
            $this->response("success", "Saving bill successfully.");
        else
            $this->response("error", "Saving bill fail.");
    }

    function response($status, $msg) {
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(array('status' => $status, 'msg' => $msg)));
    }

}
