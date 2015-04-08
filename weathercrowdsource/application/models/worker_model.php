<?php
class Worker_model extends CI_Model{
    /**
     * set isassigned is 0
     *
     * @access	public
     * @return	TRUE if is successfully set
     */
    public function unassigned($userid){
            $this->db->set('isassigned','0');
            $this->db->where('userid',$userid);
            $this->db->update('location_report');
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
    public function location_report($userid,$lat,$lng,$address,$type=0){
        $date_server = date("Y-m-d H:i:s");
        $loc = "'POINT($lat $lng)'";
        $arrayAdress = $this->getArrayAddress($lat,$lng);
        $this->db->set('location', "ST_GeomFromText($loc, 4326)",false);
        $this->db->set('city',trim($arrayAdress[0]));
        $this->db->set('state',trim($arrayAdress[1]));
        $this->db->set('country',trim($arrayAdress[2]));
        if($type==1){
            $this->db->set('date_server',$date_server);    
        }
        $this->db->set('address',$address);
        if($this->is_exits_location($userid)){
            $this->db->where('userid',$userid);
            $this->db->update('location_report');
        }else{
            $active = $this->user_model->is_active($userid);
            $this->db->set('date_server',$date_server);
            $this->db->set('isactive', $active);
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
    public function task_response($taskid,$userid,$code,$level,$date,$lat,$lng,$address){
        $this->db->from('responses');
        $this->db->where('taskid',$taskid);
        $this->db->where('workerid',$userid);
        $check = $this->db->get();
        if($check->num_rows()>0){
            return 0;
        }else{
            $time = strtotime($date);
            $date = date('Y-m-d H:i:s',$time);
            $date_now = date("Y-m-d H:i:s");
            $worker_location = "'POINT($lat $lng)'";
            $this->db->set('worker_location', "ST_GeomFromText($worker_location, 4326)",false);
            $this->db->set('worker_place',$address);
            $this->db->set('taskid',$taskid);
            $this->db->set('workerid',$userid);
            $this->db->set('response_code',$code);
            $this->db->set('level',$level);
            $this->db->set('response_date',$date);
            $this->db->set('response_date_server',$date_now);
            // Transaction
           
            if ($this->db->insert('responses')){
                $this->db->trans_start();
                $this->db->set('iscompleted','1');
                $this->db->set('completed_date',$date);
                $this->db->where('userid',$userid);
                $this->db->where('taskid',$taskid);
                $this->db->update('task_worker_matches');
                
        // Set completion in Tasks table                
                $this->db->set('iscompleted','1');
                $this->db->where('taskid',$taskid);
                $this->db->update('tasks');
                
                $this->db->where("enddate >= '$date'");
                $this->db->where('taskid',$taskid);
                $query = $this->db->get('tasks');
                if($query->num_rows()>0){
                    return 2;
                }else{
                    return 1;
                }
                $this->db->trans_complete();
            }else{
                return 0;
            }

        }
        return 0;
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
    	$this->db->select('startdate');
        $this->db->select('place');
    	$this->db->select('enddate');
        $this->db->select('ST_X(location) AS lat');
        $this->db->select('ST_Y(location) AS lng');
    	$this->db->from('tasks');
    	$this->db->where('taskid',$taskid);
    	$query_taskid = $this->db->get();
    	$row = $query_taskid->row();
    	return $row;
    }
    
    public function gettask($userid){
        //$userid = $this->session->userdata('userid');
    	$this->db->select('taskid');
    	$this->db->from('task_worker_matches');
    	$this->db->where('userid',$userid);
    	$this->db->where('iscompleted',0);
    	$query_taskid = $this->db->get();
    	if ($query_taskid->num_rows()>0){
    		return  $this->get_taskinfo($query_taskid->row()->taskid);
    	}else{
    	   return false;
    	}
    }
          



    
    
    public function getArrayAddress($lat,$lng){
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false&language=en';
        $json = @file_get_contents($url);
        $data=json_decode($json);
        $status = $data->status;
        $arr = array('unknown','unknown','unknown');
        $lv1 = false;
        $lv2 = false;
        $lv3 = false;
        if($status=='OK'){
            $k = count($data->results);
            for($i=0;$i<$k;$i++){
                if($data->results[$i]->types[0]=='administrative_area_level_2'){
                    $lv1 = removesign($data->results[$i]->formatted_address);             
                }
                if($data->results[$i]->types[0]=='locality'){
                    $lv2 = removesign($data->results[$i]->formatted_address);
                }
                
                if($data->results[$i]->types[0]=='street_address'){
                    $lv3 = removesign($data->results[$i]->formatted_address);
                }
            }
        }
        if($lv1){
            $address = explode(",",$lv1);
            $arr[0] = str_replace('\'', '', trim($address[0]));
            $arr[1] = str_replace('\'', '', trim($address[1]));
            $arr[2] = str_replace('\'', '', trim($address[2]));
        }elseif($lv2){
            $address = explode(",",$lv2);
            $arr[0] = str_replace('\'', '', trim($address[0]));
            $arr[1] = str_replace('\'', '', trim($address[1]));
            $arr[2] = str_replace('\'', '', trim($address[2]));
        }elseif($lv3){
            $address = explode(",",$lv3);
            $num = count($address);
            $arr[0] = str_replace('\'', '', trim($address[$num-3]));
            $arr[1] = str_replace('\'', '', trim($address[$num-2]));
            $arr[2] = str_replace('\'', '', trim($address[$num-1]));
        }
        return $arr;
    }
    
}
?>
