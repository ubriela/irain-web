<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_User_agent extends CI_User_agent {
	var $mobile_apps	= array();
	var $is_mobile_app	= FALSE;
	var $mobile_app		= '';
	
    public function __construct()
    {
        parent::__construct();
                
        if ( ! is_null($this->agent))
        {
        	if ($this->_load_mobile_apps_agent_file())
        	{
        		 $this->_set_mobile_app();
        	}
        }
    }
    
    // --------------------------------------------------------------------
	
    
    /**
     * Load mobile apps agent array
     *
     * @access	private
     * @return	bool
     */
    private function _load_mobile_apps_agent_file()
    {
    	if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/user_agents.php'))
    	{
    		include(APPPATH.'config/'.ENVIRONMENT.'/user_agents.php');
    	}
    	elseif (is_file(APPPATH.'config/user_agents.php'))
    	{
    		include(APPPATH.'config/user_agents.php');
    	}
    	else
    	{
    		return FALSE;
    	}
    
    	$return = FALSE;
    
    	
    	if (isset($mobile_apps))
    	{
    		$this->mobile_apps = $mobile_apps;
    		unset($mobile_apps);
    		$return = TRUE;
    	}
    
    
    	return $return;
    }
    
    // --------------------------------------------------------------------
    
    
	/**
	 * Set the Mobile App
	 *
	 * @access	private
	 * @return	bool
	 */
	private function _set_mobile_app()
	{
		if (is_array($this->mobile_apps) AND count($this->mobile_apps) > 0)
		{
			foreach ($this->mobile_apps as $key => $val)
			{
				if (FALSE !== (strpos(strtolower($this->agent), $key)))
				{
					$this->is_mobile_app = TRUE;
					$this->mobile_app = $val;
					return TRUE;
				}
			}
		}
		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	
    /**
     * Is Mobile App
     *
     * @access	public
     * @return	bool
     */
    public function is_mobile_app($key = NULL)
    {
    	if ( ! $this->is_mobile_app)
    	{
    		return FALSE;
    	}
    
    	// No need to be specific, it's a mobile
    	if ($key === NULL)
    	{
    		return TRUE;
    	}
    
    	// Check for a specific mobile
    	return array_key_exists($key, $this->mobile_apps) AND $this->mobile_app === $this->mobile_apps[$key];
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Get the Mobile App
     *
     * @access	public
     * @return	string
     */
    public function mobile_app()
    {
    	return $this->mobile_app;
    }
}