<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Login\LoginController@index');
Route::get('/help', 'HomeController@help');
Route::get('/editor/fckeditor', 'HomeController@help');

Route::post('/login1', 'Login\LoginController@login1')->name('auth.login1');
Route::post('/api/catalog/add', 'api\ApiController@catalog_add')->name('api.catalog.add');
Route::post('/api/order/add', 'api\ApiController@order_add')->name('api.order.add');

Route::post('/api/export', 'api\ApiController@export')->name('api.export');

Route::get('/razdel/index', 'RazdelController@index')->name('razdel.index');

Route::get('/export/{file}', function ($file) {
    return view('export', compact('file'));
})->name('export');

Route::get('/help', function () {
    return view('help');
});


//Route::group(['middleware' => 'employee','prefix' => 'module', 'namespace' => 'module', 'as' => 'module.'], function () {
//    Route::post('/catalog/list', 'CatalogController@search')->name('catalog.list');
//    Route::post('/cabinet/list', 'CabinetController@search')->name('cabinet.list');
//    Route::get('/photo/{file}', 'CatalogController@photo')->name('photo');
//    Route::post('/log/search', 'LogController@list')->name('log.search');
//    Route::get('/remont/added', 'RemontController@added')->name('remont.added');
//    Route::get('/remont/add_order', 'RemontController@new_order')->name('remont.add_order');
//
//    Route::get('/remont/archive', 'RemontController@list_archive')->name('remont.archive');
////    Route::get('/peremesh/list_oper', 'PeremeshController@index_oper')->name('peremesh.list_oper');
//    Route::get('/order/list_disp', 'PeremeshController@index_oper')->name('order.list_oper');
//
//    Route::resource('remont', 'RemontController');
//    Route::resource('user', 'UserController');
//    Route::resource('catalog', 'CatalogController');
//    Route::resource('order', 'OrderController');
//    Route::resource('cabinet', 'CabinetController');
//
//});

Route::group(['middleware' => 'admin', 'prefix' => 'option', 'as' => 'option.'], function () {
    Route::get('/{table}', 'OptionController@med_center')->name('list');
    Route::get('/edit/{id}', 'OptionController@edit')->name('edit');
    Route::get('/delete/{id}', 'OptionController@delete')->name('delete');

    Route::post('/', 'OptionController@store')->name('add_place');
    Route::post('/update/{id}', 'OptionController@update')->name('update');
});

Route::group(['middleware' => 'admin', 'prefix' => 'admin', 'namespace' => 'module', 'as' => 'admin.'], function () {

    Route::post('/catalog/list', 'CatalogController@search')->name('catalog.list');
    Route::post('/cabinet/list', 'CabinetController@search')->name('cabinet.list');
    Route::get('/photo/{file}', 'CatalogController@photo')->name('photo');


    Route::get('/log', 'LogController@index')->name('log');
    Route::post('/log/search', 'LogController@list')->name('log.search');


    Route::get('/remont/added', 'RemontController@added')->name('remont.added');
    Route::get('/remont/archive', 'RemontController@list_archive')->name('remont.archive');
    Route::get('/remont/show_rem/{id}', 'RemontController@show_rem')->name('remont.show_rem');
    Route::get('/remont/add_order', 'RemontController@new_order')->name('remont.add_order');


    Route::get('/peremesh/list_oper', 'PeremeshController@index_oper')->name('peremesh.list_oper');
    Route::post('/peremesh/list_oper_search', 'PeremeshController@index_oper_search')->name('peremesh.list_oper_search');
    Route::get('/peremesh/list_pere', 'PeremeshController@list_premesh')->name('peremesh.list_pere');
    Route::post('/peremesh/list_pere_search', 'PeremeshController@list_premesh_search')->name('peremesh.list_pere_search');

    Route::get('/peremesh/show_oper/{id}', 'PeremeshController@show_oper')->name('peremesh.show_oper');
    Route::post('/peremesh/place_change/{id}', 'PeremeshController@place_change')->name('peremesh.place_change');

    Route::get('/user/index_personal/{group}', 'UserController@index_personal')->name('user.index_personal');

    Route::resource('remont', 'RemontController');
    Route::resource('user', 'UserController');
    Route::resource('catalog', 'CatalogController');
    Route::resource('order', 'OrderController');
    Route::resource('cabinet', 'CabinetController');
    Route::resource('center', 'CenterController');
    Route::resource('peremesh', 'PeremeshController');
});

Route::group(['middleware' => 'employee', 'prefix' => 'operator', 'namespace' => 'operator', 'as' => 'operator.'], function () {


    Route::get('/remont/added', 'RemontController@added')->name('remont.added');
    Route::get('/remont/archive', 'RemontController@list_archive')->name('remont.archive');
    Route::get('/remont/add_order', 'RemontController@new_order')->name('remont.add_order');
    Route::get('/remont/show_added/{id}', 'RemontController@show_added')->name('remont.show_added');
    Route::post('/remont/add_archive/{id}', 'RemontController@add_archive')->name('remont.add_archive');

    Route::get('/remont/print/{id}', 'RemontController@print')->name('remont.print');
    Route::get('/cabinet', 'OperatorController@list_cabinet')->name('cabinet.list');
    Route::post('/cabinet/search', 'OperatorController@search')->name('cabinet.list.search');
    Route::get('/cabinet/show{id}', 'OperatorController@show_cabinet')->name('cabinet.list.show');


    Route::resource('order', 'OrderController');
    Route::resource('remont', 'RemontController');



});

Route::group(['middleware' => 'accountant', 'prefix' => 'accountant', 'namespace' => 'accountant', 'as' => 'accountant.'], function () {

    Route::post('/catalog/list', 'CatalogController@search')->name('catalog.list');
    Route::post('/cabinet/list', 'CabinetController@search')->name('cabinet.list');
    Route::get('/photo/{file}', 'CatalogController@photo')->name('photo');


    Route::get('/remont/added', 'RemontController@added')->name('remont.added');
    Route::get('/remont/archive', 'RemontController@list_archive')->name('remont.archive');
    Route::get('/remont/show_rem/{id}', 'RemontController@show_rem')->name('remont.show_rem');
    Route::get('/remont/add_order', 'RemontController@new_order')->name('remont.add_order');


    Route::get('/peremesh/list_oper', 'PeremeshController@index_oper')->name('peremesh.list_oper');
    Route::post('/peremesh/list_oper_search', 'PeremeshController@index_oper_search')->name('peremesh.list_oper_search');
    Route::get('/peremesh/list_pere', 'PeremeshController@list_premesh')->name('peremesh.list_pere');
    Route::post('/peremesh/list_pere_search', 'PeremeshController@list_premesh_search')->name('peremesh.list_pere_search');
    Route::get('/peremesh/show_oper/{id}', 'PeremeshController@show_oper')->name('peremesh.show_oper');
    Route::post('/peremesh/place_change/{id}', 'PeremeshController@place_change')->name('peremesh.place_change');


    Route::resource('center', 'CenterController');
    Route::resource('remont', 'RemontController');
    Route::resource('peremesh', 'PeremeshController');
    Route::resource('catalog', 'CatalogController');
    Route::resource('cabinet', 'CabinetController');

});
Route::group(['middleware' => 'employee', 'prefix' => 'dispatcher', 'namespace' => 'dispatcher', 'as' => 'dispatcher.'], function () {

    Route::get('/remont/archive', 'RemontController@list_archive')->name('remont.archive');

    Route::post('/remont/index_search', 'RemontController@index_search')->name('remont.index_search');

    Route::get('/remont/show_rem/{id}', 'RemontController@show_rem')->name('remont.show_rem');
    Route::get('/remont/show_added/{id}', 'RemontController@show_added')->name('remont.show_added');

    Route::resource('remont', 'RemontController');

});
Auth::routes();
