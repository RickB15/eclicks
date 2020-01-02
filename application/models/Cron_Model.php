<?php
/**
* Cron Model
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
* @author       Rick Blanksma <rickblanksma@gmail.com>
*/
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handles 
 */
class Cron_Model extends MY_Model {
    public function __construct()
	{
        parent::__construct();
		$this->db_table['appointment'] = 'appointment';
		$this->db_table['event'] = 'event';
		$this->db_table['user'] = 'user';
		$this->db_table['notifications'] = 'notifications';
		$this->db_table['attendee'] = 'attendee';
		$this->db_table['auth'] = 'auth';
		$this->db_table['bizz_meta'] = 'bizzmail_meta';
	}

    /**
	 * Handles 
	 * @return 
	 */
	public function send_email()
	{
		//import helpers
		$this->load->helper('curl_helper');

		foreach( $this->get_mail_list() as $send ){
			var_dump($send);
			$response = getRelationID(
				bizz_url(),
				$this->_decode($send['host']->host_email),
				$this->_decode($send['attendee']->email),
				$this->_decode($send['host']->api_key)
			);
			$data = json_decode($response, true);

			var_dump($data);

			// if()
			// sendEmail(
			// 	bizz_url(),
			// 	$data["guest_id"],
			// 	$row["event_time"],
			// 	$row['guest_name'],
			// 	$mailApiKey,
			// 	$mailId10min);
		}
    }

    /**
	 * Handles 
	 * @return 
	 */
	public function send_sms()
	{

	}
	
	private function get_mail_list()
    {
		$data = array();
        $select = 'date, start_time, end_time, attendee_id, event_id';
        $query = $this->_db_select($this->db_table['appointment'], $select);
        foreach ($query->result() as $row) {
			if( strtotime($row->date) > strtotime('now') ){
				$info = array();

				$info['event'] = (Object) Array(
					'event_time' => strtotime($row->date.$row->start_time)
				);
				
				$select = 'api_key, email, email_direct, email_24, email_10, sms_direct, sms_24, sms_10';
				$where = Array($this->db_table['event'].'.id' => $row->event_id);
				$join = array(
					array(
						$this->db_table['user'],
						$this->db_table['user'].'.cs_username = '.$this->db_table['event'].'.user_cs_username'
					), 
					array(
						$this->db_table['notifications'],
						$this->db_table['notifications'].'.notifications_id = '.$this->db_table['user'].'.notifications_id'
					),
					array(
						$this->db_table['bizz_meta'],						
						$this->db_table['bizz_meta'].'.auth_username = '.$this->db_table['user'].'.auth_username'
					)
				);
				$query = $this->_db_select($this->db_table['event'], $select, null, $where, $join);
				foreach ($query->result() as $result) {
					$info['host'] = (Object) Array(
						'host_email' 	=> $result->email,
						'api_key'		=> $result->api_key
					);
					unset($result->email);
					unset($result->api_key);
					$info['notifications'] = $result;
				}

				$select = 'name, email, phone';
				$where = Array('id' => $row->attendee_id);
				$query = $this->_db_select($this->db_table['attendee'], $select, null, $where);
				foreach ($query->result() as $result) {
					$info['attendee'] = $result;
				}
				array_push($data, $info);
			}
		}
		return $data;
    }
}