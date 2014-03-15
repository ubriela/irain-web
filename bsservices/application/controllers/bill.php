<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of worker
 *
 * @author ubriela
 */
class Bill extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('bill_model', '', True);
        $this->load->helper('url');
    }

    function index() {
        echo 'not yet';
    }

    function save_bill() {
        $data = json_decode($this->input->post('mydata'));
        $success = $this->bill_model->save_bill($data);
        if ($success)
            $this->response ("success", "Saving bill successfully.");
        else
            $this->response ("error", "Saving bill fail.");
    }

    function response($status, $msg) {
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(array('status' => $status, 'msg' => $msg)));
    }
}
