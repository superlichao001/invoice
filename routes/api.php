<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

$router = app('Dingo\Api\Routing\Router');
$router->group(['version' => config('api.version'), 'prefix' => 'api', 'namespace' => 'App\Http\Controllers\Api'],
    function ($router) {
        $router->group(['prefix' => 'user'], function ($router) {
            $router->post('login', 'UserController@login');//用户登录 - 不需要登录鉴权
        });

        $router->group(['prefix' => 'invoice'], function ($router) {
            $router->post('check', 'InvoiceController@invoiceCheck');//校验发票
        });

    });
