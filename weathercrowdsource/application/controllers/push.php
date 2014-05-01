<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Push Class
 *
 * This class provides methods to interact with Parse Push services
 *
 * @package
 * @subpackage
 * @category
 * @Duc M le
 * @link
 */
	
class push extends CI_Controller {
	
	/**
	 * Constructor
	 *
	 * Loads user model and libraries. They are available for all methods
	 *
	 * @access	public
	 * @return	void
	 */
	
	public function __construct() {
		parent::__construct();
	
		$this->load->database();
	
		// Load user model
		$this->load->model('user_model');
		$this->load->helper('cookie');
	
		// Load user agent library
		$this->load->helper('json_response');
	}
	
	/**
	 * Default function executed when [base_url]/index.php/push is requested
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		$this->push_to_group();
	}
	
	/**
	 * function executed when [base_url]/index.php/push/get_channel_id is requested
	 * get channel id
	 * @access	public
	 * @return	void
	 * @param
	 */
	public function get_channel_id()
	{
		$this->push_to_group();
	}
	
	/**
	 * function executed when [base_url]/index.php/push/push_to_group is requested
	 * send push notification to a list of user
	 * @access	public
	 * @return	void
	 */
	
	public function push_to_group(){
		// Global informatin
		$url = "https://api.parse.com/1/push";
		$appId = 'UovV9FjabAWcEoNDSWRjGZl9L4ZQGdklPtqwuP3m';
		$restKey = 'gzoEcDslLHdNqu2cTLG4bIIoSm7FkYVXxBtTZJYh';
		
		if (isset($_POST['message'])) {
			$message = $_POST['message'];
			$channelId = $_POST['channelid'];
		} else
			return False;
		
		$push_payload = json_encode(array(
				"channels" =>	
// 				array("$in"=>$channelId)
						[
						$channelId         
						]
				,
				"data" => array(
						"alert" => $message
				)
		));
		
		$rest = curl_init();
		curl_setopt($rest,CURLOPT_URL,$url);
		curl_setopt($rest,CURLOPT_PORT,443);
		curl_setopt($rest, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($rest,CURLOPT_POST,1);
		curl_setopt($rest,CURLOPT_POSTFIELDS,$push_payload);
		curl_setopt($rest, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($rest,CURLOPT_HTTPHEADER,
		array(
		"X-Parse-Application-Id:". $appId ,
		"X-Parse-REST-API-Key: " . $restKey,
		"Content-Type: application/json"));
		$response = curl_exec($rest);
		$responseCode = curl_getinfo($rest, CURLINFO_HTTP_CODE);
		print curl_error($rest);
// 		$this->_json_response($message);
		echo $push_payload;
		echo $response;
		echo $responseCode;
// 		$this->_json_response($response);
		curl_close($rest);
	}
	
	
	/**
	 * Sends the data in JSON format
	 *
	 * Used when the respond is for mobile application or AJAX requests
	 *
	 * @access	private
	 * @param	object	$data contains the object to be sent as JSON
	 * @return	void
	 */
	private function _json_response($data) {
	
		$this->output->set_content_type('application/json');
	
		if (!empty($data)) {
			$this->output->set_output(json_encode(array('status' => 'success', "msg" => $data)));
		} else {
			$this->output->set_output(json_encode(array('status' => 'error', "msg" => '0')));
		}
	}

}