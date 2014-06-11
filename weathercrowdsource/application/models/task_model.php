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
    public function update_tatus($taskid, $status = 0) {
    	$this->db->set('status', $status);
    	$this->db->where('taskid', $taskid);
    	$this->db->update('tasks');
    }
}
