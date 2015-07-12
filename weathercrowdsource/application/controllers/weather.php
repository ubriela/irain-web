<?php
require_once('convert.php');
class Weather extends Convert{
    /**
     * Constructor
     *
     * Loads user model and libraries. They are available for all methods
     *
     * @access	public
     * @return	void
     */
    public function __construct(){
        parent::__construct();
        
        $this->load->model('user_model');
        $this->load->model('weather_model');
        $this->load->model('worker_model');
        $this->load->helper('text');
        
       
    }
    /**
     * Default function executed when [base_url]/index.php/weather
     *
     * @access	public
     * @return	void
     */
    public function index(){
        $this->load->helper('form');
        $this->load->library('form_validation');
        if(!$this->session->userdata('signed_in')){
             $this->_json_response(FALSE);
             return;
        }
        if ($this->form_validation->run('report_location_weather') == FALSE){
            $this->_json_response(FALSE);
        }else{
            $userid = $this->session->userdata('userid');
            $lat = $this->input->post('lat');
            $lng = $this->input->post('lng');
            $code = $this->input->post('code');
            $level = $this->input->post('level');
            $time = $this->input->post('time');
            $address = $this->input->post('address');
            $this->worker_model->location_report($userid,$lat,$lng,$address);
            $this->weather_model->insert_weather($userid,$lat,$lng,$code,$level,$time,$address);
            $this->_json_response(TRUE);
        }
            
    }
     public function rectangle_report(){
     	log_message('info', 'rectangle_report');
        if ($this->form_validation->run('rectangle_report') == FALSE){
                $this->_json_response(FALSE);
        }else{
            $SW_lat = $this->input->post('swlat');//"34.0197";
            $SW_lng = $this->input->post('swlng');//"-118.2927";
            $NE_lat = $this->input->post('nelat');//"34.219722";
            $NE_lng = $this->input->post('nelng');//"-118.092785";
            $from = $this->input->post('startdate');
            $to = $this->input->post('enddate');
            
            $type = $this->input->post('type');
            if($type==null){
                $type = -1;
            }
            log_message('info', "rectangle_report: \t" . $SW_lat. "\t" . $SW_lng . "\t" . $NE_lat. "\t". $NE_lng. "\t". $from . "\t" . $to);
            $this->weather_model->spatiotemporal_query($SW_lat,$SW_lng,$NE_lat,$NE_lng,$from,$to,$type);
        } 
    }  
    public function rectangle_report_code(){
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(false);
        }
        if ($this->form_validation->run('rectangle_report_code') == FALSE){
                $this->_json_response(FALSE);
        }else{
            $Code = $this->input->post('code');
            $SW_lat = $this->input->post('swlat');//"34.0197";
            $SW_lng = $this->input->post('swlng');//"-118.2927";
            $NE_lat = $this->input->post('nelat');//"34.219722";
            $NE_lng = $this->input->post('nelng');//"-118.092785";
            $from = $this->input->post('startdate');
            $to = $this->input->post('enddate');
            $this->weather_model->spatiotemporal_code_query($Code,$SW_lat,$SW_lng,$NE_lat,$NE_lng,$from,$to);
        } 
    }
    private function _json_response($data) {
        $this->output->set_content_type('application/json');
        if ($data) {
            $this->output->set_output(json_encode(array('status' => 'success', "msg" => 'success')));
        } else {
            $this->output->set_output(json_encode(array('status' => 'error', "msg" => '0')));
        }
    }
    public function getlagtime(){
        $homepage = file_get_contents('http://persiann.eng.uci.edu/htdocs/gwadi/data/ascii/current_datetime');
        $array = explode('-',$homepage);
        echo $array[3];
    }
   
   
     
}
?>