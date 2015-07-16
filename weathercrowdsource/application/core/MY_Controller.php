<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

require APPPATH . '/libraries/REST_Controller.php';

/**
 * MediaQ Controller
 *
 * @package MediaQ
 * @subpackage Controllers
 * @category Core
 * @author Giorgos Constantinou <gconstan@usc.edu>
 * @link http://mediaq.usc.edu/
 *      
 */
class MY_Controller extends CI_Controller {
	
	/**
	 * This is the user id
	 *
	 * @var string|null
	 */
	protected $userid = null;
	
	// -----------------------------------------------------------------------------------------------------------------
	
	/**
	 * This is the http client for requests to the RESTful API
	 *
	 *
	 * @var string|null
	 */
	protected $client = null;
	
	// -----------------------------------------------------------------------------------------------------------------
	
	/**
	 * The data returned to the views
	 *
	 *
	 * @var array
	 */
	protected $data = array ();
	
	// -----------------------------------------------------------------------------------------------------------------
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct ();
		
		if ($this->session->userdata ( 'signed_in' ))
		{
			// Get logged in session
			$this->userid = $this->session->userdata('userid');;
		}
		
		// Get request headers
		$headers = $this->input->request_headers ();
		
		// Initialize client
		$this->client = new GuzzleHttp\Client ( [ 
				'base_url' => base_url (),
				'defaults' => [ 
						'headers' => $headers,
						'timeout' => 60,
						'exceptions' => false 
				] 
		] );
	}
	
	// -----------------------------------------------------------------------------------------------------------------
	
	/**
	 * Returns if user is logged in
	 *
	 * @return boolean
	 */
	public function user_is_logged_in()
	{
		return (! empty ( $this->userid ));
	}
	
	// -----------------------------------------------------------------------------------------------------------------
	
	/**
	 * Redirect to last page
	 *
	 */
	public function redirect_to_last_page()
	{
		// Redirect to last page
		if ($this->session->has_userdata ( 'last_page' ))
		{
			$last_page = $this->session->last_page;
			$this->session->unset_userdata ( 'last_page' );
			redirect ( $last_page );
		}
	}
}

// -----------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------

/**
 * MediaQ Authorized Controller
 *
 * @package MediaQ
 * @subpackage Controllers
 * @category Core
 * @author Giorgos Constantinou <gconstan@usc.edu>
 * @link http://mediaq.usc.edu/
 *      
 */
class Authorized_Controller extends MY_Controller {
	
	// -----------------------------------------------------------------------------------------------------------------
	
	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct ();
		
		// Check user is logged in
		if (! $this->user_is_logged_in ())
		{
			$this->session->set_userdata ( 'last_page', current_url () );
			redirect ( '/login' );
		}
		else
		{
			$this->redirect_to_last_page();
		}
		
		
		// All views need these data
		$this->data ['userid'] = $this->session->userdata('userid');;
		$this->data ['username'] = $this->session->userdata('username');
		
		// Close the session for the current request after no longer need it
		// http://www.codeigniter.com/user_guide/libraries/sessions.html#a-note-about-concurrency
		session_write_close ();
	}
	
	// -----------------------------------------------------------------------------------------------------------------
}

// -----------------------------------------------------------------------------------------------------------------
// -----------------------------------------------------------------------------------------------------------------

/**
 * MediaQ API Controller
 *
 * @package MediaQ
 * @subpackage Controllers
 * @category Core
 * @author Giorgos Constantinou <gconstan@usc.edu>
 * @link http://mediaq.usc.edu/
 *      
 */
class API_Controller extends REST_Controller {
	
	/**
	 * This is the user id
	 *
	 *
	 * @var string|null
	 */
	protected $userid = null;
	
	// -----------------------------------------------------------------------------------------------------------------
	public function __construct()
	{
		parent::__construct ();
		
		// Configure limits on our controller methods. Ensure
		// you have created the 'limits' table and enabled 'limits'
		// within application/config/rest.php
		// $this->methods ['user_get'] ['limit'] = 500; // 500 requests per hour per user/key
		// $this->methods ['user_post'] ['limit'] = 100; // 100 requests per hour per user/key
		// $this->methods ['user_delete'] ['limit'] = 50; // 50 requests per hour per user/key
		
		$session_data = $this->session->userdata();

		if (! empty ( $session_data ))
		{
			$this->userid = $session_data ['userid'];
		}
		
		// Load form validation library
		$this->load->library ( 'form_validation' );
		
		// No error delimiters, error_string won't contain <p>
		$this->form_validation->set_error_delimiters ( '', '' );
	}
	
	// -----------------------------------------------------------------------------------------------------------------
	
	/**
	 * Returns if user is logged in
	 *
	 * @return boolean
	 */
	public function user_is_logged_in()
	{
		return (! empty ( $this->userid ));
	}
}