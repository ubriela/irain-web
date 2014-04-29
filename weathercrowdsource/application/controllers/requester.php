<?php
class Requester extends CI_Controller{
    /**
     * Constructor
     *
     * Loads user model and libraries. They are available for all methods
     *
     * @access	public
     * @return	void
     */
     
    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('json_response');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('requester_model');
    }
     /**
     * Default function executed when [base_url]/index.php/requester
     *
     * @access	public
     * @return	void
     */
    public function index(){
      
    }
    /**
     * task request
     *
     * One of the two urls requested
     *
     * 1. [base_url]/index.php/requester/task_request
     * @access	public
     * @return	void
     */
    public function task_request(){
//        if(!$this->session->userdata('signed_in')){
//            $this->_json_response(FALSE);
//            return;
//        }
        if ($this->form_validation->run('task_request') == FALSE){
            $data = "form validation error";
            $this->_json_response($data);
        }else{ 
            log_message('debug', "task_request");
            $title = $this->input->post('title');
            $lat = $this->input->post('lat');
            $lng = $this->input->post('lng');
            $requestdate = $this->input->post('requestdate');
            $startdate = $this->input->post('startdate');
            $enddate = $this->input->post('enddate');
            $type = $this->input->post('type');
            $radius = $this->input->post('radius');
            $flag = $this->requester_model->task_request($title,$lat,$lng,$requestdate,$startdate,$enddate,$type,$radius);
            $this->_json_response($flag);
        }
    }
    /**
     * Callback validation to Check input is number
     * @return	true if $str is number else false
     */
    public function is_number($str){
       if(is_numeric($str)){
            return TRUE;
       }else{
            $this->form_validation->set_message('is_number', 'Please enter number');
            return FALSE;
       }
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