<?php
class Admin extends CI_Controller{
    public function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('json_response');
        $this->load->helper('form');
        $this->load->model('admin_model');
       
    }
    public function index(){
        $this->load->view('admin');
    }
    public function login(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        if($username == 'irainadmin' && $password == 'chrs2015'){
            $sess_array = array(
                    'signed_in' => True,
                    'type' => 1
            );
            $this->session->set_userdata($sess_array);
            $this->_json_response(true);
        }else{
            $this->_json_response(false);
        }
    }
    public function listUsers(){
        $query = $this->admin_model->listUsers();
        $this->_json_response($query->result_array());
    }
    public function getUserInfo(){
        $userid = $this->input->post('userid');
        $query = $this->admin_model->getUserInfo($userid);
        if($query){
            $this->_json_response($query->result_array());
        }
    }
    public function getUserNumTasks(){
        $userid = $this->input->post('userid');
        $query = $this->admin_model->getUserNumTasks($userid);
        echo $query;
        
    }
    public function getUserNumResponses(){
        $userid = $this->input->post('userid');
        $query = $this->admin_model->getUserNumResponses($userid);
        
            echo $query;
        
    }
    public function getNumUsers(){
        $num = $this->admin_model->getNumUsers();
        $this->_json_response($num);
    }
    public function deleteUser(){
        $userid = $this->input->post('userid');
        $query = $this->admin_model->deleteUser($userid);
        $this->_json_response($query);
    }
    public function getNumTasks(){
        $num = $this->admin_model->getNumTasks();
        $this->_json_response($num);
    }
    public function getNumResponses(){
        $num = $this->admin_model->getNumResponses();
        $this->_json_response($num);
    }
    public function listTasks(){
        $query = $this->admin_model->listTasks();
        $this->_json_response($query->result_array());
    }
    public function listResponses(){
        $query = $this->admin_model->listResponses();
        $this->_json_response($query->result_array());
    }
    public function deleteResponse(){
        $flag = $this->admin_model->deleteResponse();
        $this->_json_response($flag);
    }
    public function logout(){
        $this->session->sess_destroy();
        redirect(base_url('index.php/admin'));
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
?>