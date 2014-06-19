<?php
require_once('convert.php');
class Stats extends Convert{
    public function __construct(){
        parent::__construct();
        
        $this->load->model('stats_model');
    }
    /**
     * Default function executed when [base_url]/index.php/stats
     *
     * @access	public
     * @return	void
     */
    public function index(){
        
    }
    /**
     * summary_tasks
     * function executed when [base_url]/index.php/stats/summary_tasks
     * show the total number of  requested tasks
     * show the total number of completed tasks
     * show the total number of pending tasks
     *show the total number of expired tasks
     *
     * @access	public
     * @return	void
     */
    public function summary_tasks(){
        $this->stats_model->summary_tasks();
    }
    /**
     * summary_workers
     * function executed when [base_url]/index.php/stats/summary_workers
     * show the total number of  workers(user)
     * show the total number of workers online
     * show the total number of workers isactive = 1
     * 
     *
     * @access	public
     * @return	void
     */
     
    public function summary_workers(){
        $this->stats_model->summary_workers();
    }
    public function summary_geocrowd(){
        $this->stats_model->summary_geocrowd();
    }
    /**
     * top_contributions
     * function executed when [base_url]/index.php/stats/top_contributions
     * list of contributions
     * 
     *
     * @access	public
     * @return	void
     */
    public function top_contributions(){
        if(!$this->session->userdata('signed_in')){
            $this->_json_response(false);
        }$type = $_POST['type'];
        if ($this->form_validation->run('top_contributions') == FALSE){
                $this->_json_response(FALSE);
        }else{
            $type = $this->input->post('type');
            $this->stats_model->top_contributions($type);
        }
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
?>