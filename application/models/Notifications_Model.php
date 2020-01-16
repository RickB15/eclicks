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
class Notifications_Model extends MY_Model {
     private $notifications_id;

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
	public function set_notifications(Array $notifications)
	{
        if( $this->_db_insert($this->db_table['notifications'], $notifications) ){
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }

        return FALSE;
    }

    /**
     * 
     */
    public function get_notifications(String $notifications_id, Array $notifications=NULL)
    {
        $where = Array('notifications_id' => $notifications_id);
        return $this->_db_select($this->db_table['notifications'], $notifications, null, $where)->row();
    }

    /**
     * @param settings {Array or Object} contains
     * return boolean
     */
    public function update_notification(String $notifications_id, $notifications)
    {
        $this->notifications_id = $notifications_id;
        //object is JSON way, array is normal way
        if( is_object($notifications) ){
            $function_name = 'update_'.str_replace('-', '_', $notifications->fieldId);
            $output = $this->$function_name($notifications->value);
            if( $output === TRUE ){
                return TRUE;
            } elseif( isset($output['Error']) ){
                return $output['Error'];
            }
        } elseif( isArray($notifications) ){
            $where = Array('notifications_id' => $notifications_id);
            //TODO make this functional (normal way)
            if( $this->_db_update($this->db_table['notifications'], $notifications, $where) ){
                return TRUE;
            }
        }
    
        return FALSE;
    }

    /**
     * 
     */
    private function update_direct_email(String $notification)
    {
        $data = Array('email_direct' => $notification);
        $where = Array('notifications_id' => $this->notifications_id);
        if( $this->_db_update($this->db_table['notifications'], $data, $where) === TRUE ){
            return TRUE;
        }
    }

    /**
     * 
     */
    private function update_24_email(String $notification)
    {
        $data = Array('email_24' => $notification);
        $where = Array('notifications_id' => $this->notifications_id);
        if( $this->_db_update($this->db_table['notifications'], $data, $where) === TRUE ){
            return TRUE;
        }
    }

    /**
     * 
     */
    private function update_10_email(String $notification)
    {
        $data = Array('email_10' => $notification);
        $where = Array('notifications_id' => $this->notifications_id);
        if( $this->_db_update($this->db_table['notifications'], $data, $where) === TRUE ){
            return TRUE;
        }
    }

    /**
     * 
     */
    private function update_cancel_email(String $notification)
    {
        $data = Array('email_cancel' => $notification);
        $where = Array('notifications_id' => $this->notifications_id);
        if( $this->_db_update($this->db_table['notifications'], $data, $where) === TRUE ){
            return TRUE;
        }
    }

    /**
     * 
     */
    private function update_direct_sms(String $notification)
    {
        $data = Array('sms_direct' => $notification);
        $where = Array('notifications_id' => $this->notifications_id);
        if( $this->_db_update($this->db_table['notifications'], $data, $where) === TRUE ){
            return TRUE;
        }
    }

    /**
     * 
     */
    private function update_24_sms(String $notification)
    {
        $data = Array('sms_24' => $notification);
        $where = Array('notifications_id' => $this->notifications_id);
        if( $this->_db_update($this->db_table['notifications'], $data, $where) === TRUE ){
            return TRUE;
        }
    }

    /**
     * 
     */
    private function update_10_sms(String $notification)
    {
        $data = Array('sms_10' => $notification);
        $where = Array('notifications_id' => $this->notifications_id);
        if( $this->_db_update($this->db_table['notifications'], $data, $where) === TRUE ){
            return TRUE;
        }
    }

    /**
     * 
     */
    private function update_cancel_sms(String $notification)
    {
        $data = Array('sms_cancel' => $notification);
        $where = Array('notifications_id' => $this->notifications_id);
        if( $this->_db_update($this->db_table['notifications'], $data, $where) === TRUE ){
            return TRUE;
        }
    }

    /**
     * 
     */
    private function update_email_available(String $notification)
    {
        $data = Array('email_available' => $notification);
        $where = Array('notifications_id' => $this->notifications_id);
        if( $this->_db_update($this->db_table['notifications'], $data, $where) === TRUE ){
            return TRUE;
        }
    }

    /**
     * 
     */
    private function update_sms_available(String $notification)
    {
        $data = Array('sms_available' => $notification);
        $where = Array('notifications_id' => $this->notifications_id);
        if( $this->_db_update($this->db_table['notifications'], $data, $where) === TRUE ){
            return TRUE;
        }
    }
}