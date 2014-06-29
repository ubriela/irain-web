<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('json_response');
         $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
    }
	public function index()
	{
        if($this->session->userdata('signed_in')){
            $data = array(
                'username' => $this->session->userdata('username'),
                'avatar' => $this->session->userdata('avatar'),
                'userid' => $this->session->userdata('userid')
            );
            $this->load->view('home_view',$data);
        }else{
            redirect(base_url('index.php'));
        }
	}
    public function logout(){
        $this->session->sess_destroy();
        redirect(base_url('index.php'));
    }
    public function getmarker(){
        $sw_lat = $_POST['sw_lat'];
        $sw_lng = $_POST['sw_lng'];
        $ne_lat = $_POST['ne_lat'];
        $ne_lng = $_POST['ne_lng'];
        
    }
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */