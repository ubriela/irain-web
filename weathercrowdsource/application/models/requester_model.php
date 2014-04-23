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
    public function task_request($title,$lat,$lng,$request_date,$start_date,$end_date,$type=1,$radius){
        $userid = $this->session->userdata('userid');
        $loc = "'POINT($lat $lng)'";
        $requestdate = $this->string_to_time($request_date);
        $startdate = $this->string_to_time($start_date);
        $enddate = $this->string_to_time($end_date);
        $this->db->set('requesterid',$userid);
        $this->db->set('title',$title);
        $this->db->set('location', "GeomFromText($loc)",false);
        $this->db->set('request_date',$requestdate);
        $this->db->set('startdate',$startdate);
        $this->db->set('enddate',$enddate);
        $this->db->set('type',$type);
        $query = $this->db->insert('tasks');
        if($query){
            $this->db->select('taskid');
            $this->db->from('tasks');
            $this->db->where('requesterid',$userid);
            $this->db->order_by('taskid','desc');
            $this->db->limit('1');
            $query_taskid = $this->db->get();
            foreach($query_taskid->result() as $row){
                return $row->taskid;
            }
        }else{
            return false;
        }
    }
    public function string_to_time($in){
        $time = strtotime($in);
        $date = date('Y-m-d H:i:s',$time);
        return $date;
    }
}
?>