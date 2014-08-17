<?php
class Requester_model extends CI_Model{
    /**
     * user send request to server
     *
     * @access	public
     * @param	string $title,$request_date,$start_date,$end_date
     * @param   number $lat,$lng,$type,$radius
     * @return	TRUE if is successfully set
     */
    public function task_request($userid,$title,$lat,$lng,$request_date,$start_date,$end_date,$type=1,$radius,$place=''){
        //$userid = $this->session->userdata('userid');
        $loc = "'POINT($lat $lng)'";
        $location = "GeomFromText($loc)";
        $requestdate = $this->string_to_time($request_date);
        $startdate = $this->string_to_time($start_date);
        $enddate = $this->string_to_time($end_date);
        if($startdate >= $enddate)
            return false;
        $success = TRUE;
        $this->db->set('requesterid',$userid);
        $this->db->set('title',$title);
        $this->db->set('location', "GeomFromText($loc)",false);
        $this->db->set('place',$place);
        $this->db->set('request_date',$requestdate);
        $this->db->set('startdate',$startdate);
        $this->db->set('enddate',$enddate);
        $this->db->set('type',$type);
        $this->db->set('radius',$radius);
        //$query = $this->db->insert('tasks');
        $this->db->trans_start();
        if (!$this->db->insert('tasks'))
            $success = FALSE;
        $this->db->trans_complete();
        return $success;
    }
    
    public function get_taskid($userid){
         $this->db->select('taskid');
         $this->db->from('tasks');
         $this->db->where('requesterid',$userid);
         $this->db->order_by('taskid','desc');
         $this->db->limit('1');
         $query_taskid = $this->db->get();
         $row = $query_taskid->row();
         return $row->taskid;
    }
    public function string_to_time($in){
        $time = strtotime($in);
        $date = date('Y-m-d H:i:s',$time);
        return $date;
    }
    
    public function submitted_task($number){
        if(isset($_POST['offset'])){
            $start = $_POST['offset'];
        }else{
            $start = 0;
        }
        $id = $this->session->userdata('userid');
        $this->db->select('taskid,title, x(location) AS lat, y(location) AS lng,request_date,startdate,enddate, iscompleted');
        $this->db->from('tasks');
        $this->db->where("requesterid = '$id'");
        $this->db->order_by('iscompleted desc','taskid desc');
        $this->db->limit($number,$start);
        $query = $this->db->get();
        if($query->num_rows()>0){
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(array('status' => 'success', "msg" => $query->result_array())));
        } else {
            $this->output->set_output(json_encode(array('status' => 'error', "msg" => 'No task')));
        }
       
    }
    public function submitted_task_type(){
        $type = $_POST['type'];
        $time = strtotime($_POST['time']);
        $date = date('Y-m-d H:i:s',$time);
        $id = $this->session->userdata('userid');
        $this->db->select('taskid,title, x(location) AS lat, y(location) AS lng,place,request_date,startdate,enddate, iscompleted');
        $this->db->from('tasks');
        $this->db->where("requesterid = '$id'");
        if($type==0){
           $this->db->where("iscompleted = 0 and enddate >= '$date'");
        }
        if($type==1){
            $this->db->where('iscompleted',1);
        }
        if($type==2){
            $this->db->where("iscompleted = 0 and enddate < '$date'");
        }
        $this->db->order_by('taskid','desc');
        $query = $this->db->get();
        if($query->num_rows()>0){
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(array('status' => 'success', "msg" => $query->result_array())));
        } else {
            $this->output->set_output(json_encode(array('status' => 'error', "msg" => 'No task')));
        }
       
    }
     /**
     * get requesterid from taskid
     * @param type $taskid
     */
    public function requesterid_from_taskid($taskid) {
        $this->db->select('requesterid');
        $this->db->from('tasks');
        $this->db->where('taskid', $taskid);
        $this->db->limit(1);
        
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            // Return the row
            return $query->row();
        } else {
            return FALSE;
        }
    }
    public function list_pending_task(){
        $tablehead = '<thead><tr><th>Location</th><th>Request date</th><th>Radius</th></tr></thead>';
        $id = $this->session->userdata('userid');
        $timezone = $_POST['timezone'];
        $time = strtotime($_POST['time']);
        $date = date('Y-m-d H:i:s',$time);
        $this->db->select('taskid,place,request_date,radius');
        $this->db->from('tasks');
        $this->db->where("requesterid = '$id'");
        $this->db->where("iscompleted = 0 and enddate > '$date'");
        $this->db->order_by('request_date desc');
        $query = $this->db->get();
        if($query->num_rows()>0){
            $tbody = '';
            foreach($query->result() as $rows){
                $local = $this->convert_time_zone($rows->request_date,'UTC',$timezone);
                $cellLocation = '<td>'.$rows->place.'</td>';
                $cellRequestdate = '<td class=center>'.$local.'</td>';
                $cellRadius = '<td class=center>'.$rows->radius.'<td>';
                $row = '<tr>'.$cellLocation.$cellRequestdate.$cellRadius.'</tr>';
                $tbody.=$row;
            }
            $table =$tablehead.'<tbody>'.$tbody.'</tbody>';
            echo $table;
        }else{
            $table = $tablehead.'<tbody><tr><td class=center colspan="3">No task</td></tr></tbody>';
            echo $table;
        }
    }
    public function list_completed_task(){
        $tablehead = '<thead><tr><th>Location</th><th>Response date</th><th>Response</th></tr></thead>';
        $id = $this->session->userdata('userid');
        $timezone = $_POST['timezone'];
        $select = "select place,response_date,response_code,level from `tasks`,`responses` where iscompleted=1 and tasks.taskid=responses.taskid and tasks.requesterid='$id' order by response_date desc";
        $query = $this->db->query($select);
        if($query->num_rows()>0){
            $tbody = '';
            foreach($query->result() as $rows){
                $local = $this->convert_time_zone($rows->response_date,'UTC',$timezone);
                $result = $this->getresult($rows->response_code,$rows->level);
                $cellLocation = '<td>'.$rows->place.'</td>';
                $cellResponsedate = '<td class=center>'.$local.'</td>';
                $cellResponse = '<td class=center>'.$result.'<td>';
                $row = '<tr>'.$cellLocation.$cellResponsedate.$cellResponse.'</tr>';
                $tbody.=$row;
            }
            $table =$tablehead.'<tbody>'.$tbody.'</tbody>';
            echo $table;
        }else{
            $table = $tablehead.'<tbody><tr><td class=center colspan="3">No task</td></tr></tbody>';
            echo $table;
        }
    }
    public function list_expired_task(){
        $tablehead = '<thead><tr><th>Location</th><th>Request date</th></tr></thead>';
        $id = $this->session->userdata('userid');
        $timezone = $_POST['timezone'];
        $time = strtotime($_POST['time']);
        $date = date('Y-m-d H:i:s',$time);
        $this->db->select('place,request_date');
        $this->db->from('tasks');
        $this->db->where("iscompleted = 0 and enddate <= '$date'");
        $this->db->order_by('taskid','desc');
        $query = $this->db->get();
        if($query->num_rows()>0){
            $tbody = '';
            foreach($query->result() as $rows){
                $local = $this->convert_time_zone($rows->request_date,'UTC',$timezone);
                $cellLocation = '<td>'.$rows->place.'</td>';
                $cellRequestdate = '<td class=center>'.$local.'</td>';
                $row = '<tr>'.$cellLocation.$cellRequestdate.'</tr>';
                $tbody.=$row;
            }
            $table =$tablehead.'<tbody>'.$tbody.'</tbody>';
            echo $table;
        }else{
            $table = $tablehead.'<tbody><tr><td class=center colspan="2">No task</td></tr></tbody>';
            echo $table;
        }
    }
    private function getresult($code,$level){
        if($code==0){
            return 'none';
        }
        if($code==1){
            switch($level){
                case 0:return 'rain(light)';
                case 1:return 'rain(moderate)';
                case 2:return 'rain(heavy)';
            }
        }
        if($code==2){
            switch($level){
                case 0:return 'snow(light)';
                case 1:return 'snow(moderate)';
                case 2:return 'snow(heavy)';
            }
        }  
    }
    public function delete_completed(){
        $id = $this->session->userdata('userid');
        $this->db->where('iscompleted',1);
        $this->db->where('requesterid',$id);
        $this->db->delete('tasks'); 
    }
    public function delete_expired(){
        $id = $this->session->userdata('userid');
        $time = strtotime($_POST['time']);
        $date = date('Y-m-d H:i:s',$time);
        $this->db->where("iscompleted = 0 and enddate <= '$date'");
        $this->db->delete('tasks');
    }
    function convert_time_zone($date_time, $from_tz, $to_tz)
	{
    	$time_object = new DateTime($date_time, new DateTimeZone($from_tz));
    	$time_object->setTimezone(new DateTimeZone($to_tz));
    	return $time_object->format('Y-m-d H:i:s');
	}
    
}
?>