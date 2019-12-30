<?php
/**
* policies
* 
* Controller for the policies pages. The default route is set to this page.
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
 *  Handles the policies page » select the correct application
 */
class Policies extends MY_Controller {
	public function __construct()
	{
		parent::__construct();

		//change page view path
		$this->data['path'] = 'policies';

		//default page info
		$this->pageName = strToLower(get_class($this));
		$this->pageAccess = 'public';
	}

	/**
	 * Handles 
	 * @link /
	 * @return void
	 */
	public function index()
	{	
		$this->privacy_policy();
    }
    
    /**
     * 
     */
    public function privacy_policy()
    {
        ////import helpers and models

		////get data from database

		//set page info
		$pageInfo['pageName'] = strtolower(__FUNCTION__);

		////sort
		
		//render
		$this->_render($pageInfo);
    }
}