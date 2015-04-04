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
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            return;
        }
        $flag = $this->worker_model->unassigned();
        $this->_json_response($flag);
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
            $userid = $this->session->userdata('userid');
            $lat = $this->input->post('lat');
            $lng = $this->input->post('lng');
            $address = $this->input->post('address');
            $this->worker_model->location_report($userid,$lat,$lng,$address);
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
            $userid = $this->session->userdata('userid');
            $taskid = $this->input->post('taskid');
            $code = $this->input->post('responsecode');
            $level = $this->input->post('level');
            $time = $this->input->post('responsedate');
            $lat = $this->input->post('lat');
            $lng = $this->input->post('lng');
            $address = $this->input->post('address');
            $flag = $this->worker_model->task_response($taskid,$userid,$code,$level,$time,$lat,$lng,$address);
            if ($flag) {
                //$flag2 = $this->worker_model->update_worker_location($userid,$taskid,$lat,$lng);
                $this->worker_model->location_report($userid,$lat,$lng,$address,1);            
            	// update status in tasks table
            	$this->task_model->update_status($taskid, 2);	// assigned
            	// notify requester
            	$row = $this->requester_model->requesterid_from_taskid($taskid);
                $weather = "";
                if($code==0){
                    $weather = "No Rain/Snow";
                }else{
                    if($code == 1){
                        $weather = "Rain";
                    }else{
                        $weather = "Snow";
                    }
                    switch ($level) {
                        case 0:
                            $weather .="(Light)";
                            break;
                        case 1:
                            $weather .="(Moderate)";
                            break;
                        case 2:
                            $weather .="(Heavy)";
                            break;
                    }
                }
                


            	$message = "Crowdsource reported: ".$weather.", ".substr($time, 0, 13).", ".$address;
            	if ($row) {
            		$requesterid = $row->requesterid;
            		$pushObject = new push();
            		$pushObject->push_to_userid($requesterid, $message);
            	}
                $this->worker_model->unassigned($userid);            	
            }
            
            $this->_json_response($flag);
            //$this->report_similartask($lat,$lng,$userid,$code,$level,$time);    
            
        }           
    }
     
    public function report_similartask($lat,$lng,$userid,$code,$level,$time){
        $arr = $this->task_model->get_similartask($lat,$lng);
            if($arr->num_rows()>0){
                foreach($arr->result() as $row){
                    $taskid = $row->taskid;
                    $this->task_model->matched($taskid,$userid);
                    $flag = $this->worker_model->task_response($taskid,$userid,$code,$level,$time);
                    if($flag){
                        $this->worker_model->update_worker_location($userid,$taskid,$lat,$lng);
                        $this->task_model->update_status($taskid, 2);
                        $rows = $this->requester_model->requesterid_from_taskid($taskid);
                    	$message = "Your task with id " . $taskid . " has been done.";
                    	if ($rows) {
                    		$requesterid = $rows->requesterid;
                    		$pushObject = new push();
                    		$pushObject->push_to_userid($requesterid, $message);
                    	}
                    }
                   
                    
                }
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
    		$this->_json_response_debug_error("user did not signed in");
    		log_message('error','user did not log in');
    		return;
    	}else{
    		$flag = $this->worker_model->get_taskid();
    		$this->_json_response($flag);
    	}
    }
    public function gettask(){
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            return;
        }
        $userid = $this->session->userdata('userid');
        $response = $this->worker_model->gettask($userid);
        $this->_json_response($response);
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
    
    
    
}
?>
