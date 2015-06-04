<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('json_response');
         $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->model('user_model');
    }
	public function index()
	{
	   if($this->session->userdata('userid')){
	       $data = $this->user_model->get_userinfo();
            $this->load->view('home_view',$data);
	   }else{
	       $this->load->view('login');
	   }
        
      
	}
    public function logout(){
        $this->session->sess_destroy();
        redirect(base_url('index.php'));
    }
   
    
    
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */