<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

if (! function_exists ( 'log_better_message' ))
{
	/**
	 * Logs messages using the log_message() method.
	 *
	 * Additionally includes the context for the logging: file, method and line. The output is formatted in JSON
	 *
	 * @access public
	 * @param string $level
	 *        	the error level: 'error', 'debug' or 'info'
	 * @param string $file
	 *        	the full path and filename of the file given by __FILE__
	 * @param string $method
	 *        	the class method name given by __METHOD__
	 * @param int $line
	 *        	the current line number of the file given by __LINE__
	 * @param string $msg
	 *        	the error message
	 * @return void
	 */
	function log_better_message($level, $file, $method, $line, $msg)
	{
		// Enrich the log message
		$better_msg = array (
				"msg" => $msg,
				"file" => $file,
				"method" => $method,
				"line" => $line
		);
		
		// Encode array to json string
		$better_msg_json = json_encode ( $better_msg );
		
		// Write to log
		log_message ( $level, $better_msg_json );
	}
}