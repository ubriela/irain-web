<?php
class Stats_model extends CI_Model{
    /**
     * summary_tasks
     *
     * show the total number of  requested tasks
     * show the total number of completed tasks
     * show the total number of pending tasks
     *show the total number of expired tasks
     *
     * @access	public
     * @return	void
     */
    public function summary_tasks(){
        $data = array();
        $data['num_task_requested'] = $this->get_num_task_requested();
        $data['num_completed_task'] = $this->get_num_complete_tasks();
        $data['num_pending_task'] = $this->get_num_pending_tasks();
        $data['num_expired_tasks'] = $this->get_num_expired_tasks();
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(array('status' => 'success', 'msg' => $data)));
    }
    //get total number task_requested
    private function get_num_task_requested(){
        
        return $this->db->count_all_results('tasks');
    }
    //get total number complete_task
    private function get_num_complete_tasks(){
        $this->db->where('iscompleted','1');
        $this->db->from('tasks');
        return $this->db->count_all_results();
    }
     //get total number pending_tasks
    private function get_num_pending_tasks(){
        $this->db->where('iscompleted','0');
        $this->db->from('tasks');
        return $this->db->count_all_results();
    }
    //get total number expired_tasks
    private function get_num_expired_tasks(){
        $now = date('Y-m-d H:i:s');
        $this->db->where('enddate <',$now);
        $this->db->where('iscompleted','0');
        $this->db->from('tasks');
        return $this->db->count_all_results();
    }
    /**
     * summary_workers
     *
     * show the total number of  workers(user)
     * show the total number of workers online
     * show the total number of workers isactive = 1
     * 
     *
     * @access	public
     * @return	void
     */
    public function summary_workers(){
        $data = array();
        $data['num_workers'] = $this->get_num_workers();
        $data['num_online_workers'] = $this->get_num_online_workers();
        $data['num_available_workers'] = $this->get_num_available();
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(array('status' => 'success','msg' => $data)));
    }
    private function get_num_workers(){
        return $this->db->count_all_results('users');
    }
    private function get_num_online_workers(){
        $this->db->where('islogout','0');
        $this->db->from('users');
        return $this->db->count_all_results();
    }
    private function get_num_available(){
        $this->db->where('isactive','1');
        $this->db->where('isassigned','0');
        $this->db->from('location_report');
        return $this->db->count_all_results();
    }
    /**
     * top_contributions
     *
     * list of contributions
     * 
     *
     * @access	public
     * @return	void
     */
    public function top_contributions($type){
        if($type!=0){
            $select = "select task_worker_matches.userid,users.username,users.email,count(*) as contributions
                from tasks
                left join task_worker_matches on tasks.taskid = task_worker_matches.taskid
                left join users on task_worker_matches.userid = users.userid
                where tasks.iscompleted = 1 and tasks.type=1
                group by task_worker_matches.userid,users.username
                order by contributions desc
               
                ";
        }else{
            $select = "select weather_report.userid,users.username,users.email,count(*) as contributions
                from weather_report
                left join users on  weather_report.userid = users.userid
                group by weather_report.userid
				order by contributions desc
				
                ";
                
        }
        
        $query = $this->db->query($select);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($query->result_array()));
        
    }
    public function summary_geocrowd(){
        $select = "select taskid,task_worker_matches.userid,users.username,users.email,iscompleted,assigned_date,completed_date
            from task_worker_matches
            left join users on task_worker_matches.userid = users.userid
            order by userid";
        $query = $this->db->query($select);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($query->result_array()));
    }
    private function _json_response($data) {
        $this->output->set_content_type('application/json');
        if ($data) {
            $this->output->set_output(json_encode(array('status' => 'success', "msg" => $data)));
        } else {
            $this->output->set_output(json_encode(array('status' => 'error', "msg" => '0')));
        }
    }
}
?>