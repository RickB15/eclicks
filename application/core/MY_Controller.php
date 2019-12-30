<?php
/**
* MY_Controller
* 
* PHP Version 7.3.5
* 
* LICENSE: 
*
* @category     Eclicks
* @package      CI
* @subpackage   Controller
* @copyright    -
* @license      -
* @version      0.0.1 
* @link         -
* @since        File available since Release 0.0.0
* @author       Rick Blanskma <rickblanksma@gmail.com>
*/
defined('BASEPATH') OR exit('No direct script access allowed');
use Jenssegers\Blade\Blade;

/**
 * Handels 
 */
class MY_Controller extends CI_Controller {
    //* GLOBAL variables */
    ## application
    protected $application = Array();
    protected $data = Array();
    protected $files = Array();

    ## folder names
    protected $views = APPPATH . 'views';
    protected $cache = APPPATH . 'cache';

    ## blade
    protected $blade;

	public function __construct()
	{
        parent::__construct();

        //connect blade to the "views" and "cache" folder. For more info see https://github.com/jenssegers/blade
        $this->blade = new Blade($this->views, $this->cache);

        //default application settings
        $this->application['appName'] = str_replace(' ', '_', strtolower($this->config->item('application_name')));

        //set default page info
        $this->data['meta_charset'] = strtolower($this->config->item('charset'));
        $this->data['meta_keywords'] = 'Client Schedular';
        $this->data['meta_description'] = 'Client Schedular is an application that lets you make an appointment';
        $this->data['meta_author'] = 'Rick Blanksma';

        //set default page "views" path
        $this->data['path'] = 'pages';

        //set user
        $this->_set_auth();

        //load language
        if( isset($this->data['language']) ){
            $this->lang->load('db_error_messages_lang', $this->data['language']);
            $this->lang->load('default_lang', $this->data['language']);
            $this->lang->load('error_message_lang', $this->data['language']);
            $this->lang->load('form_validation_lang', $this->data['language']);
        } else {
            $this->data['language'] = 'english';
            $this->lang->load('db_error_messages_lang', 'english');
            $this->lang->load('default_lang', 'english');
            $this->lang->load('error_message_lang', 'english');
            $this->lang->load('form_validation_lang', 'english');
        }
    }

/*
| -------------------------------------------------------------------
|  Render (different) views
| -------------------------------------------------------------------
*/
    /**
     * render default view
     * @global
     * @param pageInfo {array} contains info from controllers/* 
     * @param pageData {array} contains data from controllers/* that provides values or arrays (from the database)
     * @return view or redirect to login
     */
    public function _render(Array $pageInfo, Array $pageData = null)
    {
        //check if page is not public » user needs to be logged in
        if( $this->data['path'] !== 'auth' ){
        //don't check auth path because you get a loop
            if( isset($pageInfo['access']) ){
            //check if class page access is overwritten in a function » 
            //*use "$pageInfo['access'] = 'public';" under "//set page info" in a function if you want to do this
                if( $pageInfo['access'] !== 'public' ) {
                //check if function page info is public
                    if( $this->_check_auth() !== TRUE ){
                    //check if user is loged in
                        //if not » redirect
                        redirect('login');
                    }
                }
            } elseif( isset($this->pageAccess) ){
                $pageInfo['access'] = $this->pageAccess;
                if( $this->pageAccess !== 'public' ){
                    //check if class page access is public
                    if( $this->_check_auth() !== TRUE ){
                    //check if user is loged in
                        //if not » redirect
                        redirect('login');
                    }
                }
            }
            //for redirect to previous page
            $this->_set_session(current_url(), 'referred_from');
        }

        //"views" path = path/application/name/segment
        $pagePath = "{$this->data['path']}/";
        if( str_replace(' ', '_', strtolower($this->application['appName']))
            === str_replace(' ', '_', strtolower($this->config->item('application_name'))) ){
            //if application is Eclicks than don't add application name to {@pagepath}
            //* Everything that isn't Eclicks should have his own application map inside "views"
            $pagePath .= "";
        } else {
            //replace spaces with underscore
            $pagePath .= str_replace(' ', '_', strtolower($this->application['appName'])).'/';
        }
        //replace strange characters with underscore
        $pagePath .= str_replace(' ', '', str_replace(str_split('!@#$%^&*?'), '_', $pageInfo['pageName']));
        if( isset($pageInfo['segment']) ) {
            $pagePath .= "/{$pageInfo['segment']}";
        }

        //error "views" path handler
        $viewPath = $this->views.'/'.$pagePath.'.blade.php';
        if( !file_exists($viewPath) && filesize($viewPath) > 0 ){
            show_404();
        }

        //remove user data from view
        unset($this->data['user']);
        if( $this->_check_auth() === TRUE ){
            $this->data['cs_username'] = $this->_decode($this->data['cs_user']->cs_username);
            unset($this->data['cs_user']);
        }

        //blade render, if {@pageData} is empty give empty array. For more info see https://github.com/jenssegers/blade
        echo $this->blade->render(
            $pagePath,
            array_merge(
                $pageInfo,
                $this->data,
                $this->application,
                $this->files
            ),
            empty($pageData) ? array() : $pageData
        );
    }
    
    /**
     * ! This function is not used !
     * render development view
     * @global
     * @param pageInfo {array} contains info from controllers/* 
     * @param pageData {array} contains data from controllers/* that provides values or arrays (from the database)
     * @return view
     */
    public function _render_dev(Array $pageInfo, Array $pageData = null)
    {
        //redirect to default render
        $this->_render($pageInfo, $pageData);
    }

/*
| -------------------------------------------------------------------
|  Sessions
| -------------------------------------------------------------------
|
|   @link https://codeigniter.com/user_guide/libraries/sessions.html
|
*/
    /**
     * Set session or themp session (add expire) with or without name
     * @param value {array or string} contains the data that needs to
     * be stored in a session
     * @param name {string} contains the name for the session item
     * @param expire {interture} contains the time until the session
     * expires (in seconds!)
     * @return session {boolean}
     */
    protected function _set_session($value, String $name=NULL, Int $expire=NULL)
    {
        //import session library
        $this->load->library('session');

        //set session
        if( $expire === NULL ){
            empty($name)
            ? $this->session->set_userdata($value)
            : $this->session->set_userdata($name, $value);
        } else {
            if( is_array($value) && empty($name) ){
                $this->session->set_tempdata($value, NULL, $expire);
            } else {
                empty($name)
                ? $this->session->set_tempdata($value, $expire)
                : $this->session->set_tempdata($name, $value, $expire);
            }
        }
        //check if session is set
        if( empty($name) ){
            if( $this->_check_session($value) === TRUE ){
                return TRUE;
            }
        } else {
            if( $this->_check_session($name) === TRUE ){
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Get session
     * @param item {string} contains the item of a session 
     * that needs to be returned
     * @return session {boolean}
     */
    protected function _get_session($item)
    {
        //import session library
        $this->load->library('session');

        //check session
        if( $this->_check_session($item) === TRUE ){
            return $_SESSION[$item];
        }

        return NULL;
    }

    /**
     * Get session
     * @param item {string} contains the item of a session 
     * that needs to be returned
     * @return session {boolean}
     */
    protected function _check_session($item)
    {
        //import session library
        $this->load->library('session');

        //check session
        if( $this->session->has_userdata($item) === TRUE
        || isset($_SESSION[$item]) ){
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Unset session but keeps the session marker for reuse
     * @param item {array or string} contains the item of a session 
     * that needs to be unset
     * @return session {boolean}
     */
    protected function _unset_session($item)
    {
        //import library
        $this->load->library('session');

        //unset session
        if( $this->session->unset_userdata($item) === TRUE
            || $this->session->unset_tempdata($item) ){
            return TRUE;
        }
        
        return FALSE;
    }

    /**
     * Remove session compleetly
     * @return session {boolean}
     */
    protected function _remove_session()
    {
        //import library
        $this->load->library('session');

        //destroy session
        $this->session->sess_destroy();
    }

/*
| -------------------------------------------------------------------
|  Cookies
| -------------------------------------------------------------------
*/
    /**
     * Set cookie
     * @return cookie {boolean}
     */
    protected function _set_cookie()
    {
        
    }

    /**
     * Get cookie
     * @return cookie {boolean}
     */
    protected function _get_cookie()
    {
        
    }

/*
| -------------------------------------------------------------------
|  Encryption
| -------------------------------------------------------------------
*/
    /**
     * Encode item
     * @param item {array or string} contains an item/items that needs
     * te be encoded
     * @param exeptions {array or string} contains an item/items that
     * don't needs to be encoded
     * @return hased_item {array or string}
     */
    protected function _encode($item, $exeptions=null)
    {
        //import library
        $this->load->library('encryption');

        if( is_array($item) ){
            //multiple items needs to be encrypt
            foreach ($item as $key => $value) {
                //TODO nested array encryption
                if( empty($value) && $value !== 0 && $value !== '0' ){
                    //null to make sure encrypt doesn't encrypt this value
                    $item[$key] = null;
                } else {
                    $skip = false;
                    if( !empty($exeptions) ) {
                        if( is_array($exeptions) ){
                            //multiple exeptions
                            foreach($exeptions as $exeption ){
                                if( $key === $exeption ){
                                    //value is in exeptions
                                    $skip = true;
                                }
                            }
                        } elseif( $key === $exeptions ) {
                            //value is exeption
                            $skip = true;
                        }
                        if( $skip === FALSE ){
                            //value isn't in exeptions and needs to be encrypt
                            $item[$key] = $this->encryption->encrypt((string)$value);
                        }
                    } else {
                        //encrypt all values (no exeptions) using encryption library
                        $item[$key] = $this->encryption->encrypt((string)$value);
                    }
                }
            }
        } else {
            //single item needs to be encrypt
            if( empty($item) && $value !== 0 && $value !== '0' ){
                //null to make sure encrypt doesn't encrypt this value
                $item = null;
            } else {
                //encrypt value using encryption library
                $item = $this->encryption->encrypt((string)$item);
            }
        }

        return $item;
    }

    /** 
     * Decode item
     * @param item {array or string} contains an item/items that needs
     * te be decoded
     * @return dehased_item {array or string}
     */
    protected function _decode($item)
    {
        //import library
		$this->load->library('encryption');
		
		//object to array convert
		if( is_object($item) === TRUE ){
			$item = (array) $item;
		}

        if( is_array($item) ){
            foreach($item as $key => $value) {
                //TODO nested array decryption
                if( $this->encryption->decrypt($value) !== FALSE ){
                    $item[$key] = $this->encryption->decrypt($value);
                }
            }
        } else {
            if( $this->encryption->decrypt($item) !== FALSE ){
                $item = $this->encryption->decrypt($item);
            }
        }

        return $item;        
    }

    /**
     * JWT encode
     */
    protected function _jwt_encrypt(Array $data, String $name, Int $expire, String $secret, Bool $encode_data=FALSE, Array $encode_exeptions=NULL)
    {
        //import library
        $this->load->library('JWT');

        if( $encode_data === true ){
            if( !empty($encode_exeptions) ){
                $data = $this->_encode($data, $encode_exeptions);
            } else {
                $data = $this->_encode($data);
            }
        }
        return $this->jwt->encode(
            Array(
                'iss'=> [$data],
                'sub'=> $this->_encode($name),
                'iat'=> date(DATE_ISO8601, strtotime("now")),
                'exe'=> $expire //in seconds
            ),
            $secret
        );
    }

    /**
     * JWT decode
     */
    protected function _jwt_decript(String $token, String $secret, String $sub, Bool $decode_data=FALSE, Array $decode_exeptions=NULL)
    {        
        //import library
        $this->load->library('JWT');

        if( $this->_decode($this->jwt->decode($token, $secret)->sub) === $sub ){
            return $this->jwt->decode($token, $secret)->iss;
        }
    }

/*
| -------------------------------------------------------------------
|  JSON
| -------------------------------------------------------------------
*/
    public function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

/*
| -------------------------------------------------------------------
|  Auth check functions
| -------------------------------------------------------------------
*/
    /**
     * Check if user is logged in with session
     * @return login {boolean}
     */
    public function _check_auth()
    {
        $session = $this->_get_session('auth');
        if( !empty($session) ){
            $token = $this->_decode($session['token']);
            $secret = $this->_decode($session['secret']);
            if( $this->_jwt_decript($token, $secret, 'login')[0] ){
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Need to check auth first
     */
    private function _set_auth()
    {
        $session = $this->_get_session('auth');
        if( !empty($session) ){
            $token = $this->_decode($session['token']);
            $secret = $this->_decode($session['secret']);
            $this->data['user'] = $this->_jwt_decript($token, $secret, 'login')[0];
            if( $this->_check_session('lang') === TRUE ){
                $this->data['language'] = $this->_get_session('lang');
            } else if( isset($this->data['user']->details->language) ){
                $this->data['language'] = $this->_decode($this->data['user']->details->language);
            } else {
                 $this->data['language'] = 'english';
            }
            unset($this->data['user']->details->language);
        }

        //cs means client schedular
        $cs_session = $this->_get_session('client_schedular');
        if( !empty($cs_session) ){
            $this->data['cs_user'] = $cs_session;
        }
    }
}