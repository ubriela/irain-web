<?php

/*
 * Geocrowd
 */

class Geocrowd extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('geocrowd_model');
    }
    /**
     * Used to test other functions
     */
    public function index() {
        echo "</br>Test spatiotemporal_query </br>";
        $SW_lat = "34.0197";
        $SW_lng = "-118.2927";
        $NE_lat = "34.219722";
        $NE_lng = "-118.092785";
        $this->spatiotemporal_query($SW_lat, $SW_lng, $NE_lat, $NE_lng);

        echo "</br></br>Test circle_query </br>";
        $lat = "34.0197";
        $lng = "-118.2927";
        $this->circle_query($lat, $lng, 1);
    }

    /**
     * Spatio-temporal query (Rectangle)
     * 
     * @param type $SW_lat
     * @param type $SW_lng
     * @param type $NE_lat
     * @param type $NE_lng
     * @param type $from
     * @param type $to
     */
    // version 1
    function spatiotemporal_query($SW_lat, $SW_lng, $NE_lat, $NE_lng, $from = '1979-01-01 00:00:00', $to = '2015-01-01 00:00:00') {
        $region_str = "POLYGON((" . $SW_lat . ' ' . $SW_lng . "," . $NE_lat . ' ' . $SW_lng . "," . $NE_lat . ' ' . $NE_lng . "," . $SW_lat . ' ' . $NE_lng . "," . $SW_lat . ' ' . $SW_lng . "))";

        $condition = "response_date between '$from' and '$to' and CONTAINS(GeomFromText(\"$region_str\"), GeomFromText(CONCAT('POINT(', x(location), ' ', y(location),')')))";
        $query = $this->db->select('x(location) AS lat, y(location) AS lng, response_date')->from('weather_report')->where($condition)->order_by('response_date')->get();
        echo json_encode($query->result_array());
        //foreach($query->result_array() as $row){
            //echo json_encode($row);
        //}
        
        
    }

    // this version would be slower version 1
    function spatiotemporal_query2($SW_lat, $SW_lng, $NE_lat, $NE_lng, $from = '1979-01-01 00:00:00', $to = '2015-01-01 00:00:00') {

        $condition = "x(location) >= '$SW_lat' and y(location) >= '$SW_lng' and x(location) <= '$NE_lat' and y(location) <= '$NE_lng' and response_date between '$from' and '$to'";
        $query = $this->db->select('id, x(location) AS lat, y(location) AS lng, response_code, response_date')->from('weather_report')->where($condition)->order_by('response_date')->get();

        echo json_encode(array("results" => $query->result()));
    }

    /**
     * Find all data points within a circle
     * 
     * @param type $lat
     * @param type $lng
     * @param type $radius, in metres
     */
    function circle_query($lat, $lng, $radius = 100) {

        $this->db->select("id, x(location) AS lat, y(location) AS lng, response_code, response_date, (6373000 * acos (cos ( radians( '$lat' ) )* cos( radians( x(location) ) )* cos( radians( y(location) ) - radians( '$lng' ) )+ sin ( radians( '$lat' ) )* sin( radians( x(location) ) ))) AS distance");
        $this->db->from('weather_report')->order_by('response_date');
        $query = $this->db->having("distance < ' $radius '")->get();

        //echo json_encode(array("results" => $query->result()));
    }
    private function _json_response($data) {
        $this->output->set_content_type('application/json');
        if ($data) {
            $this->output->set_output(json_encode(array('status' => 'success', "msg" => $data)));
        } else {
            $this->output->set_output(json_encode(array('status' => 'error', "msg" => '0')));
        }
    }
}
