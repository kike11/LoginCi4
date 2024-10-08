<?php

use App\Controllers\Users;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Login::index');
$routes->post('auth', 'Login::auth');
$routes->get('logout', 'Login::logout');


$routes->get('register', 'Users::index');
$routes->post('register','Users::create');

$routes->get('activate-user/(:any)', 'Users::activateUser/$1');

$routes->get('password-request', 'Users::requestPasswordForm');
$routes->post('password-email', 'Users::SendResetlinkEmail');

$routes->get('password-reset/(:any)', 'Users::resetPasswordForm/$1');
$routes->post('password/reset/', 'Users::resetPassword');

//ejemplo de filtro individual sin haber creado un grupo
//$routes->get('home', 'Home::index',['filter' => 'auth']);

$routes->group('/',['filter'=>'auth'],function($routes){
    $routes->get('home', 'Home::index');
});

