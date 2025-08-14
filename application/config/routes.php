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
|	https://codeigniter.com/userguide3/general/routing.html
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

// Default route
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Auth routes
$route['login'] = 'auth/index';
$route['auth/login'] = 'auth/login';
$route['register'] = 'auth/register';
$route['logout'] = 'auth/logout';
$route['profile'] = 'auth/profile';
$route['change-password'] = 'auth/change_password';

// Home routes
$route['home'] = 'home/index';

// Vehicle routes
$route['vehicles'] = 'vehicle/search';
$route['vehicle/search'] = 'vehicle/search';
$route['vehicle/details/(:num)'] = 'vehicle/details/$1';

// Booking routes
$route['bookings'] = 'booking/index';
$route['booking/create'] = 'booking/create';
$route['booking/view/(:num)'] = 'booking/view/$1';
$route['booking/cancel/(:num)'] = 'booking/cancel/$1';
$route['booking/payment/(:num)'] = 'booking/payment/$1';

// Vendor routes
$route['vendor'] = 'vendor/dashboard';
$route['vendor/dashboard'] = 'vendor/dashboard';
$route['vendor/apply'] = 'vendor/apply';
$route['vendor/profile'] = 'vendor/profile';
$route['vendor/vehicles'] = 'vendor/vehicles';
$route['vendor/add-vehicle'] = 'vendor/add_vehicle';
$route['vendor/edit-vehicle/(:num)'] = 'vendor/edit_vehicle/$1';
$route['vendor/delete-vehicle/(:num)'] = 'vendor/delete_vehicle/$1';
$route['vendor/drivers'] = 'vendor/drivers';
$route['vendor/add-driver'] = 'vendor/add_driver';
$route['vendor/edit-driver/(:num)'] = 'vendor/edit_driver/$1';
$route['vendor/delete-driver/(:num)'] = 'vendor/delete_driver/$1';
$route['vendor/bookings'] = 'vendor/bookings';
$route['vendor/assign-drivers/(:num)'] = 'vendor/assign_drivers/$1';

// User routes
$route['user/dashboard'] = 'user/dashboard';
$route['user/bookings'] = 'user/bookings';
$route['user/profile'] = 'user/profile';

// Notification routes
$route['notifications'] = 'notification/index';
$route['notification'] = 'notification/index';
$route['notification/mark_as_read/(:num)'] = 'notification/mark_as_read/$1';
$route['notification/mark_all_as_read'] = 'notification/mark_all_as_read';
$route['notification/delete/(:num)'] = 'notification/delete/$1';
$route['notification/delete_all'] = 'notification/delete_all';

// Admin routes
$route['admin'] = 'admin/dashboard';
$route['admin/dashboard'] = 'admin/dashboard';
$route['admin/users'] = 'admin/users';
$route['admin/add-user'] = 'admin/add_user';
$route['admin/edit-user/(:num)'] = 'admin/edit_user/$1';
$route['admin/delete-user/(:num)'] = 'admin/delete_user/$1';
$route['admin/update-user-status/(:num)'] = 'admin/update_user_status/$1';
$route['admin/vendors'] = 'admin/vendors';
$route['admin/view-vendor/(:num)'] = 'admin/view_vendor/$1';
$route['admin/approve-vendor/(:num)'] = 'admin/approve_vendor/$1';
$route['admin/reject-vendor/(:num)'] = 'admin/reject_vendor/$1';
$route['admin/vehicles'] = 'admin/vehicles';
$route['admin/view-vehicle/(:num)'] = 'admin/view_vehicle/$1';
$route['admin/bookings'] = 'admin/bookings';
$route['admin/view-booking/(:num)'] = 'admin/view_booking/$1';
$route['admin/reports'] = 'admin/reports';
$route['admin/export-report'] = 'admin/export_report';
$route['admin/settings'] = 'admin/settings';
$route['admin/audit-logs'] = 'admin/audit_logs';
