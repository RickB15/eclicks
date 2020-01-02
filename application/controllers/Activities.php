<?php
/**
* Activities
* 
* Controller for the activities page (index page logedin user) Client Schedular.
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
* @link         http://localhost:8383/activites
* @since        File available since Release 0.0.0
* @author       Rick Blanksma <rickblanksma@gmail.com>
*/
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * !Client Schedular application!
 * Handels the activities page » overview of appointments made by attendees
 * *index page for an user of Client Schedular
 */
class Activities extends MY_Controller {
	public function __construct()
	{
		parent::__construct();

		//application info
		$this->application['appName'] = 'Client Schedular';

		//default page info
		$this->pageName = strToLower(get_class($this));
		$this->pageAccess = 'private';

		if( $this->_check_auth() === FALSE ){
			redirect(base_url('login'));
		}
	}

	/**
	 * Handels page info and database data to render an activities.index view
	 * @link activities
	 * @return render
	 */
	public function index()	
	{
		//import helpers and models
		$this->load->model('Event_Model');
		$this->load->model("Availability_Model");
		$this->load->model('Activities_Model');

		//get data from database
		$activities = $this->Activities_Model->get_activities($this->data['cs_user']->cs_username);

		//set page data
		//TODO make introduction page
		if( $this->Event_Model->get_events($this->data['cs_user']->cs_username) === NULL ){
			$pageData['events_redirect'] = 'settings/events';
		}
		if( $this->Availability_Model->get_availability($this->data['cs_user']->cs_username) === NULL ){
			$pageData['availability_redirect'] = 'settings/availability';
		}

		if( !empty($activities) ){
			foreach ($activities as $key => $value) {
				$date = strtotime($value['date']);
				$today = strtotime('now');
				$activities[$key]['date'] = date('d/M/Y', strtotime($value['date']));
				if( $date < $today || $value['status'] === 'canceled' ) {					
					$pageData['previous_activities'][$key] = $activities[$key];
					if( $date > $today ){
						$pageData['previous_activities'][$key]['until_now'] = date('d', $date - $today) + 1; //plus today
					} else {
						$pageData['previous_activities'][$key]['from_now'] = date('d', $today - $date) - 1; //minus today
					}
					$pageData['previous_activities'][$key]['day'] = date('d', $date);
				} elseif( $value['status'] !== 'deleted' ){
					$pageData['upcoming_activities'][$key] = $activities[$key];
					$pageData['upcoming_activities'][$key]['until_now'] = date('d', $date - $today) + 1; //plus today
					$pageData['upcoming_activities'][$key]['day'] = date('d', $date);
				}
			}
		}
		$pageData['host']['name'] = $this->_decode($this->data['cs_user']->cs_username);
		$pageData['host']['email'] = $this->_decode($this->data['cs_user']->email);

		//set custom js, css and/or font files
		$js = Array('activities.js' => 'end');
		//// $css = array();
		//// $fonts = array();

		//import custom js, css and/or font files
		$this->files['js'] = $js;
		//// $this->files['css'] = $css;
		//// $this->files['fonts'] = $fonts;

		//set page info
		$pageInfo['pageName'] = $this->pageName;
		$pageInfo['segment'] = strtolower(__FUNCTION__);

		////sort
		
		//render
		$this->_render($pageInfo, $pageData);
	}

	public function update_appointment()
	{
		$appointment_id = $this->input->post('update_appointment');
		$status = $this->input->post('submit');
		if( !empty($appointment_id) && !empty($status) && is_numeric($appointment_id) ){
			$this->load->model('Activities_Model');

			if( $this->Activities_Model->update_status($appointment_id, $status) === TRUE ){
				redirect(base_url('activities'));
			} else {
				//TODO better error handling
				echo 'Appointment not canceled. Something went wrong';
			}
		} else {
			show_404();
		}
	}
}