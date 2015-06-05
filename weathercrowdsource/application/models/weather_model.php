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
    public function insert_location($userid,$lat,$lng){
        $loc = "'POINT($lat $lng)'";
        $active = $this->user_model->is_active($userid);
        $this->db->set('location', "ST_GeomFromText($loc, 4326)",false);
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
		$loc = "'POINT($lat $lng)'";
        $location = "ST_GeomFromText($loc, 4326)";
        $this->db->where('userid',$userid);
        $this->db->where('location',$location,false);
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
    public function insert_weather($userid,$lat,$lng,$code,$level,$responsetime,$address){
        $time = strtotime($responsetime);
        $response_date = date('Y-m-d H:i:s',$time);
        $server_date = date("Y-m-d H:i:s");
        $loc = "'POINT($lat $lng)'";
        $address = removesign($address);
        $this->db->set('taskid',0);
        $this->db->set('worker_place',$address);
        $this->db->set('response_code',$code);
        $this->db->set('level',$level);
        $this->db->set('response_date',$response_date);
        $this->db->set('response_date_server',$server_date);
        $this->db->set('worker_location',"ST_GeomFromText($loc, 4326)",false);
        $this->db->set('workerid',$userid);
        $this->db->insert('responses');
    }
    public function spatiotemporal_query($SW_lat, $SW_lng, $NE_lat, $NE_lng, $from = '1979-01-01 00:00:00', $to = '2015-01-01 00:00:00') {
    	log_message('info', "spatiotemporal_query: \t" . $SW_lat. "\t" . $SW_lng . "\t" . $NE_lat. "\t". $NE_lng. "\t". $from . "\t" . $to);
        //$region_str = "'POLYGON((" . $SW_lat . ' ' . $SW_lng . "," . $NE_lat . ' ' . $SW_lng . "," . $NE_lat . ' ' . $NE_lng . "," . $SW_lat . ' ' . $NE_lng . "," . $SW_lat . ' ' . $SW_lng . "))'";
        $start = $this->string_to_time($from);
        $end = $this -> string_to_time($to);
        $condition = "response_date between '$start' and '$end'";
		$this->db->select('ST_X(worker_location) AS lat, ST_Y(worker_location) AS lng,response_code,level,worker_place, response_date');
		$this->db->limit(100);
		//$this->db->distinct('worker_location');
		$this->db->from('responses');  
		$this->db->where($condition);
		//$this->db->order_by('response_date');
		$query = $this->db->get();
		log_message('info', "spatiotemporal_query: \t" . var_export($query->result_array(), True));
		$this->_json_response($query->result_array());
// 		$this->_json_response(FALSE);
    }
    public function spatiotemporal_code_query($code,$SW_lat, $SW_lng, $NE_lat, $NE_lng, $from = '1979-01-01 00:00:00', $to = '2015-01-01 00:00:00') {
        $region_str = "POLYGON((" . $SW_lat . ' ' . $SW_lng . "," . $NE_lat . ' ' . $SW_lng . "," . $NE_lat . ' ' . $NE_lng . "," . $SW_lat . ' ' . $NE_lng . "," . $SW_lat . ' ' . $SW_lng . "))";
        $start = $this->string_to_time($from);
        $end = $this -> string_to_time($to);
        if($start>$end){
            $this->_json_response(false);
        }else{
            $condition = "response_date between '$from' and '$to' and response_code = '$code' and CONTAINS(ST_GeomFromText($region_str), ST_GeomFromText(CONCAT('POINT(', ST_X(responses.worker_location), ' ', ST_X(responses.worker_location),')')))";
            $this->db->distinct();
            $query = $this->db->select('ST_X(responses.worker_location) AS lat, ST_Y(responses.worker_location) AS lng,response_code, response_date')->distinct('worker_location')->from('responses')->where($condition)->order_by('responses.response_date','desc')->get();
            $this->_json_response($query);  
        }
    }
    
    public function _json_response($data) {
        $this->output->set_content_type('application/json');
        if ($data) {
            $this->output->set_output(json_encode($data));
        } else {
            $this->output->set_output(json_encode(array('status' => 'error', "msg" => '0')));
        }
    }
    public function string_to_time($in){
        $time = strtotime($in);
        $date = date('Y-m-d H:i:s',$time);
        return $date;
    }
   
    public function getaddress1($lat,$lng)
    {
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=true';
        $json = @file_get_contents($url);
        $data=json_decode($json);
        $status = $data->status;
        if($status=='OK'){
            $number = count($data->results);
            return $data->results[$number-3]->formatted_address;
        }
        else
            return false;
    }
    
}
?>
