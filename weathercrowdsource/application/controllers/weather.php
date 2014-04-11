<?php
class Weather extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model('user_model');
        $this->load->model('weather_model');
        $this->load->helper('uuid_helper');
        $this->load->library('session');
        $this->load->helper('json_response');
    }
    public function index($lat,$lng,$code){
        $this->weather_model->insert_location($lat,$lng,$code);
    }
    public function test(){
        $this->weather_model->test_insert();
    }
    private function _json_response($data) {

        $this->output->set_content_type('application/json');

        if (!empty($data)) {
            $this->output->set_output(json_encode(array('status' => 'success', "msg" => $data)));
        } else {
            $this->output->set_output(json_encode(array('status' => 'error', "msg" => '0')));
        }
    }
}
?>