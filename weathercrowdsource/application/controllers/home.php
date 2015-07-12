<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('user_model');
    }
	public function index()
	{
        if($this->session->userdata('signed_in')){
            $data = $this->user_model->get_userinfo();
            $this->load->view('home_view',$data);
        }else{
            redirect(base_url());
        }
	}
    public function logout(){
         if($this->session->userdata('signed_in')){
            $userid = $this->session->userdata('userid');
            $this->db->set('islogout',1);
            $this->db->where('userid',$userid);
            $this->db->update('users');
            $this->session->sess_destroy();
        }
        redirect(base_url());
    }
   
    
    
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */