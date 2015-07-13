<?php
class Admin extends CI_Controller{
    public function __construct(){
        parent::__construct();
        
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('json_response');
        $this->load->helper('form');
        $this->load->model('admin_model');
        $this->load->model('user_model');
       
    }
    public function index(){
        $this->load->view('admin');
    }
    public function login(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        if($username == 'irainadmin'){
            $row = $this->user_model->get_user($username);
            if ($row) {
                $user_id = $row->userid;
                $username = $row->username;
                $avatar = $row->avatar;
                $fullname = $row->firstname . ' ' . $row->lastname;
                $db_password = $row->password;
                $salt = $row->salt;
                $password = hash('sha512', hash('sha512',$password) . $salt);
                // Passwords must match
                if ($db_password == $password) {
                // Create a session
                    $sess_array = array(
                        'userid' => $user_id,
                        'username' => $username,
                        'avatar' => $avatar,
                        'fullname' => $fullname,
                        'signed_in' => True
                    );    
                    log_message('debug', var_export($sess_array, True));
                    $this->db->set('islogout',0);
                    $this->db->where('userid',$user_id);
                    $this->db->update('users');
                    $this->session->set_userdata($sess_array);
                    
                   
                   $this->_json_response(true);
                }else{
                    $this->_json_response(false);
                }
            }else{
                $this->_json_response(false);
            }
            
        }else{
            $this->_json_response(false);
        }
    }
    public function listUsers(){
        $query = $this->admin_model->listUsers();
        if($query){
            $this->_json_response($query->result_array());
        }
        
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