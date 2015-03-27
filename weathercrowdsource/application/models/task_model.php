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
    
    
    public function get_similartask($lat,$lng){
        $now = date("Y-m-d H:i:s");
        $condition = "enddate >= '$now' and status = 0 and (6373000 * acos (cos ( radians( '$lat' ) )* cos( radians( ST_X(tasks.location) ) )* cos( radians( ST_Y(tasks.location) ) - radians( '$lng' ) )+ sin ( radians( '$lat' ) )* sin( radians( ST_X(tasks.location) ) ))) < radius";
        $this->db->select('taskid');
        $this->db->from('tasks');
        $this->db->where($condition);
        $query = $this->db->get();
        return $query;
    }
    public function matched($taskid,$workerid){
        $now = date("Y-m-d H:i:s");
        $this->db->set('taskid',$taskid);
        $this->db->set('userid',$workerid);
        $this->db->set('assigned_date',$now);
        $this->db->insert('task_worker_matches');
    }
   
    
}
