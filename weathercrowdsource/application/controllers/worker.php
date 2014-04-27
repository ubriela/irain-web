<?php
class Worker extends CI_Controller{
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
        $this->load->model('worker_model');
        $this->load->helper('json_response');
        $this->load->model('user_model');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }
    /**
     * Default function executed when [base_url]/index.php/worker
     *
     * @access	public
     * @return	void
     */
    public function index(){
        
    }
     /**
     * unassigned
     *
     *
     * [base_url]/index.php/worker/unassigned
     *
     * @access	public
     * @return	void
     */
    public function unassigned(){
        $flag = $this->worker_model->unassigned();
        $this->_json_response($flag);
    }
     private function _json_response($data) {
        $this->output->set_content_type('application/json');
        if ($data) {
            $this->output->set_output(json_encode(array('status' => 'success', "msg" => 'success')));
        } else {
            $this->output->set_output(json_encode(array('status' => 'error', "msg" => '0')));
        }
    }
      /**
     * location_report
     * update your location
     *
     *
     * [base_url]/index.php/worker/location_report
     *
     * @access	public
     * @return	void
     */
    public function location_report(){
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            return;
        }
        if ($this->form_validation->run('report_location') == FALSE){
                $this->_json_response(FALSE);
        }else{ 
            $lat = $this->input->post('lat');
            $lng = $this->input->post('lng');
            $time = $this->input->post('datetime');
            $this->worker_model->location_report($lat,$lng,$time);
            $this->_json_response(TRUE);
        }           
    }
     /**
     * task_response
     * user response a task
     *
     *
     * [base_url]/index.php/worker/task_reponse
     *
     * @access	public
     * @return	void
     */
    public function task_response(){
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            return;
        }
        if ($this->form_validation->run('task_response') == FALSE){
                $this->_json_response(FALSE);
        }else{ 
            $taskid = $this->input->post('taskid');
            $code = $this->input->post('responsecode');
            $time = $this->input->post('responsedate');
            $flag = $this->worker_model->task_response($taskid,$code,$time);
            $this->_json_response($flag);
        }           
    }
    /**
     * set_isactive
     * set user's active 
     *
     *
     * [base_url]/index.php/worker/is_active
     *
     * @access	public
     * @return	void
     */
    public function set_isactive(){
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            return;
        }
        if ($this->form_validation->run('isactive') == FALSE){
                $this->_json_response(FALSE);
        }else{
            $isactive = $this->input->post('isactive');
            $this->worker_model->set_isactive($isactive);
            $this->_json_response(true);
        }
    }
    public function is_bool($str){
        if($str == 'true' || $str == 'false')
            return true;
        return false;
    }
     public function is_number($str){
       if(is_numeric($str)){
            return TRUE;
       }else{
            $this->form_validation->set_message('is_number', 'Please enter number');
            return FALSE;
       }
    }
     public function range_value($value){
        if($value>-1 && $value <3){
            return TRUE;
        }else{
            $this->form_validation->set_message('range_value', 'Please enter from 0 to 2');
            return FALSE;
        }
    }
}
?>