<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

use Swagger\Annotations as SWG;

/**
 * iRain User Class
 *
 * This class provides methods to interact with the user
 *
 * @package
 *
 * @subpackage
 *
 * @category
 *
 * @author
 *
 * @link
 *
 */

// @formatter:off
/**
 * @SWG\Tag(name="user", description="Users Operations")
 */
// @formatter:on
class User extends API_Controller {
	
	/**
	 * Constructor
	 *
	 * Loads user model and libraries. They are available for all methods
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct ();
		
		// Load user model
		$this->load->model ( 'user_model' );
	}
	
	
	// @formatter:off
	/**
	 *  @SWG\Get(
	 *  	path="/user/test_swagger",
	 *  	operationId="test_swagger",
	 *  	summary="Test",
	 *		description="Test",
	 *		tags={"user"},
	 *
	 *     	produces={
	 *         	"application/json",
	 *         	"application/xml",
	 *         	"text/html",
	 *         	"text/xml"
	 *     	}
	 *	)
	 */
	 public function test_swagger_get() {
	 	$this->response ( "hien", 200 );
	}
	
	/**
	 * Registers a new user.
	 * Provides the form to register.
	 *
	 * When form submitted it checks if username or email already exists
	 *
	 * Upon successful registration, user is redirected to login page
	 *
	 * @access public
	 * @return void
	 */
	
	// @formatter:off
	/**
	 * @SWG\Post(
	 * path="/user/register",
	 * operationId="register",
	 * summary="Creates a new user",
	 * description="Registers a new user given the details provided. Username and password",
	 * tags={"user"},
	 *
	 * @SWG\Parameter(
	 * name="username",
	 * description="the username",
	 * in="formData",
	 * required=true,
	 * type="string"
	 * ),
	 * @SWG\Parameter(
	 * name="channelid",
	 * description="the channelid",
	 * in="formData",
	 * required=false,
	 * type="string"
	 * ),
	 * @SWG\Parameter(
	 * name="password",
	 * description="the user's password.",
	 * in="formData",
	 * required=true,
	 * type="string",
	 * format="password"
	 * ),
	 * @SWG\Parameter(
	 * name="repeatpw",
	 * description="the repeated password",
	 * in="formData",
	 * required=true,
	 * type="string",
	 * format="password"
	 * ),
	 *
	 * @SWG\Response(
	 * response=201,
	 * description="User registered successfully"
	 * ),
	 * @SWG\Response(
	 * response=400,
	 * description="Invalid data supplied"
	 * ),
	 * @SWG\Response(
	 * response=409,
	 * description="Failed to create new user"
	 * ),
	 * @SWG\Response(
	 * response=500,
	 * description="Internal server error"
	 * )
	 * )
	 */
	// @formatter:on
	public function register_post() {
		
		// Read POST parameters
		$username = $this->post ( 'username' );
		$channelid = $this->input->post ( 'channelid' );
		$password = $this->post ( 'password' );
		
		// Defined in config/form_validation.php
		if ($this->form_validation->run ( 'api/user/register_post' ) === FALSE) {
			// Log failed validation
			$log_format = 'failed to register user [username=%s], [channelid=%s]. Bad request [error=%s]';
			$log_msg = sprintf ( $log_format, $username, $channelid, $this->form_validation->error_string () );
			log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
			
			// Bad request
			$this->response ( array (
					'status' => FALSE,
					'error' => $this->form_validation->error_string () 
			), 400 );
		}
		
		// query the database
		$success = $this->user_model->create_user ( $username, $password, $channelid );
		
		if ($success == FALSE) {
			// Log failed registration
			$log_format = 'failed to create new user [username=%s]';
			$log_msg = sprintf ( $log_format, $username );
			log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
			
			$this->response ( array (
					'status' => FALSE,
					'error' => 'Failed to create new user' 
			), 409 );
		}
		
		// Log successful registration
		$log_format = 'user registered successfully [username=%s] [channelid=%s]';
		$log_msg = sprintf ( $log_format, $username, $channelid );
		log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
		
		// Send welcome email
		// $this->_send_welcome_email ( $email, $username );
		
		$this->response ( array (
				'status' => TRUE,
				'message' => 'User registered successfully' 
		), 201 );
	}
	
	/**
	 * Login user to the system
	 *
	 * @access public
	 * @param
	 *        	username_email
	 * @param
	 *        	password
	 */
	// @formatter:off
	/**
	 * @SWG\Post(
	 * path="/user/login",
	 * operationId="login",
	 * summary="Login user to the system",
	 * description="Authenticates user",
	 * tags={"user"},
	 *
	 * @SWG\Parameter(
	 * name="username",
	 * description="the username",
	 * in="formData",
	 * required=true,
	 * type="string"
	 * ),
	 * @SWG\Parameter(
	 * name="password",
	 * description="the user's password.",
	 * in="formData",
	 * required=true,
	 * type="string",
	 * format="password"
	 * ),
	 *
	 * @SWG\Response(
	 * response=201,
	 * description="User logged in successfully"
	 * ),
	 * @SWG\Response(
	 * response=400,
	 * description="Invalid data supplied"
	 * ),
	 * @SWG\Response(
	 * response=409,
	 * description="Failed to login"
	 * ),
	 * @SWG\Response(
	 * response=500,
	 * description="Internal server error"
	 * )
	 * )
	 */
	// @formatter:on
	public function login_post() {
		// Read POST parameters
		$username = $this->input->post ( 'username' );
		$password = $this->input->post ( 'password' );
		
		// Defined in config/form_validation.php
		if ($this->form_validation->run ( 'api/user/login_post' ) === FALSE) {
			// Log failed login attempt
			$log_format = 'failed attempt to login [username=%s]. Bad request [error=%s]';
			$log_msg = sprintf ( $log_format, $username, $this->form_validation->error_string () );
			log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
			
			// Bad request
			$this->response ( array (
					'status' => FALSE,
					'error' => $this->form_validation->error_string () 
			), 400 );
		}
		
		// Query the database
		$row = $this->user_model->get_user ( $username );
		
		// User found?
		if ($row == FALSE) {
			// Log failed login attempt
			$log_format = 'failed attempt to login [username=%s]. User not found';
			$log_msg = sprintf ( $log_format, $username );
			log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
			
			// No such user
			$this->response ( array (
					'status' => FALSE,
					'error' => 'Failed to login. Invalid username or password' 
			), 409 );
		}
		
		$userid = $row->userid;
		$username = $row->username;
		$avatar = $row->avatar;
		$fullname = $row->firstname . ' ' . $row->lastname;
		$db_password = $row->password;
		$salt = $row->salt;
		$password = hash ( 'sha512', $password . $salt );
		
		// Password mismatch?
		if ($db_password != $password) {
			// Log failed login attempt
			$log_format = 'failed attempt to login [username=%s], [userid=%s]. Password miscmatch';
			$log_msg = sprintf ( $log_format, $username, $userid );
			log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
			
			// Password is not correct
			$this->response ( array (
					'status' => FALSE,
					'error' => 'Failed to login. Invalid username or password' 
			), 409 );
		}
		
		// SUCCESSFULL LOGIN
		
		// Create a session
		$sess_array = array (
				'userid' => $userid,
				'username' => $username,
				'avatar' => $avatar,
				'fullname' => $fullname,
				'signed_in' => True 
		);
		
		$this->db->set ( 'islogout', 0 );
		$this->db->where ( 'userid', $userid );
		$this->db->update ( 'users' );
		
		// Set session
		$this->session->set_userdata ( $sess_array );
		
		log_message("debug", $username);
		
		// Log user successful login
		$log_format = 'user logged in successfully [username=%s], [userid=%s]';
		$log_msg = sprintf ( $log_format, $username, $userid );
		log_better_message ( 'debug', __FILE__, __METHOD__, __LINE__, $log_msg );
		
		
		$this->response ( array (
				'status' => TRUE,
				'message' => 'User logged in successfully' 
		), 201 );
	}
}
