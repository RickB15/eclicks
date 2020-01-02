<?php
/**
* Availability Model
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
class Availability_Model extends MY_Model {
    public function __construct()
	{
		parent::__construct();
        
        //database tables
        $this->db_table['availability'] = 'availability_slot';
        $this->db_table['availability_user'] = 'availability_per_user';
    }
    
     /**
     * 
     */
    public function check_availability(String $availability_id)
    {
        $where = Array('availability_id' => $availability_id);
        if( !empty($this->_db_select($this->db_table['availability'], null, null, $where, null, true)) ){
            return TRUE;
        }
        return FALSE;
    }

    /**
	 * Handles 
	 * @return 
	 */
	public function set_availability(String $cs_username, Array $availability_array)
	{
        $inserted_all = true;
        $error = '';
        
        //make sure everything has the right format for in the database
        foreach ($availability_array as $availability) {
            //check if availability id is not in database
            if( $this->check_availability($availability['availability_id']) === FALSE ){
                $availability = $this->format_availability($availability);

                if( $this->_db_insert($this->db_table['availability'], $availability, null, true) === FALSE ){
                    $start_date = new DateTime($availability['start']);
                    $end_date = new DateTime($availability['end']);
                    $error .= "couldn't insert the availability with start time: ".$start_date->format('H:i').
                    " and end time: ".$end_date->format('H:i')."\n";

                    $inserted_all = false;
                } else {
                    $data = Array('user_cs_username' => $cs_username, 'availability_slot_id' => $availability['availability_id']);
                    if( $this->_db_insert($this->db_table['availability_user'], $data, null, true) === FALSE ){
                        //TODO better error handling
                        $error .= "availability is set but couldn't be connected to the user \n";

                        $inserted_all = false;
                    }
                }
            } else {
                //TODO better error handling
                $error .= "availability already in the database \n";

                $inserted_all = false;
            }
        }

        if( $inserted_all === TRUE ){
            return TRUE;
        }

        return $error;
    }

    /**
     * 
     */
    public function get_availability(String $cs_username, Array $availability_id=NULL, Bool $specific=FALSE)
    {
        $select = 'availability_id, group_id, class_names, start, end, all_day, specific';
        $where = Array('user_cs_username' => $cs_username);
        $join = array(
            $this->db_table['availability_user'], 
            $this->db_table['availability_user'].".availability_slot_id = ".$this->db_table['availability'].".availability_id",
            'inner'
        );
        if( $availability_id !== NULL ){
            $where['availability_id'] = $availability_id;
        }
        $query = $this->_db_select($this->db_table['availability'], $select, null, $where, $join, true);
        
        if( !empty($query) ){
            foreach ($query as $row => $availability) {
                $query[$row] = (Object) $this->format_availability((Array) $availability);
                //remove date specific availability from query
                if( $query[$row]->specific !== $specific ){
                    unset($query[$row]);
                }
                unset($query[$row]->specific);
            }
            //reindex key values
            $query = array_values($query);

            //check if by removing date specific availability the query is not empty
            if( !empty($query) ){
                return $query;
            }
            return NULL;
        }
        return NULL;
    }

    /**
     * 
     */
    public function update_availability(Array $availability_array)
    {
        $updated_all = true;
        $error = '';
        
        //make sure everything has the right format for in the database
        foreach ($availability_array as $availability) {
            //check if availability id is in database
            if( $this->check_availability($availability['availability_id']) === TRUE ){
                $availability = $this->format_availability($availability);
                
                $where = Array('availability_id' => $availability['availability_id']);
                if( $this->_db_update($this->db_table['availability'], $availability, $where, true) === FALSE ){
                    $start_date = new DateTime($availability['start']);
                    $end_date = new DateTime($availability['end']);
                    $error .= "couldn't update the availability with start time: ".$start_date->format('H:i').
                    " and end time: ".$end_date->format('H:i')."\n";

                    $updated_all = false;
                }
            } else {
                //TODO better error handling
                $error .= "availability not in the database \n";
                
                $updated_all = false;
            }
        }

        if( $updated_all === TRUE ){
            return TRUE;
        }

        return $error;
    }

    /**
     * 
     */
    public function delete_availability(String $cs_username, Array $availability_array)
    {
        $deleted_all = true;
        $error = '';
        
        //make sure everything has the right format for in the database
        foreach ($availability_array as $availability) {
            //check if availability id is in database
            if( $this->check_availability($availability['availability_id']) === TRUE ){
                $where = Array('user_cs_username' => $cs_username, 'availability_slot_id' => $availability['availability_id']);
                if( $this->_db_delete($this->db_table['availability_user'], $where) === TRUE ){
                    $where = Array('availability_id' => $availability['availability_id']);
                    if( $this->_db_delete($this->db_table['availability'], $where) === FALSE ){
                        $start_date = new DateTime($availability['start']);
                        $end_date = new DateTime($availability['end']);
                        $error .= "couldn't update the availability with start time: ".$start_date->format('H:i').
                        " and end time: ".$end_date->format('H:i')."\n";
                        $deleted_all = false;
                    }
                } else {
                    //TODO better error handling
                    $error .= "username and availability id are not correct";
                    $deleted_all = false;
                }
            } else {
                //TODO better error handling
                $error .= "availability not in the database \n";
                $deleted_all = false;
            }
        }

        if( $deleted_all === TRUE ){
            return TRUE;
        }

        return $error;
    }

    private function format_availability(Array $availability)
    {
        foreach ($availability as $key => $value) {
            switch ($key) {
                //to database format
                case 'groupId':
                    $availability['group_id'] = $value;
                    unset($availability[$key]);
                    break;

                case 'classNames':
                    $availability['class_names'] = json_encode($value);
                    unset($availability[$key]);
                    break;
                
                case 'allDay':
                    if( $value === FALSE ){
                        $availability['all_day'] = 0;
                    } elseif( $value === TRUE ){
                        $availability['all_day'] = 1;
                    }
                    unset($availability[$key]);
                    break;

                //to javascript format
                case 'group_id':
                    $availability['groupId'] = $value;
                    unset($availability['group_id']);
                    break;
                
                case 'class_names':     
                    $availability['classNames'] = json_decode($value);
                    unset($availability['class_names']);
                    break;

                case 'all_day':
                    if( (int)$value === 0 ){
                        $availability['allDay'] = false;
                    } elseif( (int)$value === 1 ){
                        $availability['allDay'] = true;
                    }
                    unset($availability[$key]);
                    break;
                
                case 'specific':
                    //to database format
                    if( $value === FALSE ){
                        $availability[$key] = 0;
                    } elseif( $value === TRUE ){
                        $availability[$key] = 1;
                    }
                    //to javascript format
                    elseif( (int)$value === 0 ){
                        $availability[$key] = false;
                    } elseif( (int)$value === 1 ){
                        $availability[$key] = true;
                    }
                    break;
            }
        }
        return $availability;
    }
}