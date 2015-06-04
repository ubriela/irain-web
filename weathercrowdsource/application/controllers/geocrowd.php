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
        $this->load->model('geocrowd_model');
        $this->load->helper('text');
        $this->load->library('form_validation');
    }
     
    /**
     * Used to test other functions
     */
    public function index() {
        
    }
    
    /**
     * Assign incompleted tasks to nearby workers
     */
    public function assign_tasks(){
        $now = date("Y-m-d H:i:s");
        //$condition = 'iscompleted=0';
        $this->db->select('taskid,ST_X(tasks.location) AS lat, ST_Y(tasks.location) AS lng,type,radius,place,requesterid');
        $this->db->from('tasks');
        $this->db->where("status = 0 and enddate >= '$now'");
        $query = $this->db->get();
        if($query->num_rows()>0){
            foreach($query->result_array() as $row){
                $taskid = $row['taskid'];
                $lat = $row['lat'];
                $lng = $row['lng'];
                $radius = $row['radius'];
                $type = $row['type'];
                $place = $row['place'];
                $userid = $row['requesterid'];
                $area = 'unknown';
                if($type<0 || $type>3){
                    $type =3;
                }
                if($type<3){
                    
                    $array = explode(',',$place);
                    if(count($array)==3){
                        $area = $array[$type];
                    }else{
                        $area = 'unknown';
                    }
                        
                }
                
                $message = "Please report weather at your location. Thank you!";
                $this->task_query($userid,$taskid,$lat,$lng,$radius,$message,$type,$area);
            }
        }
        //$this->unassign_tasks();
    }
    public function reset(){
        $this->db->set('num_notifi',0);
        $this->db->update('location_report');
    }
    // public function unassign_tasks(){
    //     $now = date("Y-m-d H:i:s");
    //     $sql = "DELETE FROM task_worker_matches WHERE task_worker_matches.taskid IN (SELECT taskid FROM tasks WHERE tasks.status <2 and tasks.enddate <= '$now')";
    //     $this->db->query($sql);
        
        

    // }
    
    
    
    
    /**
     * checks and assign a task to all nearby workers
     * 
     * @param $taskid
     * @param $lat
     * @param $lng
     * @param $radius
     * @param $message
     * @param $type : query type
     * @param $area : address
     * @return array workerid
     */
    public function task_query($userid,$taskid,$lat,$lng,$radius,$message,$type=3,$area='unknown'){
        if($area=='unknown' || $type==3) {
        	$this->circle_query($userid,$taskid,$lat,$lng,$radius,$message);
        }else{
            if($type==0){
                $this->city_query($userid,$taskid,$lat,$lng,$radius,$message,$area);
            }
            if($type==1){
                $this->state_query($userid,$taskid,$lat,$lng,$radius,$message,$area);
            }
            if($type==2){
                $this->country_query($userid,$taskid,$lat,$lng,$radius,$message,$area);
            }
        }
    }
   
    public function checktime($timein){
        $date_server = date("Y-m-d H:i:s");
        $interval  = abs($date_server - $timein);
        $minutes   = round($interval / 60);
        return $minutes; 
    }
   
    private function _json_response($data) {
        $this->output->set_content_type('application/json');
        if ($data) {
            $this->output->set_output(json_encode(array('matched' => 'true', "data" => $data)));
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
    
    public function city_query($userid,$taskid,$lat,$lng,$radius,$message,$place){
        //$arrayAddress = $this->getArrayAddress($lat,$lng);
            //$userid = $this->session->userdata('userid');
        $now = date("Y-m-d H:i:s");
            $condition_city = "isactive = '1' and isassigned = 0 and city = '$place' and num_notifi < 2 and userid != '$userid' and extract(hour from ('$now'::timestamp - date_server))>=6";
            $this->db->select('userid');
            $this->db->from('location_report');
            $this->db->where($condition_city);
            $query = $this->db->get();
            if($query->num_rows()>0){
                 $this->pushtask($query,$taskid,$message);
            }
        
    }
    public function country_query($userid,$taskid,$lat,$lng,$radius,$message,$place){
        //$arrayAddress = $this->getArrayAddress($lat,$lng);
        $now = date("Y-m-d H:i:s");
            //$userid = $this->session->userdata('userid');
            $condition_country = "isactive = '1' and isassigned = 0 and country = '$place' and num_notifi < 2 and userid != '$userid' and extract(hour from ('$now'::timestamp - date_server))>=6";
            $this->db->select('userid');
            $this->db->from('location_report');
            $this->db->where($condition_country);
            $query = $this->db->get();
            if($query->num_rows()>0){
                 $this->pushtask($query,$taskid,$message);
            }
        
    }
    
    public function state_query($userid,$taskid,$lat,$lng,$radius,$message,$place){
            $now = date("Y-m-d H:i:s");
            $condition_state = "isactive = '1' and isassigned = 0 and state = '$place' and num_notifi < 2 and userid != '$userid' and extract(hour from ('$now'::timestamp - date_server))>=6";
            $this->db->select('userid');
            $this->db->from('location_report');
            $this->db->where($condition_state);
            $query = $this->db->get();
            if($query->num_rows()>0){
                 $this->pushtask($query,$taskid,$message);
            }
    }
    public function circle_query($userid,$taskid,$lat,$lng,$radius,$message){
        $point = "'POINT($lat $lng)'";
        $now = date("Y-m-d H:i:s");
        //$condition_radius = "isactive = '1' and isassigned = 0 and ST_Point_Inside_Circle(ST_Point(1,1), $lat, $lng, $radius) and userid != '$userid'";
        $condition_radius = "isactive = '1' and isassigned = 0 and userid != '$userid' and num_notifi < 2 and (6373000 * acos (cos ( radians( '$lat' ) )* cos( radians( ST_X(location_report.location) ) )* cos( radians( ST_Y(location_report.location) ) - radians( '$lng' ) )+ sin ( radians( '$lat' ) )* sin( radians( ST_X(location_report.location) ) ))) < '$radius' and extract(hour from ('$now'::timestamp - date_server))>=6";
        //$condition_radius = "isactive = '1' and isassigned = 0 and ST_intersects(ST_GeometryFromText(ST_AsText(location)), ST_buffer(ST_GeometryFromText($point), $radius))";
        $this->db->select('userid');
        $this->db->from('location_report');
        $this->db->where($condition_radius);
        $query = $this->db->get();
        if($query->num_rows()>0){
            $this->pushtask($query,$taskid,$message);
        }
    }
    
    public function pushtask($query,$taskid,$message){
        $this->db->trans_start();
        $pushObject = new push();
        $now = date("Y-m-d H:i:s");
        foreach($query->result_array() as $row){
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
        $this->db->trans_complete();
        $this->_json_response1($query->result_array());
    }
    public function change(){
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $arr = $this->worker_model->getArrayAddress($lat,$lng);
        $this->_json_response($arr);
    }
    
}
