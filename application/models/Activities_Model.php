<?php
/**
* Attendee Model
* 
* 
* 
* PHP Version 7.3.5
* 
* LICENSE: 
*
* @category     Client Schedular
* @package      MY
* @subpackage   Model
* @copyright    -
* @license      -
* @version      0.0.1 
* @link         -
* @since        File available since Release 0.0.0
* @author       Rick Blanskma <rickblanksma@gmail.com>
*/
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handles 
 */
class Activities_Model extends MY_Model {
    public function __construct()
	{
		parent::__construct();

        //database tables
		$this->db_table['appointment'] = 'appointment';
		$this->db_table['event'] = 'event';
        $this->db_table['attendee'] = 'attendee';
	}

    /**
	 * Handles 
	 * @return 
	 */
	public function get_activities(String $cs_username)
	{
		if( !empty($cs_username) ){
			$where = Array('user_cs_username' => $cs_username);
			$join = array(
				array(
					$this->db_table['event'],
					$this->db_table['event'].".id = ".$this->db_table['appointment'].".event_id"
				),
				array(
					$this->db_table['attendee'],
					$this->db_table['attendee'].".id = ".$this->db_table['appointment'].".attendee_id"
				)
			);
			$data = $this->_db_select($this->db_table['appointment'], null, null, $where, $join, true);

			if( !empty($data) ){
				foreach ($data as $key => $appointment) {
					unset($data[$key]->user_cs_username);
					unset($data[$key]->attendee_id);
					unset($data[$key]->event_id);
					unset($data[$key]->id);
					
					foreach ($appointment as $item => $value) {
						switch ($item) {
							case 'start_time':
								$appointment->$item = explode(':', $value)[0] . ':' . explode(':', $value)[1]; //remove seconds
								break;
							case 'end_time':
								$appointment->$item = explode(':', $value)[0] . ':' . explode(':', $value)[1]; //remove seconds
								break;
							case 'duration':
								$appointment->$item = explode(':', $value)[0] . ':' . explode(':', $value)[1]; //remove seconds
								break;
						}
					}
					$data[$key] = $this->_decode($appointment);
				}
				return $data;
			}
		}
		return NULL;
	}
	
	public function get_activity(String $cs_username, $appointment_id)
	{

	}
}