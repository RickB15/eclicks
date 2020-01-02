<?php
/**
* Settings
* 
* Controller for the settings page.
* 
* PHP Version 7.3.5
* 
* LICENSE: 
*
* @category     Client Schedular
* @package      MY
* @subpackage   Controller
* @copyright    -
* @license      -
* @version      0.0.1 
* @link         http://localhost:8383/settings
* @since        File available since Release 0.0.0
* @author       Rick Blanksma <rickblanksma@gmail.com>
*/
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Cron extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cron_Model');
    }
    
    /**
     * This function is used to send emails to attendees automatically
     * This function is called by cron job once every 10 minutes
     */
    public function updateMail()
    {            
        // is_cli_request() is provided by default input library of codeigniter
        // if($this->input->is_cli_request())
        // {
            //send email
            $this->Cron_Model->send_email();
        // }
        // else
        // {
        //     show_404();
        // }
    }

    /**
     * This function is used to send sms to attendees automatically
     * This function is called by cron job once every 10 minutes
     */
    public function updateSms()
    {            
        // is_cli_request() is provided by default input library of codeigniter
        if($this->input->is_cli_request())
        {            
            $this->load->model('Auth_Model');
            $this->Cron_Model->send_sms();
        }
        else
        {
            show_404();
        }
    }
}