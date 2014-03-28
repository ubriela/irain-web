<?php

class Bill_model extends CI_Model {

    public function save_bill($data, $session) {
        $success = True;
        $userid = $session['userid'];
        $this->db->set('creatorid', $userid);
        $this->db->set('description', $data->description);      
        //$this->db->set('date', date('d/m/Y',strtotime($data->date)) );
        $this->db->set('total', $data->total);
        $this->db->set('tip', $data->tip);
        
        if (!$this->db->insert('bills'))
            $success = False;
        return $success;
    }
}

?>