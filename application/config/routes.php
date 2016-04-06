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

$route['default_controller'] = "auth";
$route['404_override'] = '';

/*
*	Auth Routes
*/
$route['login'] = 'auth/login/login_user';
$route['logout'] = 'auth/login/logout_user';
$route['control-panel/(:num)'] = 'auth/control_panel/$1';
// $route['change-password/(:num)'] = 'auth/change_password/$1';
$route['change-password/(:num)/(:any)'] = 'auth/change_password/$1/$2';


/*
*	Reception Routes
*/
$route['reception/all-patients'] = 'reception/patients';
$route['reception/all-patients/(:num)'] = 'reception/patients/$1';
$route['reception/add-patient'] = 'reception/add_patient';
$route['reception/register-other-patient'] = 'reception/register_other_patient';
$route['reception/register-dependant-patient/(:num)'] = 'reception/register_dependant_patient/$1';




/* End of file routes.php */
/* Location: ./application/config/routes.php */

/*
* Focal point routes
*/ 

$route['administration/all-insurance-company'] = 'administration/insurance_company/index';
$route['administration/all-insurance-company/(:num)'] = 'administration/insurance_company/index/$1';
$route['administration/add-insurance-company'] = 'administration/insurance_company/add_insurance_company';
$route['administration/edit-insurance-company/(:num)'] = 'administration/insurance_company/edit_insurance_company/$1';
$route['administration/delete-insurance-company/(:num)'] = 'administration/insurance_company/delete_insurance_company/$1';
$route['administration/activate-insurance-company/(:num)'] = 'administration/insurance_company/activate_insurance_company/$1';
$route['administration/deactivate-insurance-company/(:num)'] = 'administration/insurance_company/deactivate_insurance_company/$1';




$route['dental/save-current-notes/(:num)'] = 'nurse/save_current_notes/$1';
$route['dental/save-new-notes/(:num)'] = 'nurse/save_new_notes/$1';

// $route['administration/deactivate-service-charge/(:num)/(:num2)'] = 'administration/deactivate_service_charge/$1/$2';
