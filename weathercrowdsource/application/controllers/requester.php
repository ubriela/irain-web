<?php
require_once('geocrowd.php');
require_once('push.php');
class Requester extends Geocrowd{
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
     * function executed when [base_url]/index.php/requester/submitted_tasks
     *
     * @access	public
     * @return	a list submitted tasks
     */
    public function submitted_tasks(){
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            return;
        }
        if ($this->form_validation->run('submitted_tasks') == FALSE){
                $this->_json_response(FALSE);
        }else{
            $number = $this->input->post('number');
            $this->requester_model->submitted_task($number);
        }
    }
    /**
     * function executed when [base_url]/index.php/requester/submitted_tasks_type
     *
     * @access	public
     * @return	a list completed task
     */
    public function submitted_tasks_type(){
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            return;
        }else{
            $this->requester_model->submitted_task_type();
        }
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
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            return;
        }
        if ($this->form_validation->run('task_request') == FALSE){
                $this->_json_response(FALSE);
        }else{
            
            $userid = $this->session->userdata('userid');
            $lat = $this->input->post('lat');
            $lng = $this->input->post('lng');
            $requestdate = $this->input->post('requestdate');
            $startdate = $this->input->post('startdate');
            $enddate = $this->input->post('enddate');
            $type = $this->input->post('type');
            $radius = $this->input->post('radius');
            $message = 'please report weather at your location, Thank you';
            $arrayAddress = $this->worker_model->getArrayAddress($lat,$lng);     
            if($arrayAddress){
                $place = $arrayAddress[0].",".$arrayAddress[1].",".$arrayAddress[2];
            }else{
                $place = round($lat,3).', '.round($lng,3);
            }
            $taskid = $this->requester_model->task_request($userid,$lat,$lng,$requestdate,$startdate,$enddate,$type,$radius,$place);
            
            //$this->assign_tasks();
            //if($type<3)
                //$area = trim($arrayAddress[$type]);
            //$this->task_query($userid,$taskid,$lat,$lng,$radius,$message,$type,$area);
            //$req = curl_init();
            //curl_setopt($req, CURLOPT_URL,"http://127.0.0.1/weather-crowdsource/weathercrowdsource/index.php/geocrowd/assign_tasks");
            //curl_exec($req);
            $this->_json_response($taskid);
            
            
            // 
            
        }
    }
    /**
     * 
     *  delete task base type(1:completed task,2: expired task )
     * [base_url]/index.php/requester/delete_tasks
     * @access	public
     * @param int type
     * @return	void
     */
    public function delete_tasks(){
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            redirect(base_url('index.php'));
            return;
        }
        $type = $_POST['type'];
        if($type==1){
            $this->requester_model->delete_completed();
        }
        if($type==2){
            $this->requester_model->delete_expired();
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
    
     
    /**
     * load all pending task
     * [base_url]/index.php/requester/delete_tasks
     * @access	public
     * @return	list pending task
     */
    public function list_pending_task(){
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            redirect(base_url('index.php'));
            return;
        }
        $this->requester_model->list_pending_task();
    }
    /**
     * load all completed task
     * [base_url]/index.php/requester/delete_tasks
     * @access	public
     * @return	list completed task
     */
    public function list_completed_task(){
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            redirect(base_url('index.php'));
            return;
        }
        $this->requester_model->list_completed_task();
    }
    /**
     * load all expired task
     * [base_url]/index.php/requester/delete_tasks
     * @access	public
     * @return	list expired task
     */
    public function list_expired_task(){
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            redirect(base_url('index.php'));
            return;
        }
        $this->requester_model->list_expired_task();
    }
    public function currentlocation(){
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(FALSE);
            redirect(base_url('index.php'));
            return;
        }
        $userid = $this->session->userdata('userid');
        $query = $this->db->select('ST_X(location_report."location") as lat,ST_Y(location_report."location") as lng,address')->from('location_report')->where('userid',$userid)->get();
        if($query->num_rows()>0){
            $lat = $query->row()->lat;
            $lng = $query->row()->lng;
            $address = $query->row()->address;
            $array = array('lat'=>$lat,'lng'=>$lng,'address'=>$address);
            $this->_json_response($array);
        }else{
            $this->_json_response(false);
        }
    }
}
?>
