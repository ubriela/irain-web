<?php
require_once('convert.php');
require_once('push.php');

class Worker extends Convert {
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
        
        
        $this->load->model('worker_model');
        
        $this->load->model('task_model');
        
        $this->load->model('user_model');

        $this->load->model('requester_model');
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
            $this->output->set_output(json_encode(array('status' => 'success', "msg" => $data)));
        } else {
            $this->output->set_output(json_encode(array('status' => 'error', "msg" => '0')));
        }
    }
    
    private function _json_response_debug_error($data) {
    	$this->output->set_content_type('application/json');
    	if ($data) {
    		$this->output->set_output(json_encode(array('status' => 'error', "msg" => $data)));
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
            $this->_json_response_(FALSE);
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
            $level = $this->input->post('level');
            $time = $this->input->post('responsedate');
            $lat = $this->input->post('lat');
            $lng = $this->input->post('lng');
            $flag = $this->worker_model->task_response($taskid,$code,$level,$time);
            if ($flag) {
                $flag2 = $this->worker_model->update_worker_location($taskid,$lat,$lng);
                            
            	// update status in tasks table
            	$this->task_model->update_status($taskid, 2);	// assigned
            	
            	// notify requester
            	$row = $this->requester_model->requesterid_from_taskid($taskid);
            	$message = "Your task with id " . $taskid . " has been done.";
            	if ($row) {
            		$requesterid = $row->requesterid;
            		$pushObject = new push();
            		$pushObject->push_to_userid($requesterid, $message);
            	}            	
            }
                    
            $this->_json_response($flag);
        }           
    }
    /**
     * set_isactive
     * set user's active 
     *
     *
     * [base_url]/index.php/worker/set_isactive
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
    
    /**
     * get_taskid
     * get current task id for an user
     *
     *
     * [base_url]/index.php/worker/get_taskid
     *
     * @access	public
     * @return	void
     */
    public function get_taskid(){
    	if(!$this->session->userdata('signed_in')){
//     		$this->_json_response(FALSE);
//     		$this->_json_response_debug_error($this->session->userdata('signed_in'));
    		$this->_json_response_debug_error($this->session);
    		log_message('error','user did not log in');
    		return;
    	}else{
    		$flag = $this->worker_model->get_taskid();
    		$this->_json_response($flag);
    	}
    }
    public function gettask(){
        $tmp = $this->worker_model->gettask();
        $this->_json_response($tmp);
    }
   
    
    
}
?>