<?php
class Worker_model extends CI_Model {
	
	public function removesign($str) {
		return $str;
	}
	
	/**
	 * set isassigned is 0
	 *
	 * @access public
	 * @return TRUE if is successfully set
	 */
	public function unassigned($userid) {
		$this->db->set ( 'isassigned', '0' );
		$this->db->where ( 'userid', $userid );
		$this->db->update ( 'location_report' );
		return $this->db->affected_rows ();
	}
	public function assigned($userid) {
		$this->db->set ( 'isassigned', '1' );
		$this->db->where ( 'userid', $userid );
		$this->db->update ( 'location_report' );
		$this->db->set ( 'num_notifi', 'num_notifi+1', FALSE );
		$this->db->where ( 'userid', $userid );
		$this->db->update ( 'location_report' );
		return TRUE;
	}
	/**
	 * check the location already exists
	 *
	 * @access public
	 * @param string $userid        	
	 * @return return true if the location already exists,otherwise false
	 */
	public function is_exits_location($userid) {
		$this->db->where ( 'userid', $userid );
		$query = $this->db->get ( 'location_report' );
		if ($query->num_rows () > 0) {
			return true;
		}
		return false;
	}
	/**
	 * insert or update a row to table location_report
	 *
	 * @access public
	 * @param
	 *        	string locationtime
	 * @param
	 *        	number @lat,@lng
	 * @return void
	 */
	public function location_report($userid, $lat, $lng, $address, $type = 0) {
		$date_server = date ( "Y-m-d H:i:s" );
		$loc = "'POINT($lat $lng)'";
		$arrayAdress = $this->getArrayAddress ( $lat, $lng );
		$this->db->trans_start();
		$this->db->set ( 'location', "ST_GeomFromText($loc, 4326)", false );
		$this->db->set ( 'city', trim ( $arrayAdress [0] ) );
		$this->db->set ( 'state', trim ( $arrayAdress [1] ) );
		$this->db->set ( 'country', trim ( $arrayAdress [2] ) );
		if ($type == 1) {
			$this->db->set ( 'date_server', $date_server );
		}
		$this->db->set ( 'address', $address );
		if ($this->is_exits_location ( $userid )) {
			$this->db->where ( 'userid', $userid );
			$this->db->update ( 'location_report' );
		} else {
			$active = $this->user_model->is_active ( $userid );
			$this->db->set ( 'date_server', $date_server );
			$this->db->set ( 'isactive', $active );
			$this->db->set ( 'userid', $userid );
			$query = $this->db->insert ( 'location_report' );
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	/**
	 * set user's active: 0->1,1->0
	 *
	 * @access public
	 * @return true if is successfully set
	 */
	public function set_isactive($active) {
		$userid = $this->session->userdata ( 'userid' );
		
		if ($active == 'false' || $active == 'FALSE') {
			$active_int = 0;
		} else {
			$active_int = 1;
		}
		
		$this->db->trans_start ();
		$this->db->set ( 'isactive', $active_int );
		$this->db->where ( 'userid', $userid );
		$this->db->update ( 'location_report' );
		$this->db->set ( 'isactive', $active_int );
		$this->db->where ( 'userid', $userid );
		$this->db->update ( 'users' );
		$this->db->trans_complete ();
		
		return $this->db->trans_status ();
	}
	/**
	 * response a task from user
	 *
	 * @access public
	 * @param string $taskid        	
	 * @param string $code        	
	 * @param string $date        	
	 * @return true if is successfully set
	 */
	public function task_response($taskid, $userid, $code, $level, $date, $lat, $lng, $address) {
		// Transaction
		$this->db->trans_start ();
		
		$this->db->where ( 'taskid', $taskid );
		$this->db->where ( 'workerid', $userid );
		$this->db->delete ( 'responses' );
		$time = strtotime ( $date );
		$date = date ( 'Y-m-d H:i:s', $time );
		$date_now = date ( "Y-m-d H:i:s" );
		$worker_location = "'POINT($lat $lng)'";
		$this->db->set ( 'worker_location', "ST_GeomFromText($worker_location, 4326)", false );
		$this->db->set ( 'worker_place', $address );
		$this->db->set ( 'taskid', $taskid );
		$this->db->set ( 'workerid', $userid );
		$this->db->set ( 'response_code', $code );
		$this->db->set ( 'level', $level );
		$this->db->set ( 'response_date', $date );
		$this->db->set ( 'response_date_server', $date_now );

		if ($this->db->insert ( 'responses' )) {
			$this->db->set ( 'iscompleted', '1' );
			$this->db->set ( 'completed_date', $date );
			$this->db->where ( 'userid', $userid );
			$this->db->where ( 'taskid', $taskid );
			$this->db->update ( 'task_worker_matches' );
			
			// Set completion in Tasks table
			$this->db->set ( 'iscompleted', '1' );
			$this->db->where ( 'taskid', $taskid );
			$this->db->update ( 'tasks' );
			
			$this->db->where ( "enddate >= '$date'" );
			$this->db->where ( 'taskid', $taskid );
			$query = $this->db->get ( 'tasks' );
		}
		
		$this->db->trans_complete ();
		
		if ($this->db->trans_status ()) {
			if ($query->num_rows () > 0) {
				return 2;
			} else {
				return 1;
			}
		}
		
		return 0;
	}
	
	/**
	 * get task info for a worker based on his id
	 *
	 * @access public
	 * @return taskid, title, startdate, enddate
	 */
	public function get_taskid() {
		$userid = $this->session->userdata ( 'userid' );
		$this->db->select ( 'taskid' );
		$this->db->from ( 'task_worker_matches' );
		$this->db->where ( 'userid', $userid );
		$this->db->where ( 'iscompleted', 0 );
		$this->db->order_by ( 'assigned_date', 'desc' );
		$this->db->limit ( '1' );
		$query_taskid = $this->db->get ();
		if ($query_taskid->num_rows () > 0) {
			return $this->get_taskinfo ( $query_taskid->row ()->taskid );
		}
		
		return array (
				'taskid' => '-1' 
		);
	}
	public function get_taskinfo($taskid) {
		$this->db->select ( 'taskid' );
		$this->db->select ( 'startdate' );
		$this->db->select ( 'place' );
		$this->db->select ( 'enddate' );
		$this->db->select ( 'ST_X(location) AS lat' );
		$this->db->select ( 'ST_Y(location) AS lng' );
		$this->db->from ( 'tasks' );
		$this->db->where ( 'taskid', $taskid );
		$query_taskid = $this->db->get ();
		$row = $query_taskid->row ();
		$place = '';
		$arr = explode ( ',', $row->place );
		if ($arr [0] != 'unknown') {
			$place .= $arr [0] . ', ';
		}
		if ($arr [1] != 'unknown') {
			$place .= $arr [1] . ', ';
		}
		if ($arr [2] != 'unknown') {
			$place .= $arr [2];
		}
		$row->place = $place;
		return $row;
	}
	public function gettask($userid) {
		// $userid = $this->session->userdata('userid');
		$this->db->select ( 'taskid' );
		$this->db->from ( 'task_worker_matches' );
		$this->db->where ( 'userid', $userid );
		$this->db->where ( 'iscompleted', 0 );
		$query_taskid = $this->db->get ();
		if ($query_taskid->num_rows () > 0) {
			return $this->get_taskinfo ( $query_taskid->row ()->taskid );
		} else {
			return false;
		}
	}
	public function getArrayAddress($lat, $lng) {
		$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . trim ( $lat ) . ',' . trim ( $lng ) . '&sensor=false&language=en';
		$json = @file_get_contents ( $url );
		$data = json_decode ( $json );
		$status = $data->status;
		$arr = array (
				'unknown',
				'unknown',
				'unknown' 
		);
		$ad2 = false;
		$ad1 = false;
		$locality = false;
		$street_address = false;
		$route = false;
		if ($status == 'OK') {
			$k = count ( $data->results );
			for($i = 0; $i < $k; $i ++) {
				if ($data->results [$i]->types [0] == 'administrative_area_level_2') {
					$ad2 = $this->removesign ( $data->results [$i]->formatted_address );
				}
				if ($data->results [$i]->types [0] == 'locality') {
					$locality = $this->removesign ( $data->results [$i]->formatted_address );
				}
				
				if ($data->results [$i]->types [0] == 'street_address') {
					$street_address = $this->removesign ( $data->results [$i]->formatted_address );
				}
				if ($data->results [$i]->types [0] == 'route') {
					$route = $this->removesign ( $data->results [$i]->formatted_address );
				}
				if ($data->results [$i]->types [0] == 'administrative_area_level_1') {
					$ad1 = $this->removesign ( $data->results [$i]->formatted_address );
				}
			}
		}
		if ($ad2) {
			$address = explode ( ",", $ad2 );
			$num = count ( $address );
			if (isset ( $address [$num - 1] )) {
				$arr [2] = str_replace ( '\'', '', trim ( $address [$num - 1] ) );
			}
			if (isset ( $address [$num - 2] )) {
				$arr [1] = str_replace ( '\'', '', trim ( $address [$num - 2] ) );
			}
			if (isset ( $address [$num - 3] )) {
				$arr [0] = str_replace ( '\'', '', trim ( $address [$num - 3] ) );
			}
		} elseif ($locality) {
			$address = explode ( ",", $locality );
			
			$num = count ( $address );
			if (isset ( $address [$num - 1] )) {
				$arr [2] = str_replace ( '\'', '', trim ( $address [$num - 1] ) );
			}
			if (isset ( $address [$num - 2] )) {
				$arr [1] = str_replace ( '\'', '', trim ( $address [$num - 2] ) );
			}
			if (isset ( $address [$num - 3] )) {
				$arr [0] = str_replace ( '\'', '', trim ( $address [$num - 3] ) );
			}
		} elseif ($street_address) {
			$address = explode ( ",", $street_address );
			$num = count ( $address );
			if (isset ( $address [$num - 1] )) {
				$arr [2] = str_replace ( '\'', '', trim ( $address [$num - 1] ) );
			}
			if (isset ( $address [$num - 2] )) {
				$arr [1] = str_replace ( '\'', '', trim ( $address [$num - 2] ) );
			}
			if (isset ( $address [$num - 3] )) {
				$arr [0] = str_replace ( '\'', '', trim ( $address [$num - 3] ) );
			}
		} elseif ($ad1) {
			$address = explode ( ",", $ad1 );
			$num = count ( $address );
			if (isset ( $address [$num - 1] )) {
				$arr [2] = str_replace ( '\'', '', trim ( $address [$num - 1] ) );
			}
			if (isset ( $address [$num - 2] )) {
				$arr [1] = str_replace ( '\'', '', trim ( $address [$num - 2] ) );
			}
			if (isset ( $address [$num - 3] )) {
				$arr [0] = str_replace ( '\'', '', trim ( $address [$num - 3] ) );
			}
		}
		return $arr;
	}
}
?>
