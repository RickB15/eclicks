<?php
/**
* Auth Model
* 
* Model for the login page.
* 
* PHP Version 7.3.5
* 
* LICENSE: 
*
* @category     Eclicks
* @package      User
* @subpackage   Model
* @copyright    -
* @license      -
* @version      0.0.1 
* @link         -
* @since        File available since Release 0.0.0
* @author       Rick Blanskma <rickblanksma@gmail.com>
*/
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH.'models/user_model.php';

/**
 * Handles the auth database » check user login and register
 */
class Auth_Model extends User_Model {
	public function __construct()
	{
		parent::__construct();

        //database tables
        $this->db_table['auth'] = 'auth';
        $this->db_table['auth_details'] = 'auth_details';
        $this->db_table['auth_client_schedular'] = 'user';
	}

    /**
	 * Handles if given username and password are correct
     * @param username {string} contains the given username from {@auth/login_user}
     * @param password {string} contains the given password from {@auth/login_user}
	 * @return boolean
	 */
	public function check_login(String $username, String $password)
	{
		//TODO login by email
		//if user exist in database
		if( $this->check_user($this->db_table['auth'], $username) === TRUE ){
			if( $this->check_password($username, $password) === TRUE ){
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Handles if given username doesn't exist yet
     * @param username {string} contains the given username from {@auth/register_user}
	 * @return boolean
	 */
	public function check_register(String $username)
	{
        //if user exist in database
		if( $this->check_user($this->db_table['auth'], $username) === FALSE ){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Handles the user with details are set to the database
     * @param auth {array} contains the given username from {@auth/register_user}
     * @param auth_details {array} contains the given password from {@auth/register_user}
	 * @return boolean
	 */
	public function register_auth(Array $auth, Array $auth_details)
	{
		if( !empty($auth) && !empty($auth_details) ){
			$data = Array(
				'user' =>
					Array(
						'table' => $this->db_table['auth'],
						'data' 	=> $auth
					),
				'details' =>
					Array(
						'table'	=> $this->db_table['auth_details'],
						'data'	=> $auth_details
					)
			);
			if( $this->set_new_user($data) === TRUE ){
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * 
	 */
	public function register_client_schedular_auth(Array $auth)
	{
		if( !empty($auth) ){
			$data = Array(
				'user' =>
					Array(
						'table'	=> $this->db_table['auth_client_schedular'],
						'data'	=> $auth
					)
			);
			if( $this->set_new_user($data) === TRUE ){
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * 
	 */
	public function set_language(String $language, String $username)
	{
		switch (strtolower($language)) {
			case 'nl':
			case 'dutch':
				$language = 'dutch';
				break;
			
			default:
				$language = 'english';
				break;
		}
		$data = Array('language' => $this->_encode($language));
		if( $this->update_user_details($this->db_table['auth_details'], $username, $data) === TRUE ){
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * 
	 */
	public function auth_details($username)
	{
		return $this->get_user_details($this->db_table['auth_details'], $username);
	}

	/**
	 * 
	 */
	public function auth_settings_id($username)
	{
		//TODO error handling if null
        return $this->get_settings_id($this->db_table['auth_client_schedular'], $username);
	}

	/**
	 * 
	 */
	public function get_cs_user($username)
	{
		$select = 'cs_username, email, settings_id, company';
		return $this->get_application_user($this->db_table['auth_client_schedular'], $username, $select);
	}

    /**
	 * Check if give password is the same as database password
     * @param username {string} contains the given username from {@this->check_login}
     * @param password {string} contains the given password from {@this->check_login}
	 * @return boolean
	 */
	private function check_password(String $username, String $password)
	{
		if( !empty($username) && !empty($password) ){
            if( $this->check_user_password($this->db_table['auth'], $username, $password) ){
				return TRUE;
			}
		}
		return FALSE;
	}
}