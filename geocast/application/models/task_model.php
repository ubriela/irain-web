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

    public function history_tasks() {
        $this->db->select('x(Location) AS Lat, y(Location) AS Lng');
        $this->db->from('TASKS');
        $this->db->order_by('StartDate', 'desc');
        $this->db->limit(7);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

}
