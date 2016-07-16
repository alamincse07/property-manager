<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "admin";
$route['login'] 			= "auth/login";
$route['logout'] 			= "auth/logout";
$route['forgot_password']               = "auth/forgot_password";
$route['register'] 			= "auth/register";
$route['change_password']               = "auth/change_password";
$route['profile']                       = "auth/profile";
$route['404_override']                  = '';

/*----- Front Page -----*/
$route["single/(.*)"]       = 'home/single/$1';
$route["singleb/(.*)"]      = 'home/singleb/$1';
$route["pages/(.*)"]        = 'home/pages/$1';

$route["cat/(:num)"]        = 'home/cat/$1';
$route["cat/(:num)/(:num)"] = 'home/cat/$1/$2';

$route["type/(:num)"]       = 'home/type/$1';
$route["type/(:num)/(:num)"]= 'home/type/$1/$2';

$route["blog"]              = 'home/blog';
$route["blog/(:num)"]       = 'home/blog/$1';

$route["search"]            = 'home/search';
$route["search/(:num)"]     = 'home/search/$1';

$route["contact"]           = 'home/contact';

/* Admin */
$route['admin/agents(/:any)?'] = 'admin_agents$1';
$route['admin/files(/:any)?'] = 'admin_files$1';
/* End of file routes.php */
/* Location: ./application/config/routes.php */