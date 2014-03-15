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

    public function task_request($taskid, $lat, $lng, $status = 0) {
        $loc = "'POINT($lat $lng)'";
        $this->db->set('TaskId', $taskid);
        $this->db->set('Loc', "GeomFromText($loc)", false);
        $this->db->set('Status', $status, false);
        $this->db->insert('pri_tasks');
    }

}
