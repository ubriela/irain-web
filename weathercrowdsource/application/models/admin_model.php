<?php
class Admin_model extends CI_Model{
    public function listUsers(){
        if($this->session->userdata('type') && $this->session->userdata('type')==1){
            $offset = $this->input->post('offset');
            $this->db->order_by('created_date','desc');
            $this->db->limit(15,$offset);
            $query = $this->db->get('users');
            return $query;
        }else{
            return false;
        }
    }
    public function getUserInfo($userid){
        if($this->session->userdata('type') && $this->session->userdata('type')==1){
            $this->db->where('userid',$userid);
            $query = $this->db->get('users');
            return $query;
        }else{
            return false;
        }
    }
    public function getUserNumTasks($userid){
        if($this->session->userdata('type') && $this->session->userdata('type')==1){
            $this->db->from('tasks');
            $this->db->where('requesterid',$userid);
            $query = $this->db->get();
            return $query->num_rows();
        }
    }
    public function getUserNumResponses($userid){
        if($this->session->userdata('type') && $this->session->userdata('type')==1){
            $this->db->from('responses');
            $this->db->where('workerid',$userid);
            $query = $this->db->get();
            return $query->num_rows();
        }
    }
    public function deleteUser($userid){
        if($this->session->userdata('type') && $this->session->userdata('type')==1){
            $this->db->where('userid',$userid);
            $this->db->delete('users');
            return $this->db->affected_rows();
        }
    }
    public function getNumUsers(){
        if($this->session->userdata('type') && $this->session->userdata('type')==1){
            $query = $this->db->count_all_results('users');
            if($query!=0){
                    return ($query/15);
            }else{
                return 0;
            }
            
        }
    }
    public function getNumTasks(){
        if($this->session->userdata('type') && $this->session->userdata('type')==1){
            $query = $this->db->count_all_results('tasks');
           if($query!=0){
                    return ($query/15);
            }else{
                return 0;
            }
            
        }
    }
    public function getNumResponses(){
        if($this->session->userdata('type') && $this->session->userdata('type')==1){
            $query = $this->db->count_all_results('responses');
            if($query!=0){
                    return ($query/15);
            }else{
                return 0;
            }
            
            
        }
    }
    public function listTasks(){
        if($this->session->userdata('type') && $this->session->userdata('type')==1){
            $offset = $this->input->post('offset');
            $this->db->order_by('request_date','desc');
            $this->db->limit(15,$offset);
            $query = $this->db->get('tasks');
            return $query;
        }
    }
    public function listResponses(){
        if($this->session->userdata('type') && $this->session->userdata('type')==1){
            $offset = $this->input->post('offset');
            $this->db->select('id,worker_place,ST_X(worker_location) AS lat, ST_Y(worker_location) AS lng,response_code,level,response_date_server');
            $this->db->order_by('response_date_server','desc');
            
            $this->db->limit(15,$offset);
            $query = $this->db->get('responses');
            return $query;
        }
    }
    public function deleteResponse(){
        if($this->session->userdata('type') && $this->session->userdata('type')==1){
            $id = $this->input->post('id');
            
            $this->db->where('id',$id);
            $this->db->delete('responses');
            if($this->db->affected_rows()!=0){
                return true;
            }else{
                return false;
            }
            
        }
    }
}
?>