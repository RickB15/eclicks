<?php
/**
* Share
* 
* Controller for the share & publish page.
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
* @link         http://localhost:8383/share
* @since        File available since Release 0.0.0
* @author       Rick Blanksma <rickblanksma@gmail.com>
*/
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * !Client Schedular application!
 * Handles the share & publish page » embeded code for users own website
 */
class Share extends MY_Controller {
	public function __construct()
	{
		parent::__construct();

		//change page view path
		$this->application['appName'] = 'Client Schedular';

		
		//default page info
		$this->pageName = strToLower(get_class($this)).' & publish';
		$this->pageAccess = 'private';

		if( $this->_check_auth() === FALSE ){
			// redirect(base_url('login'));
			redirect($this->config->item('bizz_url_ui'));
		}
	}

	/**
	 * Handles page info and database data to render an share.index view
	 * @return render
	 */
	public function index()
	{	
		//import helpers and models
		$this->load->model('Event_Model');

		//get data from database
		$pageData['events'] = $this->Event_Model->get_events($this->data['cs_user']->cs_username, 'title');

		//set page info
		$pageInfo['pageName'] = $this->pageName;
		$pageInfo['segment'] = strtolower(__FUNCTION__);

		//set custom js, css and/or font files
		$js = Array('share.js' => 'end');
		//// $css = array();
		//// $fonts = array();

		//import custom js, css and/or font files
		$this->files['js'] = $js;
		//// $this->files['css'] = $css;
		//// $this->files['fonts'] = $fonts;

		////sort
		
		//render
		$this->_render($pageInfo, $pageData);
	}
}
