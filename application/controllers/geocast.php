<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
class Geocast extends CI_Controller {

	function __construct() {
            parent :: __construct();
            $this->load->helper(array (
                            'form',
                            'url'
            ));
            $this->load->library('form_validation');
	}
	
	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{

            $this->load->view('templates/header');
            $this->load->view('geocast_view');
            $this->load->view('templates/footer.php');
	}
	
	public function tasks() {
		//the following code segment is to load coordinates from txt file
		$file = 'res/tasks.txt';
		
		$handle = @fopen($file, 'r');
		$tasks = array();
		if ($handle) {
                    while (!feof($handle)) {
                        $line = fgets($handle, 4096);
                        $tasks[] = $line;
                    }
                    fclose($handle);
		}
		
//		log_message('error', var_export($tasks, True));
		
		// prepare xml data
		if (!empty($tasks)) {
                    header('Content-type: text/xml');
                    echo "<tasks>";
                    foreach ($tasks as $task) {
                        $item = explode(',', $task);
                        echo "<task>";
                        echo "<lat>" . $item[0] . "</lat>";
                        echo "<lng>" . $item[1] . "</lng>";
                        echo "</task>";
                    }
                    echo "</tasks>";
		}
	}
}