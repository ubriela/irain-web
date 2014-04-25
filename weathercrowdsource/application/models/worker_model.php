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
        $this->db->where('taskid',$taskid);
        $this->db->where('userid',$userid);
        $check = $this->db->get('responses');
        if($check->num_rows()>0){
            return false;
        }else{
            $time = strtotime($date);
            $date = date('Y-m-d H:i:s',$time);
            $this->db->set('taskid',$taskid);
            $this->db->set('userid',$userid);
            $this->db->set('response_code',$code);
            $this->db->set('response_date',$date);
            $query = $this->db->insert('responses');
            $this->db->set('isassigned','0');
            $this->db->where('userid',$userid);
            $this->db->update('location_report');
            return true;
        }
    }
}
?>