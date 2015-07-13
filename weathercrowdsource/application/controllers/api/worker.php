<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

use Swagger\Annotations as SWG;

//require_once('../convert.php');
//require_once('../push.php');

// @formatter:off
/**
 * @SWG\Tag(name="worker", description="Worker-related Operations")
 */
// @formatter:on

class Worker extends API_Controller {
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
        $this->load->helper('text');
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
     *		 	description="Taskid successfully returned",
     *			@SWG\Schema(type = "string")
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
    	if(!$this->session->userdata('signed_in')){
    		$this->_json_response_debug_error("user did not signed in");
    		log_message('error','user did not log in');
    		return;
    	}else{
    		$flag = $this->worker_model->get_taskid();
    		$this->response ($flag, 200);
    	}
    }
    
}
?>
