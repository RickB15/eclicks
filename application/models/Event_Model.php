<?php
/**
* Event Model
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
* @author       Rick Blanskma <rickblanksma@gmail.com>
*/
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handles 
 */
class Event_Model extends MY_Model {
    public function __construct()
	{
        parent::__construct();

        //database tables
        $this->db_table['event'] = 'event';
	}

    /**
	 * Handles 
	 * @return 
	 */
	public function set_event(String $cs_username, $event)
	{
        //object is JSON way, array is normal way
        if( is_object($event) ){
            $error = '';
            //format values so it will be correctly stored in the database
            foreach ($event as $key => $value) {
                switch (strtolower($key)) {
                    case 'title':
                        $format = $this->format_title($value);
                        if( $format !== false ){
                            $event->$key = $format;
                        } else {
                            $error .= 'Title is not correct <br>';
                        }
                        break;
                    
                    case 'description':
                        $format = $this->format_description($value);
                        if( $format !== false ){
                            $event->$key = $format;
                        } else {
                            $error .= 'Description is not correct <br>';
                        }
                        break;

                    case 'duration':
                        $format = $this->format_duration($value);
                        if( $format !== false ){
                            $event->$key = $format;
                        } else {
                            $error .= 'Duration is not correct <br>';
                        }
                        break;
                    
                    default:
                        $event->$key = json_encode($value);
                    break;
                }
            }
            
            $event->user_cs_username = $cs_username;

            if( empty($error) ){
                if( $this->_db_insert($this->db_table['event'], (Array) $event) === TRUE ){
                    return TRUE;
                } else {
                    //TODO better error handling
                    return "Something went wrong while inserting";
                }
            } else {
                return $error;
            }

        } elseif( isArray($event) ){
            $event['user_cs_username'] = $cs_username;
            //TODO make this functional (normal way)
            if( $this->_db_insert($this->db_table['event'], $event) ){
                return TRUE;
            }
        }
    
        return FALSE;
    }

    /**
     * 
     */
    public function get_events(String $cs_username, String $fields=NULL)
    {
        $where = Array('user_cs_username' => $cs_username);
        $result = $this->_db_select($this->db_table['event'], $fields, null, $where, null, true);
        if( !empty($result) ){
            foreach ($result as $row => $value) {
                 unset($result[$row]->user_cs_username);
            }           
            return $result;
        }
        return NULL;
    }

    /**
     * 
     */
    public function get_event(String $cs_username, String $eventId, Array $fields=NULL)
    {
        $where = Array('user_cs_username' => $cs_username, 'id' => $eventId);
        $eventObject = $this->_db_select($this->db_table['event'], $fields, null, $where, null, true);
        
        if( !empty($eventObject) && isset($eventObject[0]) ){
            $event = (Array) $eventObject[0];
            
            //remove username from fields
            array_pop($event);

            return $event;
        }

        return NULL;
    }

    /**
     * @param event {Array or Object} contains
     * return boolean
     */
    public function update_event(String $cs_username, $event)
    {
        //object is JSON way, array is normal way
        if( is_object($event) ){
            $where = Array('user_cs_username' => $cs_username, 'id' => $event->id);
            unset($event->id);

            $error = '';
            //format values so it will be correctly stored in the database
            foreach ($event as $key => $value) {
                switch (strtolower($key)) {
                    case 'title':
                        $format = $this->format_title($value);
                        if( $format !== false ){
                            $event->$key = $format;
                        } else {
                            $error .= 'Title is not correct <br>';
                        }
                        break;
                    
                    case 'description':
                        $format = $this->format_description($value);
                        if( $format !== false ){
                            $event->$key = $format;
                        } else {
                            $error .= 'Description is not correct <br>';
                        }
                        break;

                    case 'duration':
                        $format = $this->format_duration($value);
                        if( $format !== false ){
                            $event->$key = $format;
                        } else {
                            $error .= 'Duration is not correct <br>';
                        }
                        break;
                    
                    default:
                        $event->$key = json_encode($value);
                    break;
                }
            }

            if( empty($error) ){
                if( $this->_db_update($this->db_table['event'], (Array) $event, $where, true) === TRUE ){
                    return TRUE;
                } else {
                    //TODO better error handling
                    return "Something went wrong while inserting";
                }
            } else {
                return $error;
            }

        } elseif( is_array($event) ){
            //TODO make this functional (normal way)
            $where = Array('user_cs_username' => $cs_username, 'id' => $event['id']);
            if( $this->_db_update($this->db_table['event'], $event, $where, true) === TRUE ){
                return TRUE;
            }
        }
    
        return FALSE;
    }

    /**
     * 
     */
    public function delete_event(String $cs_username, String $event_id)
    {
        if( isset($cs_username) && isset($event_id) && !empty($cs_username) && !empty($event_id) ){
            $where = Array('user_cs_username' => $cs_username, 'id' => $event_id);
            if( $this->_db_delete($this->db_table['event'], $where) === TRUE ){
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * 
     */
    private function format_description(String $description)
    {
        if( $description !== strip_tags($description) ){
            return FALSE;
        }

        return ucfirst(trim($description));
    }
    
    /**
     * 
     */
    private function format_duration(Object $duration)
    {
        foreach ($duration as $key => $value) {
            switch (explode('-', $key)[1]) {
                case 'hours':
                    $hours = (string)
                    //add 0 if number is single
                    sprintf("%'.02d", (int) $value);
                    break;
                case 'minutes':
                    $minutes = (String)
                    //add 0 if number is single
                    sprintf("%'.02d",
                        //make the number always 2 decimal (so 5 becomes 50)
                        number_format((int) $value, 2));
                    break;
                case 'seconds':
                    $seconds = (String)
                    //add 0 if number is single
                    sprintf("%'.02d",
                        //make the number always 2 decimal (so 5 becomes 50)
                        number_format((int) $value, 2));
                    break;
                default:
                    return FALSE;
            }
        }
        
        if( isset($seconds) ){
            $new_duration = $hours.':'.$minutes.':'.$seconds;
        } else {
            $new_duration = $hours.':'.$minutes.':'.'00';
        }

        if( $new_duration === '00:00:00' ){
            return FALSE;
        }

        return $new_duration;
    }

    /**
     * 
     */
    private function format_title(String $title)
    {
        if( preg_match('/^[\w\-\_\s]+$/', $title) && strlen($title) < 3 ){
            return FALSE;
        }

        return ucfirst(strtolower(trim($title)));
    }
}