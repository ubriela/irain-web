<?php

/*
 * Geocrowd
 */
require_once('push.php');

class Geocrowd extends CI_Controller {

    public function __construct(){
        parent::__construct();
        
        $this->load->database();
//        $this->load->library('session');
        $this->load->helper('json_response');
        $this->load->helper('form');
        $this->load->model('worker_model');
        $this->load->model('task_model');
        $this->load->library('form_validation');
    }
     
    /**
     * Used to test other functions
     */
    public function index() {
        
    }
    
    public function assign_tasks(){
        $now = date("Y-m-d H:i:s");
        //$condition = 'iscompleted=0';
        $this->db->select('taskid,x(location) AS lat, y(location) AS lng, radius');
        $this->db->from('tasks');
        $this->db->where("status = 0 and enddate >= '$now'");
        $query = $this->db->get();
        if($query->num_rows()>0){
            foreach($query->result_array() as $row){
                $taskid = $row['taskid'];
                $lat = $row['lat'];
                $lng = $row['lng'];
                $radius = $row['radius'];
                $message = "Please report weather at your location. Thank you!";
                $this->task_query($taskid,$lat,$lng,$radius,$message);
            }
        }
        
    }
    
    
    /**
     * checks data weather existing or not
     * @param $taskid
     * @param $lat
     * @param $lng
     * @param $startdate
     * @param $enddate
     * @param $radius
     * @return true if existing else false
     */
    public function task_matched($taskid,$lat,$lng,$start_date,$end_date,$radius){
        $start = $this->string_to_time($start_date);
        $end = $this->string_to_time($end_date);
        $condition = "response_date between '$start' and '$end' and (6373000 * acos (cos ( radians( '$lat' ) )* cos( radians( x(location) ) )* cos( radians( y(location) ) - radians( '$lng' ) )+ sin ( radians( '$lat' ) )* sin( radians( x(location) ) ))) < '$radius'";
        $this->db->select("response_code as code");
        $this->db->from('weather_report')->order_by('response_date','desc');
        $this->db->where($condition);
        $query = $this->db->get();
        if($query->num_rows()>0){
            $this->_json_response($query->result());
            return true;
        }else{
            return false;
        }
       
    }
    /**
     * checks and assign a task to all workers in nearby area
     * 
     * @param $taskid
     * @param $lat
     * @param $lng
     * @param $radius
     * @return array workerid
     */
    public function task_query($taskid,$lat,$lng,$radius,$message){
        
        $condition = "isactive = 1 and isassigned = 0 and (6373000 * acos (cos ( radians( '$lat' ) )* cos( radians( x(location) ) )* cos( radians( y(location) ) - radians( '$lng' ) )+ sin ( radians( '$lat' ) )* sin( radians( x(location) ) ))) < '$radius'";
        $this->db->select('userid,date_server');
        $this->db->from('location_report');
        $this->db->where($condition);
        $query = $this->db->get();
        if($query->num_rows()>0){
           $this->db->trans_start();
           $pushObject = new push();
           foreach($query->result_array() as $row){
                $last_response = $row['date_server'];
                if($this->checktime($last_response)>5){
                    $now = date("Y-m-d H:i:s");
                    $data = array(
                        'taskid' => $taskid,
                        'userid' => $row['userid'],
                        'assigned_date' => $now
                    );
                    $this->db->insert('task_worker_matches',$data);
                    $this->worker_model->assigned($row['userid']);
                    
                    //notifice user
                    $pushObject->push_to_userid($row['userid'], $message);
                    
                    // update status in tasks table
                    $this->task_model->update_status($taskid, 1);	// assigned
                }
           }
           $this->db->trans_complete();
        $this->_json_response1($query->result_array());
        return true;
        }else{
            return false;
        }
    }
    public function checktime($timein){
        $date_server = date("Y-m-d H:i:s");
        $interval  = abs($date_server - $timein);
        $minutes   = round($interval / 60);
        return $minutes; 
    }
    public function getplace()
    {
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
        $json = @file_get_contents($url);
        $data=json_decode($json);
        $status = $data->status;
        if($status=="OK")
            echo $data->results[0]->formatted_address;
        else
            echo 'Unknow';
    }
    private function _json_response($data) {
        $this->output->set_content_type('application/json');
        if ($data) {
            $this->output->set_output(json_encode(array('matched ' => 'True', "data" => $data)));
        } else {
            $this->output->set_output(json_encode(array('matched' => 'false', "data" => 'none')));
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
     private function _json_response1($data) {
        $this->output->set_content_type('application/json');
        if ($data) {
            $this->output->set_output(json_encode($data));
        } else {
            $this->output->set_output(json_encode(array('matched' => 'false', "data" => 'none')));
        }
    }
    public function string_to_time($in){
        $time = strtotime($in);
        $date = date('Y-m-d H:i:s',$time);
        return $date;
    }
    
    
}
