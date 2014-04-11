<?php
class Weather_model extends CI_Model{
    public function report_weather(){
        
    }
    public function insert_location($lat,$lng,$code){
        $userid = $this->session->userdata('userid');
        $success;
        if($userid!=''){
            $location = "'POINT($lat $lng)'";
            $date_now = date("Y-m-d H:i:s");
            $active =  $this->user_model->is_active($userid);
            $taskid = gen_uuid();
            $this->db->set('workerid', $userid);
            $this->db->set('location', "GeomFromText($location)", false);
            $this->db->set('date', $date_now);
            $this->db->set('isactive', $active);
            $this->db->insert('location_report');
            if($this->db->affected_rows()>0){
                $data_response = array(
                'taskid' => $taskid,
                'workerid' => $userid,
                'response_code' => $code,
                'response_date' => $date_now,
                'upload_date' => $date_now
                );
                $this->db->insert('responses',$data_response);
                $success = 1;
            }
        }
        $this->_json_response($success);
    }
    private function _json_response($data) {

        $this->output->set_content_type('application/json');

        if (!empty($data)) {
            $this->output->set_output(json_encode(array('status' => 'success', "msg" => $data)));
        } else {
            $this->output->set_output(json_encode(array('status' => 'error', "msg" => '0')));
        }
    }

    
}
?>