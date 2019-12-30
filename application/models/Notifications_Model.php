<?php
/**
* Notifications Model
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
class Settings_Model extends MY_Model {

    public function __construct()
	{
        parent::__construct();
        
        //database tables
        $this->db_table['notifications'] = 'notifications';
	}

    /**
	 * Handles 
	 * @return 
	 */
	public function set_notifications(Array $name)
	{
        if( $this->_db_insert($this->db_table['notifications'], $name) ){
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }

        return FALSE;
    }
}