<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of task_model
 *
 * @author ubriela
 */
class Task_model extends CI_Model {

    /**
     * Update status of a task
     * 
     * Initialized status = 0
     * Pending status = 1 (i.e., assigned to some workers)
     * Completed status = 2
     * 
     * @param unknown $taskid
     * @param number $status
     */
    public function update_status($taskid, $status = 0) {
        $this->db->set('status', $status);
        $this->db->where('taskid', $taskid);
        $this->db->update('tasks');
    }

    /**
     * Finds and returns the task details based on taskid
     *
     * @access	public
     * @param	string taskid
     * @return	the user details
     */
    public function get_userinfo() {

        $taskid = $this->session->userdata('taskid');

        $this->db->select('requesterid, title, x(location) AS lat, y(location) AS lng, request_date,startdate,enddate, iscompleted, status, ');
        $this->db->from('users');
        $this->db->where('taskid', $taskid);
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            // Return the row
            $task_info = $query->result_array()[0];
            
            // Find task response
//            $this->db->select('workerid, x(worker_location) AS lat, y(worker_location) AS lng, response_code, level, response_date');
//            $this->db->from('responses');
//            $this->db->where('taskid', $taskid);
//            $this->db->limit(1);
//            $query = $this->db->get();
//            if ($query->num_rows() >= 1) {
//                $taskresponse = $query->result_array();
//                $this->output->set_content_type('application/json');
//                $this->output->set_output(json_encode(array('status' => 'success', "msg" => array('info' => $task_info,'responses' => $taskresponse))));
//            }
            
            $this->output->set_output(json_encode(array('status' => 'success', "msg" => array('info' => $task_info,'responses' => NULL))));
        } else {
            $this->output->set_output(json_encode(array('status' => 'error', "msg" => 'No task')));
        }
    }

}
