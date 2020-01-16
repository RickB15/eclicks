<?php
/**
* OAuth
* 
* Controller for google API.
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
class OAuth extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
        include APPPATH . 'third_party/Google/Client.php';
        require APPPATH . 'vendor/autoload.php';
    }

    public function index()
    {
        $client = new Google_Client();
        $client->setApplicationName('ClientSchedular');
        $client->setAuthConfig('client_secret_467734047893-55b6llqdgmu74h5v39hia5j7jtdfsn5b.apps.googleusercontent.com.json');
        $client->addScope(Google_Service_Calendar::CALENDAR, Google_Service_Calendar::CALENDAR_READONLY);
        $client->setRedirectUri(base_url(CLIENT_REDIRECT_URL));
        $client->setAccessType("offline");
        $client->setPrompt('select_account consent');
        $client->setIncludeGrantedScopes(true);

        if ($this->_check_session('access_token') === TRUE && !empty($this->_get_session('access_token'))) {
            $client->setAccessToken($this->_get_session('access_token'));
            $redirect_uri = base_url('settings/associated_calendars');
            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
        } else {
            $redirect_uri = base_url(CLIENT_REDIRECT_URL);
            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
        }
    }
    
    public function api_redirect()
    {
        $client = new Google_Client();
        $client->setAuthConfigFile('client_secret_467734047893-55b6llqdgmu74h5v39hia5j7jtdfsn5b.apps.googleusercontent.com.json');
        $client->setRedirectUri(base_url(CLIENT_REDIRECT_URL));
        $client->addScope(Google_Service_Calendar::CALENDAR, Google_Service_Calendar::CALENDAR_READONLY);
        $client->setAccessType("offline");
        $client->setIncludeGrantedScopes(true);

        if (! isset($_GET['code'])) {
            $auth_url = $client->createAuthUrl();
            header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        } else {
            $client->authenticate($_GET['code']);
            $expires = 3600; //1 hour
            $this->_set_session($client->getAccessToken(), 'access_token', $expires);

            //save token to database
            $this->load->model('Associated_Calendar_Model');
            $token = $client->getAccessToken();
            $token_filtered = Array(
                'access_token'  => $token['access_token'],
                'expires_in'    => $token['expires_in'],
                'refresh_token' => (isset($token['refresh_token']) ? $token['refresh_token'] : NULL),
                'scope'         => $token['scope'],
                'token_type'    => $token['token_type'],
                'created'       => $token['created'],
                'token_id'      => (isset($token['token_id']) ? $token['token_id'] : NULL)
            );

            $this->Associated_Calendar_Model->set_token($this->data['cs_user']->cs_username, $token_filtered);

            $redirect_uri = base_url('settings/associated_calendars');
            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
        }
    }

    public function revoke_api()
    {
        $client = new Google_Client();
        $client->setAuthConfigFile('client_secret_467734047893-55b6llqdgmu74h5v39hia5j7jtdfsn5b.apps.googleusercontent.com.json');
        $client->setRedirectUri(base_url(CLIENT_REDIRECT_URL));
        $client->revokeToken($this->_get_session('access_token'));

        //remove token from database
        $this->load->model('Associated_Calendar_Model');
        $this->Associated_Calendar_Model->delete_token($this->data['cs_user']->cs_username);

        $this->_unset_session('access_token');

        $redirect_uri = base_url('settings/associated_calendars');
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }
    
    public function get_client(String $username, String $event_title)
    {
        //TODO not vivible if not call from appointment controller
        $this->load->model('Associated_Calendar_Model');

        $client = new Google_Client();
        $client->setApplicationName('ClientSchedular');
        $client->setAuthConfig('client_secret_467734047893-55b6llqdgmu74h5v39hia5j7jtdfsn5b.apps.googleusercontent.com.json');
        $client->addScope(Google_Service_Calendar::CALENDAR, Google_Service_Calendar::CALENDAR_READONLY);
        $client->setRedirectUri(base_url(CLIENT_REDIRECT_URL));
        $client->setAccessType("offline");
        $client->setPrompt('select_account consent');
        $client->setIncludeGrantedScopes(true);

        //check if token already in database
        $API = $this->Associated_Calendar_Model->get_token($username);
        if(!empty($API)) {
            $client->setAccessToken($API->access_token);
            if(!empty($API->refresh_token)) {
                $client->refreshToken($API->refresh_token);
            }
        }

        $expires = 3600; //1 hour
        $redirect_uri = base_url('appointment/'.url_title($username).'/'.url_title($event_title,'dash',true));
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $this->_set_session(false, 'access_token', $expires);
                redirect($redirect_uri);
            }

            //save token to database
            if( !empty($client->getAccessToken()) ){
                $this->load->model('Associated_Calendar_Model');
                $token = $client->getAccessToken();
                $token_filtered = Array(
                    'access_token'  => $token['access_token'],
                    'expires_in'    => $token['expires_in'],
                    'refresh_token' => (isset($token['refresh_token']) ? $token['refresh_token'] : NULL),
                    'scope'         => $token['scope'],
                    'token_type'    => $token['token_type'],
                    'created'       => $token['created'],
                    'token_id'      => (isset($token['token_id']) ? $token['token_id'] : NULL)
                );
                $this->Associated_Calendar_Model->update_token($username, $token_filtered);
            }
        }
        $this->_set_session($client->getAccessToken(), 'access_token', $expires);

        redirect($redirect_uri);
    }
}