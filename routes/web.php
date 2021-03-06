<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$router->get('busca', [
    'as' => 'busca', 'uses' => 'BuscaController@onGet',
]);

$router->get('automovel/{id}', [
    'as' => 'automovel', 'uses' => 'AutomovelController@onGet',
]);

$router->get('marca', [
    'as' => 'marca', 'uses' => 'MarcaController@onGet',
]);