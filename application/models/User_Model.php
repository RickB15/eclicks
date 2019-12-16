<?php
/**
* User Model
* 
* Model for the login page.
* 
* PHP Version 7.3.5
* 
* LICENSE: 
*
* @category     Eclicks
* @package      My
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
 * Handles the auth database » check user login and register
 */
class User_Model extends MY_Model {

	public function __construct()
	{
        parent::__construct();

        //database tables
        $this->db_table['user_applications'] = 'apps_per_auth';
	}

/*
| -------------------------------------------------------------------
|  Complete user
| -------------------------------------------------------------------
*/
    /**
	 * Set new user
     * @param user {array} contains the given username and password from {@auth/register}
     * @param user_details {array} contains the given details from {@auth/register}
	 * @return boolean
	 */
	protected function set_new_user(Array $data)
	{
        foreach ($data as $key => $array) {
            switch( $key ){
                case 'user':
                    if( $this->set_user($array['table'], $array['data']) === FALSE ){
                        return FALSE;
                    }
                    break;
                case 'details':
                    if( $this->set_user_details($array['table'], $array['data']) === FALSE ){
                        $this->delete_user($array['table'], $array['data']['username']);
                        return FALSE;
                    }
                    break;
                default:
                    return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * 
     */
    protected function delete_complete_user($username)
    {
        //** Client Schedular user */
        //delete tables user, settings, availability, auth, auth_details

        //** Normal user */
        //delete tables auth, auth_details
    }


/*
| -------------------------------------------------------------------
|  User
| -------------------------------------------------------------------
|
| **navigation**
| @set
| @get
| @update
| @delete
| 
*/
/*
| -------------------------------------------------------------------
|  Set user
| -------------------------------------------------------------------
*/
    /**
	 * Set user
	 * @return boolean
	 */
	protected function set_user(String $db_table, Array $user)
	{
        if( $this->_db_insert($db_table, $user) ){
            return TRUE;
        }
        return FALSE;
    }

    /**
	 * Set new user username
	 * @return boolean
	 */
	private function set_user_username()
	{
        //TODO set new username
        return FALSE;
    }

    /**
	 * Set new user password
	 * @return boolean
	 */
	private function set_user_password()
	{
       //TODO set new password
       return FALSE;
    }
/*
| -------------------------------------------------------------------
|  Get user
| -------------------------------------------------------------------
*/
    /**
     * 
     */
    protected function check_user(String $db_table, String $username)
    {
        $where = Array('username' => $username);
        $result = $this->_db_select($db_table, null, null, $where, null, true);
        if( isset($result[0]) && is_object($result[0]) === TRUE ){
            unset($result);
            return TRUE;
        }
        unset($result);
        return FALSE;
    }

    /**
     * 
     */
    protected function get_application_user(String $db_table, String $username, String $select)
    {
        $where = Array('auth_username' => $username);
        $result = $this->_db_select($db_table, $select, null, $where, null, true);
        if( isset($result[0]) ){
            if( isset($result[0]->auth_username) ){
                unset($result[0]->auth_username);
            }
            return $result[0];
        }
        unset($result);
        return NULL;
    }

    /**
     * 
     */
    protected function check_user_password(String $db_table, String $username, String $password)
    {
        $where = Array('username' => $username, 'password' => $password);
        $result = $this->_db_select($db_table, null, null, $where, null, true);
        if( isset($result[0]) && is_object($result[0]) === TRUE ){
            unset($result);
            return TRUE;
        }
        unset($result);
        return FALSE;
    }
/*
| -------------------------------------------------------------------
|  Update user
| -------------------------------------------------------------------
*/
    /**
     * 
     */
    protected function update_user(String $db_table, String $username)
    {
        return FALSE;
    }

/*
| -------------------------------------------------------------------
|  Delete user
| -------------------------------------------------------------------
*/
    /**
     * 
     */
    protected function delete_user(String $db_table, String $username)
    {
        $where = Array('username' => $username);
        if( $this->_db_delete($db_table, $where) ){
            return TRUE;
        }
        return FALSE;
    }


/*
| -------------------------------------------------------------------
|  User Details
| -------------------------------------------------------------------
|
| **navigation**
| @set
| @get
| @update
| @delete
| 
*/
/*
| -------------------------------------------------------------------
|  Set user details
| -------------------------------------------------------------------
*/
    /**
	 * Set new user details
     * @param user_details {array} contains the given details
	 * @return boolean
	 */
	protected function set_user_details(String $db_table, Array $user_details)
	{
        if( $this->_db_insert($db_table, $user_details) ){
            return TRUE;
        }
        return FALSE;
    }
/*
| -------------------------------------------------------------------
|  Get user details
| -------------------------------------------------------------------
*/
    /**
     * 
     */
    protected function get_user_details(String $db_table, String $username)
    {
        $where = array('username' => $username);
        $result = $this->_db_select($db_table, null, null, $where, null, true);
        if( isset($result[0]) && is_object($result[0]) === TRUE ){
            return $result[0];
        }
        return NULL;
    }
/*
| -------------------------------------------------------------------
|  Update user details
| -------------------------------------------------------------------
*/
    /**
     * 
     */
    protected function update_user_details(String $db_table, String $username, Array $user_details)
    {
        $where = Array('username' => $username);
        if( $this->_db_update($db_table, $user_details, $where, true) === TRUE ){
            return TRUE;
        }        
        return FALSE;
    }

/*
| -------------------------------------------------------------------
|  Delete user details
| -------------------------------------------------------------------
*/
    /**
     * 
     */
    protected function delete_user_details(String $db_table, String $username)
    {
        return FALSE;
    }


/*
| -------------------------------------------------------------------
|  User applications
| -------------------------------------------------------------------
|
| **navigation**
| @set
| @get
| @update
| @delete
| 
*/
/*
| -------------------------------------------------------------------
|  Set user applications
| -------------------------------------------------------------------
*/
    /**
     * Handles
	 * @param username {string} contains
	 * @param app_name {string} contains
	 * @return
     */
    protected function set_user_applications(String $username, String $app_name)
    {

    }
/*
| -------------------------------------------------------------------
|  Get user applications
| -------------------------------------------------------------------
*/
    /**
	 * Handles
	 * @param username {string} contains
	 * @return
	 */
	protected function get_user_applications(String $username)
	{
		// if( $this->_db_select() ){
            
        // }

        return NULL;
    }
/*
| -------------------------------------------------------------------
|  Update user applications
| -------------------------------------------------------------------
*/
    /**
	 * Handles
	 * @param username {string} contains
	 * @param app_name {string} contains
	 * @param new_app_name {string} contains
	 * @return
	 */
	protected function update_user_applications(String $username, String $app_name, String $new_app_name)
	{
		
    }
/*
| -------------------------------------------------------------------
|  Delete user applications
| -------------------------------------------------------------------
*/
    /**
	 * Handles
	 * @param username {string} contains
	 * @param app_name {string} contains
	 * @return
	 */
	protected function delete_user_applications(String $username, String $app_name)
	{
		
    }
}