<?php
class Worker_model extends CI_Model{
    /**
     * set isassigned is 0
     *
     * @access	public
     * @return	TRUE if is successfully set
     */
    public function unassigned(){
        if($this->session->userdata('signed_in')){
            $userid = $this->session->userdata('userid');
            $this->db->set('isassigned','0');
            $this->db->where('userid',$userid);
            $this->db->update('location_report');
            return TRUE;
        }else{
            return FALSE;
        };
    }
    public function assigned($userid){
            $this->db->set('isassigned','1');
            $this->db->where('userid',$userid);
            $this->db->update('location_report');
            return TRUE;
    }
      /**
     * check the location already exists
     *
     * @access	public
     * @param	string $userid
     * @return	return true if the location already exists,otherwise false
     */
    public function is_exits_location($userid){
        $this->db->where('userid',$userid);
        $query = $this->db->get('location_report');
        if($query->num_rows()>0){
            return true;
        }
        return false;
    }
    /**
     * insert or update a row to table location_report  
     *
     * @access	public
     * @param	string locationtime
     * @param   number @lat,@lng
     * @return  void
     */
    public function location_report($userid,$lat,$lng,$locationtime){
        
        $time = strtotime($locationtime);
        $date = date('Y-m-d H:i:s',$time);
        $date_server = date("Y-m-d H:i:s");
        $loc = "'POINT($lat $lng)'";
        $active = $this->user_model->is_active($userid);
        $this->db->set('location', "GeomFromText($loc)",false);
        $this->db->set('date', $date );
        $this->db->set('date_server',$date_server);
        $this->db->set('isactive', $active);
        if($this->is_exits_location($userid)){
            $this->db->where('userid',$userid);
            $this->db->update('location_report');
        }else{
            $this->db->set('userid',$userid);
            $query = $this->db->insert('location_report');
        }
    }
    /**
     * set user's active: 0->1,1->0
     *
     * @access	public
     * @return	true if is successfully set
     */
    public function set_isactive($active){
            $id = $this->session->userdata('userid');
            if($active=='false'){
                $this->db->set('isactive',0);
                $this->db->where('userid',$id);
                $this->db->update('location_report');
                $this->db->set('isactive',0);
                $this->db->where('userid',$id);
                $this->db->update('users');
            }else{
                $this->db->set('isactive',1);
                $this->db->where('userid',$id);
                $this->db->update('location_report');
                $this->db->set('isactive',1);
                $this->db->where('userid',$id);
                $this->db->update('users');
            }
            
    }
    /**
     * response a task from user
     *
     * @access	public
     * @param   string $taskid
     * @param   string $code
     * @param   string $date
     * @return	true if is successfully set
     */
    public function task_response($taskid,$userid,$code,$level,$date){
        $this->db->from('responses');
        $this->db->where('taskid',$taskid);
        $this->db->where('workerid',$userid);
        $check = $this->db->get();
        if($check->num_rows()>0){
            return false;
        }else{
            $time = strtotime($date);
            $date = date('Y-m-d H:i:s',$time);
            $date_now = date("Y-m-d H:i:s");
            
            $success = TRUE;
            $response_data = array(
                'taskid' => $taskid,
                'workerid' => $userid,
                'response_code' => $code,
                'level' => $level,
                'response_date' => $date,
                'response_date_server' => $date_now
            );
            // Transaction
            $this->db->trans_start();
            if (!$this->db->insert('responses', $response_data))
                $success = FALSE;
            $this->db->trans_complete();
            
            // Update related table
            if($success){
            	// Set user free to received new task
                $this->db->set('isassigned','0');
                $this->db->where('userid',$userid);
                $this->db->update('location_report');
                
                // Set completion in Task_worker_matches table
                $this->db->set('iscompleted','1');
                $this->db->set('completed_date',$date);
                $this->db->where('userid',$userid);
                $this->db->where('taskid',$taskid);
                $this->db->update('task_worker_matches');
                
		// Set completion in Tasks table                
                $this->db->set('iscompleted','1');
                $this->db->where('taskid',$taskid);
                $this->db->update('tasks');
            }
           
            return $success;
        }
    }
    
    /**
     * Update worker location
     * @param type $taskid
     * @param type $lat
     * @param type $lng
     * @return boolean
     */
    public function update_worker_location($userid,$taskid,$lat,$lng){
        //$userid = $this->session->userdata('userid');
        $place = $this->getaddress($lat,$lng);
        $loc = "'POINT($lat $lng)'";
        $this->db->where('taskid',$taskid);
        $this->db->where('workerid',$userid);
        $this->db->set('worker_location', "GeomFromText($loc)",false);
        $this->db->set('worker_place',$place);
        if (!$this->db->update('responses'))
            return FALSE;
            
        return TRUE;
    }
    public function update_worker_location_web($userid,$taskid,$time){
        //$userid = $this->session->userdata('userid');
        $select = "select x(location) as lat,y(location) as lng from location_report where userid='$userid'";
        $query = $this->db->query($select);
        $lat = $query->row()->lat;
        $lng = $query->row()->lng;
        $this->location_report($userid,$lat,$lng,$time);
        $this->update_worker_location($userid,$taskid,$lat,$lng);
    }
    /**
     * get task info for a worker based on his id
     *
     * @access	public
     * @return	taskid, title, startdate, enddate
     */
    
    public function get_taskid(){
    	$userid = $this->session->userdata('userid');
    	$this->db->select('taskid');
    	$this->db->from('task_worker_matches');
    	$this->db->where('userid',$userid);
    	$this->db->where('iscompleted',0);
    	$this->db->order_by('assigned_date','desc');
    	$this->db->limit('1');
    	$query_taskid = $this->db->get();
    	if ($query_taskid->num_rows()>0){
    		return $this->get_taskinfo($query_taskid->row()->taskid);
    	}
    	$response_data = array(
    			'taskid' => '-1'
    	);
    	return $response_data;
    }
    
    public function get_taskinfo($taskid){
    	$this->db->select('taskid');
    	$this->db->select('title');
    	$this->db->select('startdate');
        $this->db->select('place');
    	$this->db->select('enddate');
        $this->db->select('x(location) AS lat');
        $this->db->select('y(location) AS lng');
    	$this->db->from('tasks');
    	$this->db->where('taskid',$taskid);
    	$query_taskid = $this->db->get();
    	$row = $query_taskid->row();
    	return $row;
    }
    
    public function gettask(){
        $userid = $this->session->userdata('userid');
    	$this->db->select('taskid');
    	$this->db->from('task_worker_matches');
    	$this->db->where('userid',$userid);
    	$this->db->where('iscompleted',0);
    	$this->db->order_by('assigned_date','desc');
    	$this->db->limit('1');
    	$query_taskid = $this->db->get();
    	if ($query_taskid->num_rows()>0){
    		return  $this->get_taskinfo($query_taskid->row()->taskid);
    	}else{
    	   return false;
    	}
    }
    
    public function getaddress($lat,$lng)
    {
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
        $json = @file_get_contents($url);
        $data=json_decode($json);
        $status = $data->status;
        if($status=="OK")
            return $data->results[0]->formatted_address;
        else
            return 'Unknow';
    }
    
    
    
}
?>