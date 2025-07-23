<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'HomeController::index', ['as' => 'home']);
$routes->get('/sobre', 'SobreController::index', ['as' => 'sobre']);
$routes->get('/login', 'LoginController::index', ['as' => 'login']);
$routes->post('/login', 'LoginController::store', ['as' => 'login.store']);
$routes->get('/login/destroy', 'LoginController::destroy', ['as' => 'login.destroy']);
$routes->get('/noticia/show/(:segment)', 'NoticiaController::show/$1', ['as' => 'noticia.show']);

$routes->group('admin', ['filter' => 'estaLogado'], static function ($routes) {
    $routes->get('', 'AdminController::index', ['as' => 'admin']);
    $routes->get('usuarios', 'UsuariosController::index', ['as' => 'admin.usuarios']);
    $routes->get('noticias', 'NoticiasController::index', ['as' => 'admin.noticias']);
    $routes->get('noticias/show/error', 'NoticiasController::show/error', ['as' => 'admin.noticias.show.error']);
    $routes->get('noticias/show/success', 'NoticiasController::show/success', ['as' => 'admin.noticias.show.success']);
    $routes->get('noticias/create', 'NoticiasController::create', ['as' => 'admin.noticias.create']);
    $routes->post('noticias/store', 'NoticiasController::store', ['as' => 'admin.noticias.store']);
    $routes->get('noticias/destroy/(:segment)', 'NoticiasController::destroy/$1', ['as' => 'admin.noticias.destroy']);
    $routes->get('blog', 'AdminController\Blog::index');
});

$routes->get('/elfinder', 'ElFinderController::index', ['as' => 'elFinder']);
$routes->get('/teste', 'TesteController::index');
