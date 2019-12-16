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
* @author       Rick Blanskma <rickblanksma@gmail.com>
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
	 * @link auth
	 */
	public function index()
	{	
		////import helpers and models

		////get data from database

		////set page info

		////sort
		
		////render
		//redirect to login page
		$this->login();
	}

	/**
	 * Handles page info and database data to render an auth.login view
	 * @link auth/login
	 * @return render
	 */
	public function login()
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
	 * Handles page info and database data to render an auth.register view
	 * @link aut/register
	 * @return render
	 */
	public function register()
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
	public function login_user(String $url=null)
	{
		//import helpers, models and librarys
		$this->load->library('form_validation');
		$this->load->helper('formvalidation_helper'); //TODO from config file
		$this->load->model('Auth_Model');

		//set form validations
		$validations = set_login_validations($this->input->post('username'));
		$this->form_validation->set_rules($validations);

		//check if form is correct filled in
		if( $this->form_validation->run() === TRUE ){
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			if( $this->Auth_Model->check_login($username, $password) === TRUE ){
				$expire = 43200; //12 hours in seconds
				//TODO check if auth has payed for client schedular
					//set client schedular data if user has client schedular
					$client_schedular_data = $this->Auth_Model->get_cs_user($username); //Client Schedular data //TODO encrypt as array with nested array encryption
					if( $this->_set_session($client_schedular_data, 'client_schedular', $expire) === FALSE ){
						//TODO error handling client schedular session not set
						echo 'client schedular session not set';
						die();
					}

				//set JWT session data to check user without database
				$data = Array(
					'username' 			=> $this->_encode($username),
					'details' 			=> $this->Auth_Model->auth_details($username)
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
			} else {
				//TODO error handling
				echo 'username or password incorrect';
			}
		} else {
			//TODO error handling
			echo 'form not correct';
			$this->login();
		}
	}

	/**
     * register user
     * @return 
     */
	public function register_user()
	{
		//import helpers, models and librarys
		$this->load->library('form_validation');
		$this->load->helper('formvalidation_helper'); //TODO from config file
		$this->load->model('Auth_Model');

		//set form validations
		$validations = set_register_validations();
		$this->form_validation->set_rules($validations);

		//check if form is correct filled in
		if( $this->form_validation->run() === TRUE ){
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$language = 'english'; //TODO make dynamic

			//check if user doen't exist
			if( $this->Auth_Model->check_register($username) === TRUE ){
				//set auth variables (corresponding to database fields)
				$auth = Array(
					'username'	=> $username,
					'password'	=> $password
				);
				//hash user variables
				$auth = $this->_encode($auth);

				//set user details variables (corresponding to database fields)
				$auth_details = Array(
					'email' 		=> strtolower($this->input->post('email')),
					'initials' 		=> strtolower($this->input->post('initials')),
					'firstname' 	=> ucfirst(strtolower($this->input->post('first_name'))),
					'prefix'		=> strtolower($this->input->post('prefix')),
					'lastname' 		=> ucfirst(strtolower($this->input->post('last_name'))),
					'address' 		=> ucwords(strtolower($this->input->post('address'))),
					'housenumber'	=> $this->input->post('house_number'),
					'addition' 		=> strtoUpper($this->input->post('addition')),
					'zipcode' 		=> strtoUpper($this->input->post('zipcode')),
					'city'			=> ucwords(strtolower($this->input->post('city'))),
					'country' 		=> ucwords(strtolower($this->input->post('country'))),
					'mobile' 		=> $this->input->post('mobile'),
					'company_name' 	=> $this->input->post('company_name'),
					'company_mobile'=> $this->input->post('company_phone'),
					'gender' 		=> strtolower($this->input->post('gender-man')),
					'language' 		=> strtolower($language),
					'username'		=> $auth['username']
				);
				//hash user details variables with username as exeption
				$auth_details = $this->_encode($auth_details, 'username');

				//set new user
				if( $this->Auth_Model->register_auth($auth, $auth_details) === TRUE ){
					//TODO check if auth has payed for client schedular
					//import models
					$this->load->model('Settings_Model');

					//create default database items
					$default_settings = $this->_default_settings();

					//set default database items
					$settings_insert_id = $this->Settings_Model->set_settings($default_settings);
					if( $settings_insert_id >= 0 ){
						$client_schedular_user = Array(
							'cs_username'		=> strtolower($this->_decode($auth_details['firstname'])), //TODO let users choose username for client schedular
							'email'				=> $this->_decode($auth_details['email']), //TODO let users choose email for client schedular
							'company'			=> null, //TODO make dynamic
							'settings_id'		=> $settings_insert_id,
							'auth_username'		=> $auth['username']
						);
						$client_schedular_user = $this->_encode($client_schedular_user, array('settings_id', 'auth_username'));
						//TODO encode client_schedular_user
						if( $this->Auth_Model->register_client_schedular_auth($client_schedular_user) === TRUE ){
							//redirect to login
							redirect('login');
						} else {
							//TODO error handling
							echo 'client schedular user not inserted';
						}
					} else {
						//TODO error handling default database values
						echo 'default database values not correct';
					}
				} else {
					//TODO error handling
					echo 'auth not inserted';
				}
			} else {
				//TODO error handling
				echo 'username exists';
			}
		} else {
			//TODO error handling
			echo 'form not correct';
			$this->register();
		}
	}

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
		redirect(base_url());
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
		$start_times = array(0, 30);
		return Array(
			'appointment_interim' 		=> '00:30:00',
			'appointment_start_times' 	=> json_encode($start_times),
			'appointments_a_day' 		=> 4,
			'redirect_url'				=> base_url('appointment/appointment_made'),
			'time_zone'					=> 2
		);
	}
}
