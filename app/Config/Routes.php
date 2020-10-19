<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'Home::index');

// $routes->get('/api', 'Api\Blog::index');
// $routes->get('/api/(:any)', 'Api\Blog::$1');
// $routes->add('/api/(:any)', 'Api\Blog::$1');

$routes->get('/api/posts/(:any)', 'Api\Posts::$1');
$routes->add('/api/posts/(:any)', 'Api\Posts::$1');

$routes->get('/api/users/(:any)', 'Api\Users::$1');
$routes->add('/api/users/(:any)', 'Api\Users::$1');

// $routes->get('/api/files', 'Api\files::index');

$routes->get('/api/files/(:any)', 'Api\Files::$1');
$routes->add('/api/files/(:any)', 'Api\Files::$1');

$routes->get('/api/settings/(:any)', 'Api\Settings::$1');
$routes->add('/api/settings/(:any)', 'Api\Settings::$1');

$routes->get('/api/comments/', 'Api\Comments::index');
$routes->add('/api/comments/', 'Api\Comments::index');
$routes->add('/api/comments/(:any)', 'Api\Comments::index/$1');

// $routes->add('/api/users', 'Api\Users::$1');
// $routes->add('/api/comments', 'Api\Comments::$1');
// $routes->add('/api/(:any)', 'Api\Blog::$1');


$routes->get('/templates/(:any)', 'Template\View::$1');

$routes->get('/(:any)', 'Home::redirect/$1');


/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
