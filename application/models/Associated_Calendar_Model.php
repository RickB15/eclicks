<?php
/**
* Associated Calendar Model
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
class Associated_Calendar_Model extends MY_Model {
    public function __construct()
	{
		parent::__construct();
        
        //database tables
        $this->db_table['linked_calendar'] = 'linked_calendar';
	}

	/**
	 * Handles 
	 * @return 
	 */
	public function set_token(String $cs_username, Array $token)
	{
		$token['user_cs_username'] = $cs_username;
		if( $this->_db_insert($this->db_table['linked_calendar'], $token) === TRUE ){
			return TRUE;
		}
		//TODO better error handling
		return 'token not set';
	}
	
	/**
	 * 
	 */
	public function get_token(String $cs_username)
	{
		//TODO let this work for multiple calendars
		$select = 'access_token, token_type, expires_in, refresh_token';
		$where = Array('user_cs_username' => $cs_username);
		$result = $this->_db_select($this->db_table['linked_calendar'], $select, null, $where, null, true);
		if( !empty($result) ){
			return $result[0];
		}
		return NULL;
	}

    /**
	 * Handles 
	 * @return 
	 */
	public function update_token(String $cs_username, Array $token)
	{
		$where = Array('user_cs_username' => $cs_username);
		$this->_db_update($this->db_table['linked_calendar'], $token, $where);
		return TRUE;
	}
	
	/**
	 * Handles 
	 * @return 
	 */
	public function delete_token(String $cs_username)
	{
		$where = Array('user_cs_username' => $cs_username);
		if( $this->_db_delete($this->db_table['linked_calendar'], $where) === TRUE ){
			return TRUE;
		}
		//TODO better error handling
		return 'token not deleted';
    }
}