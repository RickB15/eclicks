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

		$send_list = $this->get_mail_list();
		if( !empty($send_list) ){
			foreach( $send_list as $send ){
				$response = getRelationID(
					bizz_url(),
					$this->_decode($send['host']->host_email),
					$this->_decode($send['attendee']->email),
					$this->_decode($send['host']->api_key)
				);
				$data = json_decode($response, true);
	
				if( isset($data["guest_id"]) && !empty($data["guest_id"]) ){
					//send all mails
					$timeInterval = $send['event']->event_time - time();
					$eventData = array(
						'eventTime' => $timeInterval,
						'guestName' => $send['attendee']->name
					);
					foreach ($notification as $key => $value) {
						switch ($key) {
							case 'email_24':
								//between 23:50 and 24 hour
								if($timeInterval > 85800 && $timeInterval <= 86400){
									sendEmail(
										bizz_url(),
										$data["guest_id"],
										$eventData,
										$this->_decode($send['host']->api_key),
										$value
									);
								}
								break;
							
							case 'email_10':
								//between 0 and 10 min
								if($timeInterval > 0 && $timeInterval <= 600){
									sendEmail(
										bizz_url(),
										$data["guest_id"],
										$eventData,
										$this->_decode($send['host']->api_key),
										$value
									);
								}
								break;
						}
					}
				}
			}
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
        $select = 'date, start_time, status, end_time, attendee_id, event_id';
        $query = $this->_db_select($this->db_table['appointment'], $select);
        foreach ($query->result() as $row) {
			if( strtotime($row->date) > strtotime('now') && $row->status !== 'canceled' && $row->status !== 'deleted' ){
				$info = array();

				$info['event'] = (Object) Array(
					'event_time' => strtotime($row->date.$row->start_time)
				);
				
				$select = 'api_key, email, email_direct, email_24, email_10, email_cancel, sms_direct, sms_24, sms_10, sms_cancel';
				$where = Array($this->db_table['event'].'.event_id' => $row->event_id);
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
				$where = Array('attendee_id' => $row->attendee_id);
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