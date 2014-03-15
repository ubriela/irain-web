<?php

class Bill_model extends CI_Model {

    public function save_bill($data) {
        $this->db->set('description', $data->description);
//        $this->db->set('date', $data->date);
        $this->db->set('total', $data->total);
        $this->db->set('tip', $data->tip);
        
        $this->db->insert('bills');
    }
}

?>
