<?php
class Weather_model extends CI_Model{
    //check 
    public function is_exits_location($userid){
        $this->db->where('workerid',$userid);
        $query = $this->db->get('location_report');
        if($query->num_rows()>0){
            return true;
        }
        return false;
    }
    public function insert_location($id,$lat,$lng,$locationtime){
        $time = strtotime($locationtime);
        $date = date('Y-m-d H:i:s',$time);
        $loc = "'POINT($lat $lng)'";
        $active = $this->user_model->is_active($id);
        $this->db->set('location', "GeomFromText($loc)",false);
        $this->db->set('date', $date );
        $this->db->set('isactive', $active);
        if($this->is_exits_location($id)){
            $this->db->where('workerid',$id);
            $this->db->update('location_report');
        }else{
            $this->db->set('workerid',$id);
            $query = $this->db->insert('location_report');
        }
        return true;
    }
    public function is_exits_weather($userid,$lat,$lng){
        $loc = "GeomFromText('POINT($lat $lng)')";
        $this->db->where('userid',$userid);
        $this->db->where('location',$loc,false);
        $query = $this->db->get('weather_location');
        if($query->num_rows()>0){
            return true;
        }
        return false;
    }
    public function insert_weather($id,$lat,$lng,$code,$responsetime){
        $time = strtotime($responsetime);
        $date = date('Y-m-d H:i:s',$time);
        $loc = "'POINT($lat $lng)'";
        $this->db->set('response_code',$code);
        $this->db->set('response_date',$date);
        if($this->is_exits_weather($id,$lat,$lng)){
            $this->db->where('userid',$id);
            $this->db->where('location',"GeomFromText($loc)",false);
            $this->db->update('weather_location');
        }else{
            $this->db->set('userid',$id);
            $this->db->set('location',"GeomFromText($loc)",false);
            $this->db->insert('weather_location');
        }
        return true;
    }
    
   

    
}
?>