<?php
class Weather_model extends CI_Model{
     /**
     * check the location already exists
     *
     * @access	public
     * @param	string $userid
     * @return	return true if the location already exists,otherwise false
     */
    public function is_exits_location($userid){
        $this->db->where('userid',$userid);
        $query = $this->db->get('location_report');
        if($query->num_rows()>0){
            return true;
        }
        return false;
    }
     /**
     * insert or update a row to table location_report  
     *
     * @access	public
     * @param	string $userid,locationtime
     * @param   number $lat,$lng
     * @return	true if is successfully set
     */
    public function insert_location($userid,$lat,$lng,$locationtime){
        $time = strtotime($locationtime);
        $date = date('Y-m-d H:i:s',$time);
        $loc = "'POINT($lat $lng)'";
        $active = $this->user_model->is_active($userid);
        $this->db->set('location', "GeomFromText($loc)",false);
        $this->db->set('date', $date );
        $this->db->set('isactive', $active);
        if($this->is_exits_location($userid)){
            $this->db->where('userid',$userid);
            $this->db->update('location_report');
        }else{
            $this->db->set('userid',$userid);
            $query = $this->db->insert('location_report');
        }
        return true;
    }
    /**
     * check the weather at the location exists
     *
     * @access	public
     * @param	string $userid
     * @param   number $lat,$lng
     * @return	return true if the weather at location already exists else return false
     */
    public function is_exits_weather($userid,$lat,$lng){
        $loc = "GeomFromText('POINT($lat $lng)')";
        $this->db->where('userid',$userid);
        $this->db->where('location',$loc,false);
        $query = $this->db->get('weather_report');
        if($query->num_rows()>0){
            return true;
        }
        return false;
    }
     /**
     * insert or update a row to table weather_report
     *
     * @access	public
     * @param	string $userid,$responsetime
     * @param   number $lat,@lng,$code
     * @return	true if is successfully set
     */
    public function insert_weather($userid,$lat,$lng,$code,$responsetime){
        $time = strtotime($responsetime);
        $date = date('Y-m-d H:i:s',$time);
        $loc = "'POINT($lat $lng)'";
        $this->db->set('response_code',$code);
        $this->db->set('response_date',$date);
        if($this->is_exits_weather($userid,$lat,$lng)){
            $this->db->where('userid',$userid);
            $this->db->where('location',"GeomFromText($loc)",false);
            $this->db->update('weather_report');
        }else{
            $this->db->set('userid',$userid);
            $this->db->set('location',"GeomFromText($loc)",false);
            $this->db->insert('weather_report');
        }
        return true;
    }
    public function spatiotemporal_query($SW_lat, $SW_lng, $NE_lat, $NE_lng, $from = '1979-01-01 00:00:00', $to = '2015-01-01 00:00:00') {
        $region_str = "POLYGON((" . $SW_lat . ' ' . $SW_lng . "," . $NE_lat . ' ' . $SW_lng . "," . $NE_lat . ' ' . $NE_lng . "," . $SW_lat . ' ' . $NE_lng . "," . $SW_lat . ' ' . $SW_lng . "))";

        $condition = "response_date between '$from' and '$to' and CONTAINS(GeomFromText(\"$region_str\"), GeomFromText(CONCAT('POINT(', x(location), ' ', y(location),')')))";
        $query = $this->db->select('x(location) AS lat, y(location) AS lng, response_date')->from('weather_report')->where($condition)->order_by('response_date')->get();
        $this->_json_response($query);    
    }
    public function spatiotemporal_code_query($code,$SW_lat, $SW_lng, $NE_lat, $NE_lng, $from = '1979-01-01 00:00:00', $to = '2015-01-01 00:00:00') {
        $region_str = "POLYGON((" . $SW_lat . ' ' . $SW_lng . "," . $NE_lat . ' ' . $SW_lng . "," . $NE_lat . ' ' . $NE_lng . "," . $SW_lat . ' ' . $NE_lng . "," . $SW_lat . ' ' . $SW_lng . "))";

        $condition = "response_date between '$from' and '$to' and response_code = '$code' and CONTAINS(GeomFromText(\"$region_str\"), GeomFromText(CONCAT('POINT(', x(location), ' ', y(location),')')))";
        $query = $this->db->select('x(location) AS lat, y(location) AS lng, response_date')->from('weather_report')->where($condition)->order_by('response_date')->get();
        $this->_json_response($query);    
    }
    public function _json_response($data) {
        $this->output->set_content_type('application/json');
        if ($data) {
            $this->output->set_output(json_encode($data->result_array()));
        } else {
            $this->output->set_output(json_encode(array('status' => 'error', "msg" => '0')));
        }
    }
    public function string_to_time($in){
        $time = strtotime($in);
        $date = date('Y-m-d H:i:s',$time);
        return $date;
    }
   

    
}
?>