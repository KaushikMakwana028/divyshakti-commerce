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
$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// API Routes
$route['api/register'] = 'Api/register';
$route['api/login'] = 'Api/login';
$route['api/logout'] = 'Api/logout';
$route['api/get_profile'] = 'Api/get_profile';
$route['api/update_profile'] = 'Api/update_profile';
$route['api/get_category_list'] = 'Api/get_category_list';
$route['api/get_category_detail'] = 'Api/get_category_detail';
$route['api/get_product_list'] = 'Api/get_product_list';

$route['api/get_products_by_category'] = 'Api/get_products_by_category';
$route['api/get_products_by_category/(:num)'] = 'Api/get_products_by_category/$1';

$route['api/get_product_detail'] = 'Api/get_product_detail';
$route['api/get_product_detail/(:num)'] = 'Api/get_product_detail/$1';

// MLM Wallet & Admin Operations
$route['api/add_wallet_money'] = 'Api/add_wallet_money';
$route['api/add_wallet_money/(:num)'] = 'Api/add_wallet_money/$1';
$route['api/get_wallet_balance'] = 'Api/get_wallet_balance';
$route['api/get_wallet_transactions'] = 'Api/get_wallet_transactions';

// Order Operations
$route['api/place_order'] = 'Api/place_order';
$route['api/verify_order_payment'] = 'Api/verify_order_payment';
$route['api/get_orders'] = 'Api/get_orders';

$route['api/get_order_details'] = 'Api/get_order_details';
$route['api/get_order_details/(:num)'] = 'Api/get_order_details/$1';

$route['api/cancel_order'] = 'Api/cancel_order';
$route['api/cancel_order/(:num)'] = 'Api/cancel_order/$1';

// Cart Operations
$route['api/get_cart'] = 'Api/get_cart';
$route['api/get_cart_row'] = 'Api/get_cart_row';
$route['api/get_cart_summary'] = 'Api/get_cart_summary';
$route['api/add_to_cart'] = 'Api/add_to_cart';
$route['api/update_cart_quantity'] = 'Api/update_cart_quantity';
$route['api/remove_from_cart'] = 'Api/remove_from_cart';
$route['api/clear_cart'] = 'Api/clear_cart';

// User Wallet Deposit Request APIs
$route['api/request_wallet_deposit'] = 'Api/request_wallet_deposit';
$route['api/get_deposit_requests'] = 'Api/get_deposit_requests';

// User Address Management APIs
$route['api/get_addresses'] = 'Api/get_addresses';
$route['api/save_address'] = 'Api/save_address';

$route['api/update_address'] = 'Api/update_address';
$route['api/update_address/(:num)'] = 'Api/update_address/$1';

$route['api/delete_address'] = 'Api/delete_address';
$route['api/delete_address/(:num)'] = 'Api/delete_address/$1';

// Web Routes
$route['admin'] = 'Home/admin';
$route['admin/login'] = 'Login/index';
$route['admin/register'] = 'Login/register';
$route['admin/dashboard'] = 'Dashboard/index';
$route['admin/profile'] = 'Profile/index';
$route['admin/categories'] = 'Category/index';
$route['admin/categories/add'] = 'Category/add';
$route['admin/categories/edit/(:num)'] = 'Category/edit/$1';
$route['admin/categories/delete/(:num)'] = 'Category/delete/$1';
$route['admin/products'] = 'Product/index';
$route['admin/products/add'] = 'Product/add';
$route['admin/products/detail/(:num)'] = 'Product/detail/$1';
$route['admin/products/edit/(:num)'] = 'Product/edit/$1';
$route['admin/products/delete/(:num)'] = 'Product/delete/$1';
$route['admin/logout'] = 'Login/logout';

// Admin Member Routes
$route['admin/members'] = 'Member/index';
$route['admin/members/view/(:num)'] = 'Member/view/$1';
$route['admin/members/wallet/(:num)'] = 'Member/add_wallet/$1';

// Admin Order Routes
$route['admin/orders'] = 'Order/index';
$route['admin/orders/detail/(:num)'] = 'Order/detail/$1';
$route['admin/orders/status/(:num)'] = 'Order/update_status/$1';

// Admin Commission Routes
$route['admin/commissions'] = 'Commission/index';
$route['admin/commissions/update'] = 'Commission/update';

// Admin Deposit Requests Routes
$route['admin/deposits'] = 'Deposit/index';
$route['admin/deposits/approve/(:num)'] = 'Deposit/approve/$1';
$route['admin/deposits/reject/(:num)'] = 'Deposit/reject/$1';
