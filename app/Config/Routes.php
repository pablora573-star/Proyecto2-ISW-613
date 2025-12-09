<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//login
$routes->get('/', 'Login::index');
$routes->get('/login', 'Login::inicio');
$routes->get('/logout', 'Login::logout');
$routes->post('/login/entrar', 'Login::authentication');
//registro usuarios
$routes->get('/registro/pasajero', 'Pasajero::registro');
$routes->get('/registro/chofer', 'Chofer::registro');
$routes->get('/registro/admin', 'Admin::registro');
//Dashboards
$routes->get('/dashboard/chofer', 'Chofer::index');
$routes->get('/dashboard/pasajero', 'Pasajero::index');
$routes->get('/dashboard/admin', 'Admin::index');
//Vehiculo
$routes->get('/vehiculos', 'Vehiculo::index');
$routes->get('/vehiculos/registro', 'Vehiculo::create');
$routes->post('/vehiculos/store', 'Vehiculo::store');
$routes->get('/vehiculos/edit/(:num)', 'Vehiculo::editar/$1');
$routes->post('/vehiculos/update/(:num)', 'Vehiculo::update/$1');
$routes->get('/vehiculos/eliminar/(:num)', 'Vehiculo::delete/$1');
//Ride
$routes->get('/rides/crear', 'Ride::create');
$routes->post('/rides/store', 'Ride::store');
$routes->get('/rides/edit/(:num)', 'Ride::edit/$1');
$routes->post('/rides/update/(:num)', 'Ride::update/$1');
$routes->get('/rides/eliminar/(:num)', 'Ride::delete/$1');
//reservaciones
$routes->get('/mis-reservas', 'Reservas::index');
$routes->get('/mis-reservas/cancelar/(:num)', 'Reservas::cancelar/$1');
$routes->post('/reservar', 'Reservas::reservar');
//Admin
$routes->get('/admin/cambiarEstado/(:num)/(:segment)', 'Admin::cambiar_estado/$1/$2');
$routes->get('/admin/crear', 'Admin::registro');
//Comunes
$routes->post('/user/store', 'Common::store');
$routes->get('/user/edit', 'Common::editar');
$routes->post('/user/update/(:num)', 'Common::update/$1');
$routes->get('/buscar_rides', 'Common::buscarRides');
$routes->get('/crear_reserva/(:num)', 'Common::createReserva/$1');



//Activado de cuentas
$routes->get('/activar/(:any)', 'Activar::index/$1');
$routes->get('/registro/confirmado', function () {
    return view('/common/registration_confirm', ['email' => $_GET['email'] ?? null]);
});
$routes->get('/registro/activado', function () {
    echo "Cuenta activada correctamente"; 
});

