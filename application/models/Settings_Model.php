<?php
/**
* Settings Model
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
class Settings_Model extends MY_Model {
    private $settings_id;

    public function __construct()
	{
        parent::__construct();
        
        //database tables
        $this->db_table['settings'] = 'settings';
	}

    /**
	 * Handles 
	 * @return 
	 */
	public function set_settings(Array $settings)
	{
        if( $this->_db_insert($this->db_table['settings'], $settings) ){
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }

        return FALSE;
    }

    /**
     * 
     */
    public function get_settings(String $settings_id, Array $settings=NULL)
    {
        $where = Array('id' => $settings_id);
        return $this->_db_select($this->db_table['settings'], $settings, null, $where)->row();
    }

    /**
     * @param settings {Array or Object} contains
     * return boolean
     */
    public function update_settings(String $settings_id, $settings)
    {
        $this->settings_id = $settings_id;
        //object is JSON way, array is normal way
        if( is_object($settings) ){
            $function_name = 'update_'.preg_replace('/\d/', '', str_replace('-', '_', $settings->fieldId));
            $output = $this->$function_name($settings->value);
            if( $output === TRUE ){
                return TRUE;
            } elseif( isset($output['Error']) ){
                return $output['Error'];
            }
        } elseif( isArray($settings) ){
            $where = Array('id' => $settings_id);
            //TODO make this functional (normal way)
            if( $this->_db_update($this->db_table['settings'], $settings, $where) ){
                return TRUE;
            }
        }
    
        return FALSE;
    }

    /**
     * 
     */
    private function update_interim(String $setting)
    {
        $hours = (string)
        //add 0 if number is single
        sprintf("%'.02d",
            //take only rounded number
            explode('.', (string) $setting)[0]
        );
        $minutes = (string) 
        //add 0 if number is single
        sprintf("%'.02d",
            //round number to 2 decimals
            round(
                //take only decimal number
                explode('.',
                    //make the number allways 2 decimal (so 5 becomes 50)
                    number_format($setting, 2))[1]
                        //make minutes (.5 becomes 30 minutes)
                        /100*60)
        );

        $new_interim = Array('appointment_interim' => $hours.':'.$minutes.':'.'00');
        $where = Array('id' => $this->settings_id);
        if( $this->_db_update($this->db_table['settings'], $new_interim, $where) === TRUE ){
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 
     */
    private function update_redirect(String $setting)
    {
        $new_redirect = Array('redirect_url' => (string) trim($setting));
        $where = Array('id' => $this->settings_id);
        if( $this->_db_update($this->db_table['settings'], $new_redirect, $where) === TRUE ){
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 
     */
    private function update_start_times(String $setting)
    {
        $old_start_times = json_decode($this->get_settings($this->settings_id)->appointment_start_times);

        if( in_array((int) $setting, $old_start_times) ){
            if( empty(array_diff($old_start_times, [(int) $setting])) ){
                //TODO return error that gives feedback that there must be a starting time
                return Array('Error' => "starting times can't be empty");
            }
            $new_start_times = Array('appointment_start_times' => json_encode(array_values(array_diff($old_start_times, [(int) $setting]))));
        } else {
            array_push($old_start_times, (int) $setting);
            $new_start_times = Array('appointment_start_times' => json_encode($old_start_times));
        }

        $where = Array('id' => $this->settings_id);
        if( $this->_db_update($this->db_table['settings'], $new_start_times, $where) === TRUE ){
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 
     */
    private function update_time_zone(String $setting)
    {
        $new_time_zone = Array('time_zone' => (int) $setting);
        $where = Array('id' => $this->settings_id);
        if( $this->_db_update($this->db_table['settings'], $new_time_zone, $where) === TRUE ){
            return TRUE;
        }
        return FALSE;
    }
}