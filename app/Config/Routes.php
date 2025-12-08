<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->get('/login', 'Login::inicio');
$routes->get('/logout', 'Login::logout');
$routes->get('/buscar_rides', 'Login::buscarRides');
$routes->post('/login/entrar', 'Login::authentication');

$routes->get('/registro/pasajero', 'Pasajero::registro');
$routes->get('/registro/chofer', 'Chofer::registro');
$routes->get('/registro/admin', 'Admin::registro');

$routes->get('/dashboard/chofer', 'Chofer::index');
$routes->get('/dashboard/pasajero', 'Pasajero::index');
$routes->get('/dashboard/admin', 'Admin::index');

$routes->post('/chofer/store', 'Chofer::store');


//rutas de activado de cuentas
$routes->get('/activar/(:any)', 'Activar::index/$1');
$routes->get('/registro/confirmado', function () {
    return view('/common/registration_confirm', ['email' => $_GET['email'] ?? null]);
});
$routes->get('/registro/activado', function () {
    echo "Cuenta activada correctamente"; // puedes mostrar una vista
});

//Ejemlos
/*

//$routes->get('/', 'Student::index');
$routes->get('students', 'Student::index');
$routes->get('students/create', 'Student::create');
$routes->post('students/store', 'Student::store');
$routes->get('students/edit/(:num)', 'Student::edit/$1');
$routes->post('students/update/(:num)', 'Student::update/$1');
//$routes->get('students/inicio', 'Inicio::index');


$routes->get('/', 'Inicio::index');
//$routes->get('login', 'Inicio::index');
//$routes->get('login/inicioCarrera', 'Inicio::inicioCarrera');
//$routes->get('login/inicioStudent', 'Inicio::inicioStudent');

//$routes->get('/', 'Carrera::index');
//$routes->get('carreras', 'Carrera::index');
$routes->get('carreras/create', 'Carrera::create');
$routes->post('carreras/store', 'Carrera::store');
$routes->get('carreras/edit/(:num)', 'Carrera::edit/$1');
$routes->post('carreras/update/(:num)', 'Carrera::update/$1');
$routes->get('carreras/inicio', 'Inicio::index');
// $routes->get('users', 'Users::index');
// $routes->get('users/create', 'Users::create');
// $routes->post('users/store', 'Users::store');
// $routes->get('users/edit/(:num)', 'Users::edit/$1');
// $routes->post('users/update/(:num)', 'Users::update/$1');
// $routes->get('users/delete/(:num)', 'Users::delete/$1');

*/