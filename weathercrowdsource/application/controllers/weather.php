<?php
class Weather extends CI_Controller{
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
        $this->load->database();
        $this->load->model('user_model');
        $this->load->model('weather_model');
        $this->load->library('session');
        $this->load->helper('cookie');
        $this->load->helper('json_response');
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
        $signed = $this->session->userdata('signed_in'); 
        if(!$signed){
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
            $time = $this->input->post('time');
            $this->weather_model->insert_location($userid,$lat,$lng,$time);
            $this->weather_model->insert_weather($userid,$lat,$lng,$code,$time);
            $this->_json_response(TRUE);
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
    /**
     * Callback validation to Check input is number
     * @return	true if $str is number else false
     */
    public function is_number($str){
       if(is_numeric($str)){
            return TRUE;
       }else{
            $this->form_validation->set_message('is_number', 'Please enter number');
            return FALSE;
       }
    }
    /**
     * Callback validation to Check input is number
     * @return	true if $str is number else false
     */
    public function range_value($value){
        if($value>-1 && $value <3){
            return TRUE;
        }else{
            $this->form_validation->set_message('range_value', 'Please enter from 0 to 2');
            return FALSE;
        }
    }
}
?>