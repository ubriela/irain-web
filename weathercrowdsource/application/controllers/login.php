<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
    public function __construct()
    {
        parent::__construct();
         $this->load->helper(array('form','html'));
         $this->load->library('session');
         $this->load->helper('url');
    }
	public function index()
	{
	   if($this->session->userdata('signed_in')){
	       redirect(base_url('index.php/home'));
	   }else{
	       $this->load->view('login_view');
	   }		
	}
    
    
  
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */