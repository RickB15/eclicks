<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route['login/(.+)'] = 'auth/login/$1';
$route['login'] = 'auth/login';
$route['register'] = 'auth/register';
$route['logout'] = 'auth/logout';
$route['reset_password'] = 'auth/reset_password';
$route['change-language/(:any)'] = 'auth/change-language/$1';

$route['appointment/success'] = 'appointment/success';
$route['appointment/json_get_times/(:any)'] = 'appointment/json_get_times/$1';
$route['appointment/json_get_settings/(:any)'] = 'appointment/json_get_settings/$1';
$route['appointment/json_get_appointments/(:any)/(:any)'] = 'appointment/json_get_appointments/$1/$2';
$route['appointment/json_make_appointment/(:any)/(:any)'] = 'appointment/json_make_appointment/$1/$2';
$route['appointment/appointment_made'] = 'appointment/appointment_made';
$route['appointment/(:any)'] = 'appointment/index/$1';
$route['appointment/(:any)/(:any)'] = 'appointment/index/$1/$2';

$route['general'] = 'settings/general';
$route['availability'] = 'settings/availability';
$route['events'] = 'settings/events';
$route['notifications'] = 'settings/notifications';
$route['associated-calendars'] = 'settings/associated-calendars';