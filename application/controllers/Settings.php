<?php
/**
* Settings
* 
* Controller for the settings page.
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
* @link         http://localhost:8383/settings
* @since        File available since Release 0.0.0
* @author       Rick Blanksma <rickblanksma@gmail.com>
*/
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * !Client Schedular application!
 * Handles the settings page » all user settings for Client Schedular
 */
class Settings extends MY_Controller {
	public function __construct()
	{
		parent::__construct();

		//change page view path
		$this->application['appName'] = 'Client Schedular';

		//default page info
		$this->pageName = strToLower(get_class($this));
		$this->pageAccess = 'private';

		if( $this->_check_auth() === FALSE ){
			redirect(base_url('login'));
		}
	}

	/**
	 * @link settings
	 */
	public function index()
	{	
		////import helpers and models

		////get data from database

		////set page info

		////sort
		
		////render
		//redirect to overview page
		$this->overview();
	}

	/**
	 * Handles page info and database data to render a settings.overview view
	 * This is an overview of all settings that an user can change
	 * @link settings
	 * @return render
	 */
	public function overview()
	{	
		////import helpers and models

		////get data from database

		//set page data
		$cs_user = $this->data['cs_user'];
		$pageData['cs_user'] = $this->_decode($cs_user);

		//set page info
		$pageInfo['pageName'] = $this->pageName;
		$pageInfo['segment'] = strtolower(__FUNCTION__);

		////sort
		
		//render
		$this->_render($pageInfo, $pageData);
	}

	/**
	 * Handles page info and database data to render a settings.general view
	 * General settings that an user can change
	 * @link settings/general
	 * @return render
	 */
	public function general()
	{	
		if( isset($this->data['cs_user']->settings_id) && !empty($this->data['cs_user']->settings_id) ){
			//import helpers and models
			$this->load->model('Settings_Model');
	
			//get data from database
			$settings = $this->Settings_Model->get_settings($this->_decode($this->data['cs_user']->settings_id));
	
			//set page info
			$pageInfo['pageName'] = $this->pageName;
			$pageInfo['segment'] = strtolower(__FUNCTION__);
			
			$interim = DateTime::createFromFormat('H:i:s', $settings->appointment_interim);
			//set page data
			$pageData['settings'] = Array(
				'amount'			=> (int)$settings->appointments_a_day,
				'interim'			=> (float)$interim->format('H') + ($interim->format('i') / 60),
				'redirect_url'		=> (string)$settings->redirect_url,
				'start_times'		=> json_decode($settings->appointment_start_times),
				'time_zone' 		=> (int)$settings->time_zone
	
			);
	
			//set custom js, css and/or font files
			$js = Array(
				'general_settings.js' => 'end'
			);
			//// $css = array();
			//// $fonts = array();
	
			//import custom js, css and/or font files
			$this->files['js'] = $js;
			//// $this->files['css'] = $css;
			//// $this->files['fonts'] = $fonts;
	
			////sort
			
			//render
			$this->_render($pageInfo, $pageData);
		} else {
			//TODO better error handling
			show_404();
		}
	}

	/**
	 * Handles page info and database data to render a settings.availability view
	 * Availability that an user can give
	 * @link settings/availability
	 * @return render
	 */
	public function availability()
	{	
		////import helpers and models

		////get data from database

		//set page info
		$pageInfo['pageName'] = $this->pageName;
		$pageInfo['segment'] = strtolower(__FUNCTION__);

		//set custom js, css and/or font files
		$js = Array(
			'availability.js' => 'end',
			'lib/moment-2.24.0/moment.js' => 'start',
			'lib/moment-2.24.0/moment-locales.js' => 'start',
			'fullcalendar-4.3.1/packages/core/main.min.js' => 'start',
			'fullcalendar-4.3.1/packages/core/locales-all.min.js' => 'start',
			'fullcalendar-4.3.1/packages/interaction/main.min.js' => 'start',
			'fullcalendar-4.3.1/packages/daygrid/main.min.js' => 'start',
			'fullcalendar-4.3.1/packages/bootstrap/main.min.js' => 'start',
			'fullcalendar-4.3.1/packages/timegrid/main.min.js' => 'start',
			'fullcalendar-4.3.1/packages/list/main.min.js' => 'start'
		);
		$css = array(
			'fullcalendar-4.3.1/packages/core/main.min.css',
			'fullcalendar-4.3.1/packages/daygrid/main.min.css',
			'fullcalendar-4.3.1/packages/timegrid/main.min.css',
			'fullcalendar-4.3.1/packages/list/main.min.css',
			'fullcalendar-4.3.1/packages/bootstrap/main.min.css'
		);
		//// $fonts = array();

		//import custom js, css and/or font files
		$this->files['js'] = $js;
		$this->files['css'] = $css;
		//// $this->files['fonts'] = $fonts;

		////sort
		
		//render
		$this->_render($pageInfo);
	}

	/**
	 * Handles page info and database data to render a settings.events view
	 * Different type of events that an user can make where an attendee can make an appointent with
	 * @link settings/events
	 * @return render
	 */
	public function events()
	{	
		//import helpers and models
		$this->load->model('Event_Model');

		//get data from database
		$pageData['events'] = $this->Event_Model->get_events($this->data['cs_user']->cs_username);

		//set page info
		$pageInfo['pageName'] = $this->pageName;
		$pageInfo['segment'] = strtolower(__FUNCTION__);

		//set custom js, css and/or font files
		$js = Array(
			'events.js' => 'end'
		);
		//// $css = array();
		//// $fonts = array();

		//import custom js, css and/or font files
		$this->files['js'] = $js;
		//// $this->files['css'] = $css;
		//// $this->files['fonts'] = $fonts;

		//sort
		/** sort events so newest is first */
		if( !empty($pageData['events']) ){
			$pageData['events'] = array_reverse($pageData['events']);
		}
		
		//render
		$this->_render($pageInfo, $pageData);
	}

	/**
	 * Handles page info and database data to render a settings.notifications view
	 * Different type of notifications where an user can choose from to send to an attendee
	 * @link settings/notifications
	 * @return render
	 */
	public function notifications()
	{	
		//import helpers and models
		$this->load->model('Auth_Model');
		$this->load->helper('cURL_helper');

		//get data from database
		$api_key = $this->Auth_Model->auth_bizzmail($this->data['user']->username)->api_key;

		// Getting Emails
		$response = getNotificationEmails(bizz_url(), $api_key);
		$pageData['notification_email'] = json_decode($response, true);

		// Getting Sms
		$response = getNotificationSms(bizz_url(), $api_key);
		$pageData['notification_sms'] = json_decode($response, true);

		//set page info
		$pageInfo['pageName'] = $this->pageName;
		$pageInfo['segment'] = strtolower(__FUNCTION__);

		////sort
		
		//render
		$this->_render($pageInfo, $pageData);
	}

	/**
	 * Handles page info and database data to render a settings.notifications view
	 * Different type of notifications where an user can choose from to send to an attendee
	 * @link settings/associated-calendars
	 * @return render
	 */
	public function associated_calendars()
	{	
		////import helpers and models

		////get data from database

		//set page info
		$pageInfo['pageName'] = $this->pageName;
		$pageInfo['segment'] = strtolower(__FUNCTION__);

		//set custom js, css and/or font files
		$js = Array(
			'associated_calendars.js' => 'end',
			'lib/google-api/google_calendar.js' => 'end',
			'lib/hello-v2.0.0-4/hello.js' => 'start'
		);
		//// $css = array();
		//// $fonts = array();

		//import custom js, css and/or font files
		$this->files['js'] = $js;
		//// $this->files['css'] = $css;
		//// $this->files['fonts'] = $fonts;

		////sort
		
		//render
		$this->_render($pageInfo);
	}

	/**
	 * 
	 */
	public function json_set_general()
	{
		if( $this->is_ajax() ){
			$this->load->model('Settings_Model');
			$input = json_decode($this->input->post('setting'));

			if( !empty($input) ){
				$output = $this->Settings_Model->update_settings($this->_decode($this->data['cs_user']->settings_id), $input);
				if( $output === TRUE ){
					echo(json_encode(array('id' => $input->id, 'executed' => true)));
				} else {
					echo(json_encode(array('id' => $input->id, 'executed' => false, 'error' => $output)));
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
	public function json_get_availability()
	{
		if( $this->is_ajax() ){
			$this->load->model('Availability_Model');
			$availability = $this->Availability_Model->get_availability($this->data['cs_user']->cs_username);
			
			if( !empty($availability) ){
				echo(json_encode($availability));
			} else {
				echo(json_encode(Array()));
			}
		}
	}

	/**
	 * 
	 */
	public function json_get_specific_availability()
	{
		if( $this->is_ajax() ){
			$this->load->model('Availability_Model');
			$availability = $this->Availability_Model->get_availability($this->data['cs_user']->cs_username, null, true);
			
			if( !empty($availability) ){
				echo(json_encode($availability));
			} else {
				echo(json_encode(Array()));
			}
		}
	}

	/**
	 * 
	 */
	public function json_set_availability()
	{
		if( $this->is_ajax() ){
			$this->load->model('Availability_Model');
			$availability = json_decode($this->input->post('availability'), true);

			if( !empty($availability) ){
				$output = $this->Availability_Model->set_availability($this->data['cs_user']->cs_username, $availability);
				if( $output === TRUE ){
					echo(json_encode(array('executed' => true)));
				} else {
					//TODO better error handling from output
					echo(json_encode(array('executed' => false, 'error' => $output)));
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
	public function json_update_availability()
	{
		if( $this->is_ajax() ){
			$this->load->model('Availability_Model');
			$availability = json_decode($this->input->post('availability'), true);

			if( !empty($availability) ){
				$output = $this->Availability_Model->update_availability($availability);
				if( $output === TRUE ){
					echo(json_encode(array('executed' => true)));
				} else {
					//TODO better error handling from output
					echo(json_encode(array('executed' => false, 'error' => $output)));
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
	public function json_delete_availability()
	{
		if( $this->is_ajax() ){
			$this->load->model('Availability_Model');
			$availability = json_decode($this->input->post('availability'), true);

			if( !empty($availability) ){
				$output = $this->Availability_Model->delete_availability($this->data['cs_user']->cs_username, $availability);
				if( $output === TRUE ){
					echo(json_encode(array('executed' => true)));
				} else {
					//TODO better error handling from output
					echo(json_encode(array('executed' => false, 'error' => $output)));
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
	public function json_get_event()
	{
		if( $this->is_ajax() ){
			$this->load->model('Event_Model');
			$event = json_decode($this->input->post('event'));

			if( !empty($event) ){
				$output = $this->Event_Model->get_event($this->data['cs_user']->cs_username, $event->id);
				if( !empty($output) ){
					echo(json_encode($output));
				} else {
					//TODO better error handling from output
					echo(json_encode(array('executed' => false, 'error' => 'Something went wrong')));
				}
			} else {
				echo(json_encode(NULL));
			}
		} else {
			show_404();
		}
	}

	//TODO set event unique index with {user_cs_username}, {title} and {type}; {type} (required) must be added
	/**
	 * 
	 */
	public function json_set_event()
	{
		if( $this->is_ajax() ){
			$this->load->model('Event_Model');
			$event = json_decode($this->input->post('event'));

			if( !empty($event) ){
				if( isset($event->id) ){
					echo(json_encode($this->_update_event($event)));
				} else {
					$output = $this->Event_Model->set_event($this->data['cs_user']->cs_username, $event);
					if( $output === TRUE ){
						echo(json_encode(array('executed' => true)));
					} else {
						//TODO better error handling from output
						echo(json_encode(array('executed' => false, 'error' => 'Something went wrong')));
					}
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
	public function json_delete_event()
	{
		if( $this->is_ajax() ){
			$this->load->model('Event_Model');
			$event = json_decode($this->input->post('event'));

			if( !empty($event) && isset($event->id) ){
				$output = $this->Event_Model->delete_event($this->data['cs_user']->cs_username, $event->id);
				if( $output === TRUE ){
					echo(json_encode(array('executed' => true)));
				} else {
					//TODO better error handling from output
					echo(json_encode(array('executed' => false, 'error' => "Couldn't delete event")));
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
	private function _update_event(Object $event)
	{
		$this->load->model('Event_Model');
		$result = $this->Event_Model->get_event($this->data['cs_user']->cs_username, $event->id);
		if( !empty($result) ){
			$output = $this->Event_Model->update_event($this->data['cs_user']->cs_username, $event);
			if( $output === TRUE ){
				return array('executed' => true);
			} else {
				//TODO better error handling from output
				return array('executed' => false, 'error' => 'Something went wrong');
			}
		} else {
			return array('executed' => false, 'error' => 'Event id not found');
		}
	}
}
