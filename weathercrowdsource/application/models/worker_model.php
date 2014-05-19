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
        $userid = $this->session->userdata('userid');
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
    public function location_report($lat,$lng,$locationtime){
        $id = $this->session->userdata('userid');
        $time = strtotime($locationtime);
        $date = date('Y-m-d H:i:s',$time);
        $loc = "'POINT($lat $lng)'";
        $active = $this->user_model->is_active($id);
        $this->db->set('location', "GeomFromText($loc)",false);
        $this->db->set('date', $date );
        $this->db->set('isactive', $active);
        if($this->is_exits_location($id)){
            $this->db->where('userid',$id);
            $this->db->update('location_report');
        }else{
            $this->db->set('userid',$id);
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
    public function task_response($taskid,$code,$date){
        $userid = $this->session->userdata('userid');
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
                $this->db->set('isassigned','0');
                $this->db->where('userid',$userid);
                $this->db->update('location_report');
                
                $this->db->set('iscompleted','1');
                $this->db->set('completed_date',$date);
                $this->db->where('userid',$userid);
                $this->db->where('taskid',$taskid);
                $this->db->update('task_worker_matches');
            }
           
            return $success;
        }
    }
    
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
    		return $this->get_tasktitle($query_taskid->row()->taskid);
    	}
    	return '-1';
    }
    
    public function get_tasktitle($taskid){
    	$this->db->select('taskid');
    	$this->db->select('title');
    	$this->db->from('tasks');
    	$this->db->where('taskid',$taskid);
    	$query_taskid = $this->db->get();
    	$row = $query_taskid->row();
    	return $row;
    }
    
}
?>