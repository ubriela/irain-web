<?php
class Convert extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('json_response');
         $this->load->helper('form');
        $this->load->library('form_validation');
    }
    public function is_bool($str){
        if($str == 'true' || $str == 'false')
            return true;
        return false;
    }
     public function is_number($str){
       if(is_numeric($str)){
            return TRUE;
       }else{
            $this->form_validation->set_message('is_number', 'Please enter number');
            return FALSE;
       }
    }
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