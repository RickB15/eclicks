<?php
/**
* Appointments
* 
* Controller for the Appointments page (index page) for Client Schedular.
* 
* PHP Version 7.3.5
* 
* LICENSE: 
*
* @category     Client Schedular
* @package      MY
* @subpackage   Controller
* @copyright    -
* @license      -
* @version      0.0.1 
* @link         http://localhost:8383/{user}
* @since        File available since Release 0.0.0
* @author       Rick Blanskma <rickblanksma@gmail.com>
*/
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * !Client Schedular application!
 * Handles the appointment page » attendee can make an appointment based on the settings from an user
 * *index page for an attendee of Client Schedualr
 */
class Appointment extends MY_Controller {
	public function __construct()
	{
		parent::__construct();

		//change page view path
		$this->application['appName'] = 'Client Schedular';

		//default page info
		$this->pageName = strToLower(get_class($this)).'_planner';
		$this->pageAccess = 'public';

	}

	/**
	 * Handles page info and database data to render a calendar.index view
	 * @link {user}
	 * @return render
	 */
	public function index($username=NULL, $event_title=NULL)	
	{
		if( !empty($username) ){
			//import helpers and models
			$this->load->model('Appointment_Model');
			if( $this->Appointment_Model->check_user($username) ){
				//set page info
				$pageInfo['pageName'] = $this->pageName;
				$pageInfo['segment'] = strtolower(__FUNCTION__);

				if( empty($event_title) ){
					//get data from database
					$pageData['events'] = $this->Appointment_Model->get_events($username);
					$pageData['cs_username'] = $username;

					if( !empty($pageData['events']) ){
						$this->event_list($pageData);
					} else {
						
						//TODO better error handling
						$this->appointment_error('Username not correct or user has no events');
						//TODO error, username is not correct or user has no events
					}
				} else {
					//get data from database
					$pageData['event'] = $this->Appointment_Model->get_event($username, preg_replace('~[-_]~', ' ', ucfirst(strtolower($event_title))));

					if( !empty($pageData['event']) ){
						$pageInfo['cs_username'] = $username;
						$pageInfo['cs_duration'] = $pageData['event']->duration;
						$pageInfo['cs_title'] = $pageData['event']->title;

						//set custom js, css and/or font files
						$js = Array('appointment_calendar.js' => 'end', 'lib/vanilla-calendar-master/vanillaCalendar.js' => 'end');
						$css = array('lib/vanilla-calendar-master/vanillaCalendar.css');
						//// $fonts = array();
				
						//import custom js, css and/or font files
						$this->files['js'] = $js;
						$this->files['css'] = $css;
						//// $this->files['fonts'] = $fonts;

						////sort
						
						//render
						$this->_render($pageInfo, $pageData);
					} else {
						$this->appointment_error('Title of the event is not correct. Use only a-zA-Z characters and - or _ as spaces');
					}
				}
			} else {
				//TODO better error handling
				$this->appointment_error('Username is not correct');
			}
		} else {
			//TODO better error handling
			$this->appointment_error('Username is empty');
		}
	}

	public function appointment_error($error=NULL)
	{
		if( !empty($error) && preg_match("/[^a-zA-Z0-9]+/", $error) == 1 ){
			////import helpers and models
	
			////get data from database
			//set page data
			$pageData['error_msg'] = $error;
	
			//set page info
			$pageInfo['pageName'] = $this->pageName;
			$pageInfo['segment'] = strtolower(__FUNCTION__);
	
			////sort
			
			//render
			$this->_render($pageInfo, $pageData);
		} else {
			show_404();
		}
	}

	/*
	*
	*/
	public function event_list($pageData)
	{
		if( !empty($pageData) ){
			////import helpers and models

			////get data from database

			//set page info
			$pageInfo['pageName'] = $this->pageName;
			$pageInfo['segment'] = strtolower(__FUNCTION__);

			////sort
			
			//render
			$this->_render($pageInfo, $pageData);
		} else {
			show_404();
		}
	}

	/**
	 * Handles 
	 * @link
	 * @return render
	 */
	public function appointment_made()
	{	
		//get data from last url
		$this->load->library('session');
		$last_url = explode('/', $this->_get_session('referred_from'));
		$event = end($last_url);
		$user = prev($last_url);

		//set data
		$pageData['referred_from'] = $this->_get_session('referred_from');
		$pageData['details']['event'] = $event;
		$pageData['details']['host'] = $user;
		$pageData['details']['appointment'] = (array) $this->session->flashdata('appointment');
		$pageData['details']['attendee'] = $this->_decode($this->session->flashdata('attendee'));

		if( !empty($pageData['details']['appointment']) && !empty($pageData['details']['attendee']) ){
			//set page info
			$pageInfo['pageName'] = $this->pageName;
			$pageInfo['segment'] = strtolower(__FUNCTION__);
	
			////sort
			
			//render
			$this->_render($pageInfo, $pageData);
		} else {
			show_404();
		}
	}

	/**
	 * 
	 */
	public function json_get_times(String $username)
	{
		if( $this->is_ajax() ){
			$times = $this->_get_session('json_times');
			if( empty($times) ){
				$this->load->model('Appointment_Model');

				if( !empty($username) ){
					$output = $this->Appointment_Model->get_available_times($username);
					if( isset($output->Error) ){
						echo(json_encode(array('executed' => false, 'error' => $output->Error)));						
					} elseif( isset($output) ){
						$this->_set_session($output, 'json_times', 60); //for 1 minute in seconds
						echo(json_encode(array('executed' => true, 'success' => $output)));
					} else {
						echo(json_encode(array('executed' => false, 'error' => 'availability not given from user')));
					}
				} else {
					echo(json_encode(NULL));
				}
			} else {
				echo(json_encode(array('executed' => true, 'success' => $times)));
			}
		} else {
			show_404();
		}
	}

	/**
	 * 
	 */
	public function json_get_settings(String $username)
	{
		if( $this->is_ajax() ){
			$settings = $this->_get_session('json_settings');
			if( empty($settings) ){
				$this->load->model('Appointment_Model');
	
				if( !empty($username) ){
					$output = $this->Appointment_Model->get_settings($username);
					if( isset($output->Error) ){
						echo(json_encode(array('executed' => false, 'error' => $output->Error)));					
					} elseif( !empty($output) ){
						$this->_set_session($output, 'json_settings', 60); //for 1 minute in seconds
						echo(json_encode(array('executed' => true, 'success' => $output)));
					} else {
						echo(json_encode(array('executed' => false, 'error' => 'settings are not correct from user')));
					}
				} else {
					echo(json_encode(NULL));
				}
			} else {
				echo(json_encode(array('executed' => true, 'success' => $settings)));
			}
		} else {
			show_404();
		}
	}

	/**
	 * 
	 */
	public function json_get_appointments(String $username, String $event_title)
	{
		if( $this->is_ajax() ){
			$this->load->model('Appointment_Model');
			if( !empty($username) && !empty($event_title) ){
				$username =preg_replace('~[-_]~', ' ', ucfirst(strtolower($username)));
				$event_title = preg_replace('~[-_]~', ' ', ucfirst(strtolower($event_title)));
				$output = $this->Appointment_Model->get_appointments($username, $event_title);
				if( isset($output->Error) ){
					echo(json_encode(array('executed' => false, 'error' => $output->Error)));						
				} elseif( isset($output) ){
					$this->_set_session($output, 'json_appointments', 60); //for 1 minute in seconds
					echo(json_encode(array('executed' => true, 'success' => $output)));
				} else {
					echo(json_encode(array('executed' => true, 'success' => Array())));
				}
			} else {
				echo(json_encode(NULL));
			}
		} else {
			show_404();
		}
	}

	/**
	 * 
	 */
	public function json_make_appointment(String $username, String $event_title)
	{
		if( $this->is_ajax() ){
			if( !empty($username) && !empty($event_title) ){
				$username = preg_replace('~[-_]~', ' ', ucfirst(strtolower($username)));
				$event_title = preg_replace('~[-_]~', ' ', ucfirst(strtolower($event_title)));
				$this->load->model('Appointment_Model');
				$attendee = json_decode($this->input->post('attendee'));
				$appointment = json_decode($this->input->post('appointment'));

				$this->load->library('session');
				$this->session->set_flashdata('attendee', $attendee);
				$this->session->set_flashdata('appointment', $appointment);
	
				if( !empty($attendee) && !empty($appointment) ){
					$output = $this->Appointment_Model->make_appointment($username, $event_title, $attendee, $appointment);
					if( isset($output->Error) ){
						echo(json_encode(array('executed' => false, 'error' => $output->Error)));	
					} elseif( $output === TRUE ){
						echo(json_encode(array('executed' => true)));
					} else {
						//TODO better error handling
						echo(json_encode(array('executed' => false, 'error' => 'Something went wrong')));	
					}
				} else {
					echo(json_encode(NULL));
				}
			} else {
				echo(json_encode(NULL));
			}
		} else {
			show_404();
		}
	}
}
