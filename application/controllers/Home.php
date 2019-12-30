<?php
/**
* Home
* 
* Controller for the home page. The default route is set to this page.
* 
* PHP Version 7.3.5
* 
* LICENSE: 
*
* @category     Eclicks
* @package      MY
* @subpackage   Controller
* @copyright    -
* @license      -
* @version      0.0.1 
* @link         http://localhost:8383/
* @since        File available since Release 0.0.0
* @author       Rick Blanksma <rickblanksma@gmail.com>
*/
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  Handles the home page » select the correct application
 */
class Home extends MY_Controller {
	public function __construct()
	{
		parent::__construct();

		//default page info
		$this->pageName = strToLower(get_class($this));
		$this->pageAccess = 'private';
	}

	/**
	 * Handles page info and database data to render an home.index view
	 * @link /
	 * @return render
	 */
	public function index()
	{	
		////import helpers and models

		////get data from database

		//set page info
		$pageInfo['pageName'] = $this->pageName;
		$pageInfo['segment'] = strtolower(__FUNCTION__);

		////sort
		
		//render
		$this->_render($pageInfo);
	}
}
