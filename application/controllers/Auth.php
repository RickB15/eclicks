<?php
/**
* Auth
* 
* Controller for the login page.
* 
* PHP Version 7.3.5
* 
* LICENSE: 
*
* @category     Eclicks
* @package      MY
* @subpackage   Controller
* @copyright    -
* @license      -
* @version      0.0.1 
* @link         http://localhost:8383/auth
* @since        File available since Release 0.0.0
* @author       Rick Blanksma <rickblanksma@gmail.com>
*/
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handles the auth page » user login and register
 */
class Auth extends MY_Controller {
	public function __construct()
	{
		parent::__construct();

		//change page view path
		$this->data['path'] = 'auth';

		//default page info
		$this->pageName = strToLower(get_class($this));
		$this->pageAccess = 'private';
	}

	/**
	 * call from mybizzmail handeler
	 * @link auth
	 */
	public function index($data)
	{
		$query = parse_url(base_url('auth?'.$data), PHP_URL_QUERY);
		parse_str($query, $params);
		if( is_array($params) ){
			foreach( $params as $key => $segment ){
				switch($key) {
					case 'api':
						$api_key = (empty($segment) || $segment === 'null') ? show_404() : $segment;
						break;
					case 'name':
						$name = (empty($segment) || $segment === 'null') ? $this->bizz_login_error(ucfirst('"username"')) : $segment;
						break;
					case 'email':
						$email = (empty($segment) || $segment === 'null') ? $this->bizz_login_error(ucfirst('"E-mail"')) : $segment;
						break;
					case 'account_id':
						$account_id = (empty($segment) || $segment === 'null') ? $this->bizz_login_error(ucfirst('"E-mail"')) : $segment;
						break;
				}
			}
		}

		if( isset($api_key) && isset($name) && isset($email) && isset($account_id) ){
			$this->register_bizz_user($api_key, $name, $email, $account_id);
		} else {
			show_404();
		}
	}

	/**
	 * Handles page info and database data to render an auth.login view
	 * @link auth/login
	 * @return render
	 */
	public function login()
	{
		redirect($this->config->item('bizz_url_ui'));
		// show_404();
		// ////import helpers and models

		// ////get data from database

		// //set page info
		// $pageInfo['pageName'] = strtolower(__FUNCTION__);

		// ////sort
		
		// //render
		// $this->_render($pageInfo);
	}

	/**
	 * Handles page info and database data to render an auth.register view
	 * @link aut/register
	 * @return render
	 */
	public function register()
	{
		show_404();
		// ////import helpers and models

		// ////get data from database

		// //set page info
		// $pageInfo['pageName'] = strtolower(__FUNCTION__);

		// ////sort
		
		// //render
		// $this->_render($pageInfo);
	}

	/**
	 * 
	 */
	public function reset_password()
	{
		////import helpers and models

		////get data from database

		//set page info
		$pageInfo['pageName'] = strtolower(__FUNCTION__);

		////sort
		
		//render
		$this->_render($pageInfo);
	}

	/**
	 * Handles page info and database data to render an auth.logout view
	 * @link auth/logout
	 * @return render
	 */
	public function logout()
	{
		////import helpers and models

		////get data from database

		//set page info
		$pageInfo['pageName'] = strtolower(__FUNCTION__);

		////sort
		
		//render
		$this->_render($pageInfo);
	}

	/**
     * login user
	 * @param url {string} contains the last known page url
     * @return 
     */
	private function login_bizz_user(String $cs_username, String $email)
	{
		//import helpers, models and librarys
		$this->load->model('Auth_Model');

		//set form validations
		// $password = null;//get password;

		// if( $this->Auth_Model->check_login($cs_username, $password) === TRUE ){
			$expire = 43200; //12 hours in seconds
			//TODO check if auth has payed for client schedular
				//set client schedular data if user has client schedular
				$client_schedular_data = $this->Auth_Model->get_cs_user($cs_username); //Client Schedular data //TODO encrypt as array with nested array encryption
				if( $this->_set_session($client_schedular_data, 'client_schedular', $expire) === FALSE ){
					//TODO error handling client schedular session not set
					echo 'client schedular session not set';
					die();
				}

			//set JWT session data to check user without database
			$data = Array(
				'username' 			=> $this->_encode($email),
				'details' 			=> $this->Auth_Model->auth_details($email),
				'bizz_meta'			=> $this->Auth_Model->get_bizz_meta($cs_username)
			);
			$secret = $this->_generate_secret();
			$jws_token = $this->_jwt_encrypt($data, 'login', $expire, $secret);
			$session_data = $this->_encode(Array(
				'token' => $jws_token, 'secret' => $secret
			));
			
			if( $this->_set_session($session_data, 'auth', $expire) === TRUE ){
				$referred_from = $this->_get_session('referred_from');
				if( !empty($referred_from) ){
					redirect($referred_from, 'refresh');
				} else {
					redirect(base_url());
				}
			} else {
				//TODO error handling
				echo 'session not set';
			}				
		// } else {
		// 	//TODO error handling
		// 	echo 'username or password incorrect';
		// }
	}

	// /**
    //  * login user
	//  * @param url {string} contains the last known page url
    //  * @return 
    //  */
	// public function login_user(String $url=null)
	// {
	// 	//import helpers, models and librarys
	// 	$this->load->library('form_validation');
	// 	$this->load->helper('formvalidation_helper'); //TODO from config file
	// 	$this->load->model('Auth_Model');

	// 	//set form validations
	// 	$validations = set_login_validations($this->input->post('username'));
	// 	$this->form_validation->set_rules($validations);

	// 	//check if form is correct filled in
	// 	if( $this->form_validation->run() === TRUE ){
	// 		$username = $this->input->post('username');
	// 		$password = $this->input->post('password');

	// 		if( $this->Auth_Model->check_login($username, $password) === TRUE ){
	// 			$expire = 43200; //12 hours in seconds
	// 			//TODO check if auth has payed for client schedular
	// 				//set client schedular data if user has client schedular
	// 				$client_schedular_data = $this->Auth_Model->get_cs_user($username); //Client Schedular data //TODO encrypt as array with nested array encryption
	// 				if( $this->_set_session($client_schedular_data, 'client_schedular', $expire) === FALSE ){
	// 					//TODO error handling client schedular session not set
	// 					echo 'client schedular session not set';
	// 					die();
	// 				}

	// 			//set JWT session data to check user without database
	// 			$data = Array(
	// 				'username' 			=> $this->_encode($username),
	// 				'details' 			=> $this->Auth_Model->auth_details($username)
	// 			);
	// 			$secret = $this->_generate_secret();
	// 			$jws_token = $this->_jwt_encrypt($data, 'login', $expire, $secret);
	// 			$session_data = $this->_encode(Array(
	// 				'token' => $jws_token, 'secret' => $secret
	// 			));
				
	// 			if( $this->_set_session($session_data, 'auth', $expire) === TRUE ){
	// 				$referred_from = $this->_get_session('referred_from');
	// 				if( !empty($referred_from) ){
	// 					redirect($referred_from, 'refresh');
	// 				} else {
	// 					redirect(base_url());
	// 				}
	// 			} else {
	// 				//TODO error handling
	// 				echo 'session not set';
	// 			}				
	// 		} else {
	// 			//TODO error handling
	// 			echo 'username or password incorrect';
	// 		}
	// 	} else {
	// 		//TODO error handling
	// 		echo 'form not correct';
	// 		$this->login();
	// 	}
	// }

	/**
     * register user
     * @return 
     */
	public function register_bizz_user(String $api_key, String $cs_username, String $email, String $account_id)
	{
		//import helpers, models and librarys
		$this->load->model('Auth_Model');
		$this->load->helper('curl_helper');

		//TODO check if mybizzmail data is changed
		if(!empty($api_key) && !empty($cs_username) && !empty($email) && !empty($account_id) ){
			//check if user doesn't exist
			if( $this->Auth_Model->check_register($email) === FALSE ){ //TODO don't depend on email but mybizzmail username
				if( $this->Auth_Model->get_cs_user($cs_username) !== NULL ){
					$this->bizz_login_error(ucfirst('"username"'), ucfirst(lang('already_exists')));
				}
				$password = $this->_generate_secret();
				$language = 'english'; //TODO make dynamic

				//set auth variables (corresponding to database fields)
				$auth = Array(
					'username'	=> strtolower($email), //TODO don't depend on email but mybizzmail username
					'password'	=> $password
				);
				//hash user variables
				$auth = $this->_encode($auth);

				//get mybizzmail details
				$details = json_decode(getOverviewDetails(bizz_url(), $api_key), true);
				if( !empty($details) ){
					$name = explode(' ', $details['name']);
					//set user details variables (corresponding to database fields)
					$auth_details = Array(
						'email' 		=> (!empty($details['email'])) ? strtolower($details['email']) : 'test@mail.com',
						'initials' 		=> strtolower("t"),
						'firstname' 	=> (!empty($name)) ? ucfirst(strtolower($name[0])) : 'Test',
						'prefix'		=> (!empty($name) && isset($name[1])) ? (count($name) >= 3) ? strtolower($name[1]) : strtolower("") : '',
						'lastname' 		=> (!empty($name) && isset($name[1]) || isset($name[2])) ? (count($name) >= 3) ? ucfirst(strtolower($name[2])) : ucfirst(strtolower($name[1])) : 'User',
						'address' 		=> ucwords(strtolower("not set")),
						'housenumber'	=> 0,
						'addition' 		=> strtoUpper(""),
						'zipcode' 		=> strtoUpper("0000AA"),
						'city'			=> ucwords(strtolower("not set")),
						'country' 		=> ucwords(strtolower("not set")),
						'mobile' 		=> 06,
						'company_name' 	=> (!empty($details['email'])) ? ucfirst(strtolower($details['company'])) : '',
						'company_mobile'=> null,
						'gender' 		=> strtolower("man"),
						'language' 		=> strtolower($language),
						'auth_username'	=> $auth['username']
					);
					//hash user details variables with username as exception
					$auth_details = $this->_encode($auth_details, 'auth_username');
				} else {
					//TODO better error handling
					echo 'No connection to mybizzmail. Please make sure you are connected to the internet';
					die();
				}

				$bizz_meta = $this->create_bizz_meta($api_key, $cs_username, $account_id, $auth['username']);

				//set new user
				if( $this->Auth_Model->register_auth($auth, $auth_details, $bizz_meta) === TRUE ){
					//TODO check if auth has payed for client schedular
					if( $this->create_cs_user($cs_username, $email, $auth['username'], $api_key) === TRUE ){
						//redirect to login
						$this->login_bizz_user($cs_username, $email);
					} else {
						//TODO error handling
						echo 'client schedular user not inserted';
					}
				} else {
					//TODO error handling
					echo 'auth not inserted';
				}			
			} else {
				// if( check_cs_register($cs_username) === FALSE ){
				// 	$auth_username = get_cs_register($email); //TODO don't depend on email but mybizzmail username
				// 	$this->create_cs_user($cs_username, $email, $auth_username);
				// } else {
				// 	$this->login_bizz_user($cs_username); //TODO don't depend on email but mybizzmail username
				// }
				$this->login_bizz_user($cs_username, $email); //TODO don't depend on email but mybizzmail username
			}
		} else {
			show_404();
		}
	}

	// /**
    //  * register user
    //  * @return 
    //  */
	// public function register_user()
	// {
	// 	//import helpers, models and librarys
	// 	$this->load->library('form_validation');
	// 	$this->load->helper('formvalidation_helper'); //TODO from config file
	// 	$this->load->model('Auth_Model');

	// 	//set form validations
	// 	$validations = set_register_validations();
	// 	$this->form_validation->set_rules($validations);

	// 	//check if form is correct filled in
	// 	if( $this->form_validation->run() === TRUE ){
	// 		$username = $this->input->post('username');
	// 		$password = $this->input->post('password');
	// 		$language = 'english'; //TODO make dynamic

	// 		//check if user doesn't exist
	// 		if( $this->Auth_Model->check_register($username) === FALSE ){
	// 			//set auth variables (corresponding to database fields)
	// 			$auth = Array(
	// 				'username'	=> $username,
	// 				'password'	=> $password
	// 			);
	// 			//hash user variables
	// 			$auth = $this->_encode($auth);

	// 			//set user details variables (corresponding to database fields)
	// 			$auth_details = Array(
	// 				'email' 		=> strtolower($this->input->post('email')),
	// 				'initials' 		=> strtolower($this->input->post('initials')),
	// 				'firstname' 	=> ucfirst(strtolower($this->input->post('first_name'))),
	// 				'prefix'		=> strtolower($this->input->post('prefix')),
	// 				'lastname' 		=> ucfirst(strtolower($this->input->post('last_name'))),
	// 				'address' 		=> ucwords(strtolower($this->input->post('address'))),
	// 				'housenumber'	=> $this->input->post('house_number'),
	// 				'addition' 		=> strtoUpper($this->input->post('addition')),
	// 				'zipcode' 		=> strtoUpper($this->input->post('zipcode')),
	// 				'city'			=> ucwords(strtolower($this->input->post('city'))),
	// 				'country' 		=> ucwords(strtolower($this->input->post('country'))),
	// 				'mobile' 		=> $this->input->post('mobile'),
	// 				'company_name' 	=> $this->input->post('company_name'),
	// 				'company_mobile'=> $this->input->post('company_phone'),
	// 				'gender' 		=> strtolower($this->input->post('gender-man')),
	// 				'language' 		=> strtolower($language),
	// 				'auth_username'	=> $auth['username']
	// 			);
	// 			//hash user details variables with username as exception
	// 			$auth_details = $this->_encode($auth_details, 'auth_username');

	// 			//set meta data to connect to mybizzmail
	// 			$bizz_meta = Array(
	// 				'name' 			=> strtolower(''), //TODO change this by mybizzmail system
	// 				'api_key'		=> '', //TODO change this by mybizzmail system
	// 				'group_id'		=> '', //TODO change this by mybizzmail system
	// 				'user_id'		=> '', //TODO change this by mybizzmail system
	// 				'auth_username'	=> $auth['username']
	// 			);
	// 			//hash metadata variables with username as exception
	// 			$bizz_meta = $this->_encode($bizz_meta, array('auth_username', 'group_id', 'user_id'));

	// 			//set new user
	// 			if( $this->Auth_Model->register_auth($auth, $auth_details, $bizz_meta) === TRUE ){
	// 				//TODO check if auth has payed for client schedular
	// 				if( $this->create_cs_user($bizz_meta['name'], $auth_details['email'], $auth['username']) === TRUE ){
	// 					//redirect to login
	// 					redirect('login');
	// 				} else {
	// 					//TODO error handling
	// 					echo 'client schedular user not inserted';
	// 				}
	// 			} else {
	// 				//TODO error handling
	// 				echo 'auth not inserted';
	// 			}
	// 		} else {
	// 			//TODO error handling
	// 			echo 'username exists';
	// 		}
	// 	} else {
	// 		//TODO error handling
	// 		echo 'form not correct';
	// 		$this->register();
	// 	}
	// }

	/**
	 * 
	 */
	public function change_language($language)
	{
		switch ($language) {
			case 'nl':
				$this->_set_session('dutch', 'lang');
				break;
			
			default:
				$this->_set_session('english', 'lang');
				break;
		}
		if(isset($this->data['user'])){
			$this->load->model('Auth_Model');
			if( $this->Auth_Model->set_language($language, $this->data['user']->username) === TRUE ){
				$referred_from = $this->_get_session('referred_from');
				if( !empty($referred_from) ){
					redirect($referred_from, 'refresh');
				} else {
					redirect(base_url());
				}
			} else {
				//TODO better error handling
				$referred_from = $this->_get_session('referred_from');
				if( !empty($referred_from) ){
					redirect($referred_from, 'refresh');
				} else {
					redirect(base_url());
				}
			}
		} else {
			$referred_from = $this->_get_session('referred_from');
			if( !empty($referred_from) ){
				redirect($referred_from, 'refresh');
			}
		}
	}

	/**
	 * 
	 */
	public function reset_user_password()
	{
		
	}

	/**
     * logout user
     * @return 
     */
	public function logout_user()
	{
		//delete session
		$this->_remove_session('auth');
		$this->_remove_session('client_schedular');
		redirect($this->config->item('bizz_url_ui'));
	}

	private function bizz_login_error($error, $message=null)
	{
		$error_message = lang('no_correct') . ' ' . $error . ' ' . lang('filled_in') . $message;
		echo "
		<script type='application/javascript'>
			window.alert(
				'$error_message'
			);
			window.location.href='".$this->config->item('bizz_url_ui')."account/edit';
		</script>";
	}

	private function create_bizz_meta(String $api_key, String $username, String $account_id, String $auth_username)
	{
		//set default mails
		$this->create_bizz_mails($api_key);
		//set meta data to connect to mybizzmail
		$bizz_meta = Array(
			'name' 			=> strtolower($username),
			'api_key'		=> $api_key,
			'group_id'		=> $this->create_bizz_group($api_key),
			'user_id'		=> (int) $account_id,
			'auth_username'	=> $auth_username
		);
		//hash metadata variables with username as exception
		$bizz_meta = $this->_encode($bizz_meta, array('auth_username', 'group_id', 'user_id'));
		
		return $bizz_meta;
	}

	private function create_bizz_group(String $api_key)
	{
		$this->load->helper('curl_helper');
		$group = json_decode(creatingGroup(bizz_url(), $api_key), true);
		if(!isset($group['id']) || (int) $group['id'] === 0){
			//TODO better error handling. Ask for group id manually?
			echo ucfirst(lang('group_id_error'));
			die();
		}
		return (int) $group['id'];
	}

	private function create_bizz_mails(String $api_key)
	{
		$this->load->helper('curl_helper');

		//only add if mails are not already set
		if( empty(array_filter($this->_default_notifications($api_key))) ){
			$mails = array(
				$this->config->item('appointmentConfirmNL'),
				$this->config->item('appointmentConfirmENG'),
				$this->config->item('mail24hr'),
				$this->config->item('mail1hr'),
				$this->config->item('mail10min'),
				$this->config->item('appointmentCancel'),
				$this->config->item('notificationBooking'),
			);
	
			$status = json_decode(copyMails(bizz_url(), $api_key, $mails), true);
			if( $status !== 201 ){
				//if status is null there is no connection to bizzmail
				if( $status !== NULL ){
					//TODO better error handling
					echo "could not set necessary emails";
					die();
				} else {
					//TODO better error handling (can go through?)
				}
			}
		}
	}

	private function create_cs_user(String $username, String $email, String $auth_username, String $api_key)
	{
		//TODO check if auth has payed for client schedular
		//import models and helpers
		$this->load->model('Settings_Model');
		$this->load->model('Notifications_Model');
		$this->load->helper('curl_helper');

		//create default database items
		$default_settings = $this->_default_settings();
		$default_notifications = $this->_default_notifications($api_key);

		//set default database items
		$settings_insert_id = $this->Settings_Model->set_settings($default_settings);
		$notifications_insert_id = $this->Notifications_Model->set_notifications($default_notifications);
		if( $settings_insert_id >= 0 && $notifications_insert_id >= 0 ){
			$details = json_decode(getOverviewDetails(bizz_url(), $api_key), true);
			$client_schedular_user = Array(
				'cs_username'		=> strtolower($username),
				'email'				=> strtolower($email),
				'company'			=> $details['company'],
				'settings_id'		=> $settings_insert_id,
				'notifications_id'	=> $notifications_insert_id,
				'auth_username'		=> $auth_username
			);
			//encode with exception for settings_id, notifications_id and auth_username
			$client_schedular_user = $this->_encode($client_schedular_user, array('settings_id', 'notifications_id', 'auth_username'));
			//TODO encode client_schedular_user
			if( $this->Auth_Model->register_client_schedular_auth($client_schedular_user) === TRUE ){
				return TRUE;
			} else {
				//TODO error handling
				echo 'client schedular user not inserted';
			}
		} else {
			//TODO error handling default database values
			echo 'default database values not correct';
		}
		return FALSE;
	}

	/**
     * Generate secret
     * @return secret {string}
     */
	private function _generate_secret()
	{
		//generate secret
		return md5(microtime().rand());
	}
	
	/**
	 * 
	 */
	private function _default_settings()
	{
		$start_times = array(0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55);
		return Array(
			'appointment_interim' 		=> '00:30:00',
			'appointment_start_times' 	=> json_encode($start_times),
			'appointments_a_day' 		=> 4,
			'redirect_url'				=> base_url('appointment/appointment_made'),
			'time_zone'					=> 1
		);
	}

	/**
	 * 
	 */
	private function _default_notifications($api_key)
	{
		$default_emails = Array(
			'email_direct' 	=> 0,
			'email_10' 		=> 0,
			'email_24'		=> 0,
			'email_cancel'	=> 0,
			'email_host'	=> 0,
			'sms_direct'	=> 0,
			'sms_10'		=> 0,
			'sms_24'		=> 0,
			'sms_cancel'	=> 0,			
			'sms_host'		=> 0
		);
		$emails = json_decode(getNotificationEmails(bizz_url(), $api_key), true);
		if(!empty($emails)){
			foreach( $emails as $email ) {
				if( strpos($email['name'], 'Client Schedular') !== FALSE ){
					switch (trim($email['name'])) {
						case 'Client Schedular - appointment confirmed NL':
							$default_emails['email_direct'] = $email['id'];
							break;
						
						case 'Client Schedular - 10 min your appointm. eng Default':
							$default_emails['email_10'] = $email['id'];
							break;
						
						case 'Client Schedular - 24 hours your appointm. eng Default':
							$default_emails['email_24'] = $email['id'];
							break;
						
						case 'Client Schedular - cancel confirmation':
							$default_emails['email_cancel'] = $email['id'];
							break;

						case 'Client Schedular - your appointm.conversion eng Default - copy':
							$default_emails['email_host'] = $email['id'];
							break;
					}
				}
			}
		}

		return $default_emails;
	}
}
