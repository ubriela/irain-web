<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
require APPPATH . '/libraries/Util_API_Controller.php';

use Swagger\Annotations as SWG;

//require_once('../convert.php');
//require_once('../push.php');

// @formatter:off
/**
 * @SWG\Tag(name="worker", description="Worker-related Operations")
 */
// @formatter:on

class Worker extends Util_API_Controller {
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
        $this->load->model('user_model');
        $this->load->model('task_model');
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
    
    // @formatter:off
    /**
     *  @SWG\Get(
     *  	path="/worker/taskid",
     *  	operationId="taskid",
     *  	summary="Get current task id for an user",
     *		description="Get current task id for an user",
     *		tags={"worker"},
     *
     *
     *		@SWG\Response(
     *			response=200,
     *		 	description="Taskid successfully returned"
     *	 	),
     *		@SWG\Response(
     *		 	response=404,
     *		 	description="Taskid not found"
     *		),
     *	 	@SWG\Response(
     *			response=500,
     *			description="Internal server error"
     *		)
     *	)
     */
     // @formatter:on
    public function taskid_get(){
    	// Log user successful login
    	$log_format = '[signed_in=%b]';
    	$log_msg = sprintf ( $log_format, $this->session->userdata('signed_in') );
    	log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
    	
    	$taskid = $this->worker_model->get_taskid();
    	
    	if($taskid['taskid'] == '-1'){
    		// Log failed login attempt
    		$log_format = 'User [userid=%s] does not have any pending task.';
    		$log_msg = sprintf ( $log_format, $this->userid);
    		log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
    		 
    		$this->response ( array (
    				'status' => FALSE,
    				'error' => 'User does not have any pending task.'
    		), 404 );
    	}
    	
    	$this->response ( array (
    			'status' => TRUE,
    			'message' => $taskid
    	), 200 );
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
    
    // @formatter:off
    /**
     *  @SWG\Put(
     *  	path="/worker/set_isactive",
     *  	operationId="set_isactive",
     *  	summary="Set user's status to online",
     *		description="Set user's status to online",
     *		tags={"worker"},
     *
	 *		@SWG\Parameter(
	 *			name="isactive",
	 *			description="Set user's status, i.e., online or offline",
	 *			in="formData",
	 *			required=true,
	 *			type="boolean"
	 *		),
	 *
	 *		@SWG\Response(
	 *			response=200,
	 *			description="Status changed successfully"
	 *		),
	 *		@SWG\Response(
	 *			response=400,
	 *			description="Invalid data supplied"
	 *		),
	 *		@SWG\Response(
	 *			response=404,
	 *			description="User not found"
	 *		),
	 *		@SWG\Response(
	 *			response=409,
	 *			description="Failed to change status"
	 *		),
	 *	 	@SWG\Response(
	 *			response=500,
	 *			description="Internal server error"
	 *		)
     *	)
     */
     // @formatter:on
    public function set_isactive_put(){
    	
    	$isactive = $this->put('isactive');
    	
    	// Custom validation data
    	$validation_data ["isactive"] = $isactive;
    	$this->form_validation->set_data ( $validation_data );
    	
		if ($this->form_validation->run ( 'api/worker/set_isactive_put' ) === FALSE) {
			// Log failed validation
			$log_format = 'failed to set status for user [userid=%s] to [isactive=%b]. Bad request [error=%s]';
			$log_msg = sprintf ( $log_format, $this->userid, $isactive, $this->form_validation->error_string () );
			log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
			
			// Bad request
			$this->response ( array (
					'status' => FALSE,
					'error' => $this->form_validation->error_string () 
			), 400 );
		}
		
		$trans_status = $this->worker_model->set_isactive($isactive);
		
    	if($trans_status == FALSE){
    		// Log failed login attempt
    		$log_format = 'Fail to update status of [userid=%s] to [isactive=%b].';
    		$log_msg = sprintf ( $log_format, $this->userid, $isactive);
    		log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
    		 
    		$this->response ( array (
    				'status' => FALSE,
    				'error' => 'Fail to update status.'
    		), 409 );
    	}
    	
    	$this->response ( array (
    			'status' => TRUE,
    			'message' => "Update status successfully."
    	), 200 );
    }
    
    

//     /**
//      *  @SWG\Put(
//      *  	path="/worker/location_report",
//      *  	operationId="location_report",
//      *  	summary="Update user location",
//      *		description="Update user location",
//      *		tags={"worker"},
//      *
//      *		@SWG\Parameter(
//      *			name="location",
//      *			description="Latitude",
//      *			in="body",
//      *			required=true,
//      *			@SWG\Schema(ref="#/definitions/Location")
//      *		),
//      *
//      *		@SWG\Response(
//      *			response=200,
//      *			description="Status changed successfully"
//      *		),
//      *		@SWG\Response(
//      *			response=400,
//      *			description="Invalid data supplied"
//      *		),
//      *		@SWG\Response(
//      *			response=404,
//      *			description="User not found"
//      *		),
//      *		@SWG\Response(
//      *			response=409,
//      *			description="Failed to change status"
//      *		),
//      *	 	@SWG\Response(
//      *			response=500,
//      *			description="Internal server error"
//      *		)
//      *	)
//      */

    // @formatter:off
    /**
     *  @SWG\Put(
     *  	path="/worker/location_report",
     *  	operationId="location_report",
     *  	summary="Update user location",
     *		description="Update user location",
     *		tags={"worker"},
     *
     *		@SWG\Parameter(
     *			name="lat",
     *			description="Latitude",
     *			in="formData",
     *			required=true,
     *			type="float"
     *		),
     *
     *		@SWG\Parameter(
     *			name="lng",
     *			description="Longitude",
     *			in="formData",
     *			required=true,
     *			type="float"
     *		),
     *
     *		@SWG\Parameter(
     *			name="address",
     *			description="Reverse geocodes the location",
     *			in="formData",
     *			required=false,
     *			type = "string"
     *		),
     *
     *		@SWG\Response(
     *			response=200,
     *			description="Status changed successfully"
     *		),
     *		@SWG\Response(
     *			response=400,
     *			description="Invalid data supplied"
     *		),
     *		@SWG\Response(
     *			response=404,
     *			description="User not found"
     *		),
     *		@SWG\Response(
     *			response=409,
     *			description="Failed to change status"
     *		),
     *	 	@SWG\Response(
     *			response=500,
     *			description="Internal server error"
     *		)
     *	)
     */
     // @formatter:on
    public function location_report_put(){
    	
    	$lat = $this->put('lat');
    	$lng = $this->put('lng');
    	
    	// Custom validation data
    	$validation_data ["lat"] = $lat;
    	$validation_data ["lng"] = $lng;
    	$this->form_validation->set_data ( $validation_data );
    	 
    	if ($this->form_validation->run ( 'api/worker/report_location_put' ) === FALSE) {
    		// Log failed validation
    		$log_format = 'Failed to update location of user [userid=%s]. Bad request [error=%s]';
    		$log_msg = sprintf ( $log_format, $this->userid, $this->form_validation->error_string () );
    		log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
    			
    		// Bad request
    		$this->response ( array (
    				'status' => FALSE,
    				'error' => $this->form_validation->error_string ()
    		), 400 );
    	}
    	
    	// Update database
    	$address = $this->removesign($this->put('address'));
    	$trans_status = $this->worker_model->location_report($this->userid,$lat,$lng,$address);
    	
    	if($trans_status == FALSE){
    		$log_format = 'Fail to update location of user [userid=%s].';
    		$log_msg = sprintf ( $log_format, $this->userid);
    		log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
    		 
    		$this->response ( array (
    				'status' => FALSE,
    				'error' => 'Fail to update location.'
    		), 409 );
    	}
    	
    	$this->response ( array (
    			'status' => TRUE,
    			'message' => "Update location successfully."
    	), 200 );
    }
    
    
    // @formatter:off
    /**
     * @SWG\Post(
     * 		path="/worker/task_response",
     * 		operationId="task_response",
     * 		summary="Worker response to a task",
     * 		description="Worker response to a task",
     * 		tags={"worker"},
     *
     *		@SWG\Parameter(
     *			name="taskid",
     *			description="Task ID",
     *			in="formData",
     *			required=true,
     *			type="integer",
     *			format="int64"
     *		),
     *
     *		@SWG\Parameter(
     *			name="responsecode",
     *			description="Response code",
     *			in="formData",
     *			required=true,
     *			type="integer",
     *			format="int32"
     *		),
     *
     *		@SWG\Parameter(
     *			name="level",
     *			description="Level",
     *			in="formData",
     *			required=false,
     *			type="integer",
     *			format="int32"
     *		),
     *
     *		@SWG\Parameter(
     *			name="responsedate",
     *			description="Response date",
     *			in="formData",
     *			required=true,
     *			type="dateTime",
     *			format="date-time"
     *		),
     *
     *		@SWG\Parameter(
     *			name="lat",
     *			description="Latitude",
     *			in="formData",
     *			required=true,
     *			type="float"
     *		),
     *
     *		@SWG\Parameter(
     *			name="lng",
     *			description="Longitude",
     *			in="formData",
     *			required=true,
     *			type="float"
     *		),
     *
     *		@SWG\Response(
     *			response=201,
     *			description="Response created successfully"
     *		),
     *		@SWG\Response(
     *			response=400,
     *			description="Invalid data supplied"
     *		),
     *		@SWG\Response(
     *			response=409,
     *			description="Failed to create response"
     *		),
     *	 	@SWG\Response(
     *			response=500,
     *			description="Internal server error"
     *		)
     *
     * )
     */
     // @formatter:on
    public function task_response_post(){
    	$taskid = $this->input->post('taskid');
    	$code = $this->input->post('responsecode');
    	$level = $this->input->post('level');
    	$time = $this->input->post('responsedate');
    	$lat = $this->input->post('lat');
    	$lng = $this->input->post('lng');
    	$address = $this->removesign($this->input->post('address'));
    	
    	if ($this->form_validation->run('api/worker/task_response_post') == FALSE || $this->form_validation->run('api/worker/report_location_put') == FALSE){
    		// Log failed validation
    		$log_format = 'Failed to response to task [userid=%s]. Bad request [error=%s]';
    		$log_msg = sprintf ( $log_format, $this->userid, $this->form_validation->error_string () );
    		log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
    			
    		// Bad request
    		$this->response ( array (
    				'status' => FALSE,
    				'error' => $this->form_validation->error_string ()
    		), 400 );
    	}

    		$this->db->trans_start();
    		$flag = $this->worker_model->task_response($taskid,$this->userid,$code,$level,$time,$lat,$lng,$address);
    		if ($flag>0) {
    			//$flag2 = $this->worker_model->update_worker_location($userid,$taskid,$lat,$lng);
    			$this->worker_model->location_report($this->userid,$lat,$lng,$address,1);
    			// update status in tasks table
    			$this->task_model->update_status($taskid, 2);	// assigned
    			$this->worker_model->unassigned($this->userid);
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
    
    			$totime = strtotime($time);
    			$date = date('Y-m-d H:i',$totime);
    			$message = "Crowdsource reported: ".$weather.", ".$date." UTC, ".$address;
    			if ($row && $flag==2) {
    				$requesterid = $row->requesterid;
    				$pushObject = new push();
    				$pushObject->push_to_userid($requesterid, $message);
    			}
    		} else {
    				// Log failed login attempt
    				$log_format = 'Fail to insert worker response data [userid=%s].';
    				$log_msg = sprintf ( $log_format, $this->userid);
    				log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
    				 
    				$this->response ( array (
    						'status' => FALSE,
    						'error' => 'Fail to update location.'
    				), 409 );
    		}
    		$trans_status = $this->db->trans_complete();
    		
    		if($trans_status == FALSE){
    			$log_format = 'Fail to update status in other tables [userid=%s].';
    			$log_msg = sprintf ( $log_format, $this->userid);
    			log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
    			 
    			$this->response ( array (
    					'status' => FALSE,
    					'error' => 'Fail to update location.'
    			), 409 );
    		}
    		
    		$log_arr = array(
    				'userid' => $this->userid,
    				'taskid' => $taskid,
    				'responsecode' => $code,
    				'level' => $level,
    				'time' => $time,
    				'latlng' => $lat.', '.$lng,
    				'address' => $address
    		);
    		log_message('info', 'task response');
    		log_message('info',var_export($log_arr, True));

    		$this->response ( array (
    				'status' => TRUE,
    				'message' => "Insert response data successfully."
    		), 200 );
    
    
    		//$this->report_similartask($lat,$lng,$userid,$code,$level,$time);
    
    }
}
?>
