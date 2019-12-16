<?php
/**
* Appointment Model
* 
* Model for
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
class Appointment_Model extends MY_Model {
    public function __construct()
    {
        parent::__construct();

        //database tables
        $this->db_table['event'] = 'event';
        $this->db_table['availability'] = 'availability_slot';
        $this->db_table['availability_user'] = 'availability_per_user';
        $this->db_table['settings'] = 'settings';
        $this->db_table['user'] = 'user';
        $this->db_table['attendee'] = 'attendee';
        $this->db_table['appointment'] = 'appointment';
    }

    /**
     * 
     */
    public function check_user($cs_username)
    {
        $where = Array('cs_username' => $cs_username);
        $result = $this->_db_select($this->db_table['user'], null, null, $where, null, true);
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
    public function get_events(String $cs_username)
    {
        $select = 'title, description';
        $where = Array('user_cs_username' => $cs_username);
        $result = $this->_db_select($this->db_table['event'], $select, null, $where, null, true);
        if( !empty($result) ){
            return $result;
        }
        return NULL;
    }

    /**
     * 
     */
    public function get_event(String $cs_username, String $event_title)
    {
        $select = 'title, description, duration';
        $where = Array('user_cs_username' => $cs_username, 'title' => ucfirst($event_title));
        $result = $this->_db_select($this->db_table['event'], $select, null, $where, null, true);
        if( !empty($result) && isset($result[0]) ){
            return $result[0];
        }
        return NULL;
    }

    /**
     * 
     */
    public function get_available_times(String $cs_username) {
        $available_times = $this->get_availability($cs_username);
        $order = array('start', 'end', 'allDay', 'specific');
        if( !empty($available_times) ){
            foreach ($available_times as $item => $availability) {
                foreach ($availability as $key => $value) {
                    switch ($key) {
                        case 'start':                        
                            $startDate = new DateTime($value);
                            break;
                            
                        case 'end':                        
                            $endDate = new DateTime($value);
                            break;
                        
                        case 'allDay':
                            if( $value === TRUE ){
                                //start at 6
                                $availability->start = $startDate->setTime(6, 0, 0);
                                //ends at 22
                                $availability->end = $endDate->setTime(-2, 0, 0);
                            }
                            unset($availability->allDay);
                            break;
                        
                        case 'specific':
                            if( $value === FALSE ){
                                $availability->start = $startDate->format('w H:i:s');
                                $availability->end = $endDate->format('w H:i:s');
                            } elseif( $value === TRUE ){
                                $availability->start = $startDate->format('Y-m-d H:i:s');
                                $availability->end = $endDate->format('Y-m-d H:i:s');
                            }
                            unset($availability->specific);
                            break;    
                        default:
                        //TODO better error handling, key not found
                            return NULL;
                    }
                }
            }
            return (Object) $available_times;
        }
        //TODO better error handling
        return (Object) Array('Error' => 'No availability from user. Check if username is correct or ask the user if availability is given');
    }

    /**
     * 
     */
    public function get_settings(String $cs_username) {
        $settings_id = $this->get_settings_id($cs_username);
        if( !empty($settings_id) ){
            $select = 'appointments_a_day, appointment_start_times, appointment_interim, redirect_url, time_zone';
            $where = Array('id' => $settings_id);
            $settings = $this->_db_select($this->db_table['settings'], $select, null, $where)->row();

            if( !empty($settings) ){
                return $settings;
            } else {
                //TODO better error handling
                return (Object) Array('Error' => 'No settings');
            }
        } else {
            return (Object) Array('Error' => 'Username not correct');
        }
    }

    /**
     * 
     */
    public function get_linked_calendars(String $cs_username) {
        
    }

    /**
     * 
     */
    public function get_appointments(String $cs_username, String $event_title) {
        $event_id = $this->get_event_id($cs_username, $event_title);

        if( !empty($event_id) ){
            $select = 'date, start_time, end_time';
            $where = Array('event_id' => $event_id);
            $result = $this->_db_select($this->db_table['appointment'], $select, null, $where)->result();
            if( !empty($result) ){
                return $result;
            }
        }
        return NULL;
    }

    /**
     * 
     */
    public function make_appointment(String $cs_username, String $event_title, Object $attendee, Object $appointment) {
        $event_id = $this->get_event_id($cs_username, $event_title);

        if( !empty($event_id) ){
            if( !empty($attendee) && !empty($appointment) ){
                $attendee = $this->set_attendee($attendee);
                if( is_numeric($attendee) ){
                    $data = Array(
                        'date' => $appointment->date,
                        'start_time' => $appointment->start_time,
                        'end_time' => $appointment->end_time,
                        'status' => 'confirm',
                        'event_id' => $event_id,
                        'attendee_id' => $attendee
                    );
                    $output = $this->_db_insert($this->db_table['appointment'], $data);
                    if( $output === TRUE ){
                        return TRUE;
                    } else {
                        return (Object) Array('Error' => "Appointment not made. Something went wrong");
                    }
                } else {
                    return (Object) Array('Error' => $attendee);
                }
            } else {
                return (Object) Array('Error' => 'Attendee and/or appointment not correct');
            }
        } else {
            return (Object) Array('Error' => 'Event not correct');
        }
    }

    /**
     * For dynamic fields in forum
     */
    public function get_attendee_fields()
    {
        //unset id!
    }

    /**
     * 
     */
    private function set_attendee(Object $attendee) {
        $attendee = $this->format_attendee($attendee);
        $attendee_id = $this->get_attendee_id((array) $attendee);
        if( $attendee_id === NULL ){
            if( $this->_db_insert($this->db_table['attendee'], (array) $attendee) === TRUE ){
                return $this->db->insert_id();
            } else {
                return "Attendee not set. Can't make an appointment";
            }         
        }
        //already set
        return $attendee_id;
    }

    private function get_attendee_id(Array $attendee)
    {
        $select = 'id';
        $where = Array('email' => $attendee['email']);
        $result = $this->_db_select($this->db_table['attendee'], null, null, $where, null, true);
        if( isset($result[0]->id) && is_object($result[0]) === TRUE ){
            return $result[0]->id;
        }
        return NULL;
    }

    /**
     * 
     */
    private function get_event_id(String $cs_username, String $event_title)
    {
        $select = 'id';
        $where = Array('user_cs_username' => strtolower($cs_username), 'title' => ucfirst($event_title));
        $result = $this->_db_select($this->db_table['event'], $select, null, $where, null, true);
        if( !empty($result) && isset($result[0]->id) ){
            return $result[0]->id;
        }
        return NULL;
    }

    /**
     * 
     */
    private function format_attendee(Object $info)
    {
        foreach ($info as $key => $value) {
            //format attendee info
            // switch ($key) {
            //     case 'name':
            //         # code...
            //         break;
                
            //     case 'email':

            //         break;

            //     case 'phone':

            //         break;
            // }
            $info->$key = $this->_encode($value);
        }
        return $info;
    }

    /**
     * 
     */
    private function get_availability(String $cs_username)
    {
        $select = 'start, end, specific, all_day';
        $where = Array('user_cs_username' => $cs_username);
        $join = array(
            $this->db_table['availability_user'], 
            $this->db_table['availability_user'].".availability_slot_id = ".$this->db_table['availability'].".id",
            'inner'
        );
        $query = $this->_db_select($this->db_table['availability'], $select, null, $where, $join, true);

        if( !empty($query) ){
            foreach ($query as $row => $availability) {
                $query[$row] = (Object) $this->format_availability((Array) $availability);
            }
            return $query;
        }
        return NULL;
    }

    /**
     * 
     */
    private function format_availability(Array $availability)
    {
        foreach ($availability as $key => $value) {
            switch ($key) {
                //to javascript format
                case 'all_day':
                    $old_key_place = array_search($key, array_keys($availability));
                    if( (int)$value === 0 ){
                        //make sure the key is placed on the same place as the old key
                        $availability = array_slice($availability, 0, $old_key_place, true) +
                        array('allDay' => false) +
                        array_slice($availability, $old_key_place, count($availability) - 1, true);
                    } elseif( (int)$value === 1 ){
                        //make sure the key is placed on the same place as the old key
                        $availability = array_slice($availability, 0, $old_key_place, true) +
                        array('allDay' => true) +
                        array_slice($availability, $old_key_place, count($availability) - 1, true);
                    }
                    unset($availability[$key]);
                    break;
                
                case 'specific':
                    if( (int)$value === 0 ){
                        $availability[$key] = false;
                    } elseif( (int)$value === 1 ){
                        $availability[$key] = true;
                    }
                    break;
            }
        }
        return $availability;
    }

    /**
     * 
     */
    private function get_settings_id(String $cs_username)
    {
        $select = 'settings_id';
        $where = Array('cs_username' => $cs_username);
        $result = $this->_db_select($this->db_table['user'], $select, null, $where, null, true);
        if( isset($result[0]) ){
            if( isset($result[0]->auth_username) ){
                unset($result[0]->auth_username);
            }
            return $result[0]->settings_id;
        }
        unset($result);
        return NULL;
    }
}