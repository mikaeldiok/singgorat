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

/*
*
* Auth Routes
*
* --------------------------------------------------------------------
*/
// Route::group(['namespace' => '\Modules\Reporting\Http\Controllers\Auth', 'as' => 'auth.', 'middleware' => 'web', 'prefix' => ''], function () {
    
//     /*
//      *
//      *  Reportings Routes
//      *
//      * ---------------------------------------------------------------------
//      */
//     $module_name = 'reports';
//     $controller_name = 'ReportsController'; 
    
//     Route::get("$module_name/login", ['as' => "$module_name.login", 'uses' => "$controller_name@showLoginForm"]);
//     Route::post("$module_name/login", ['as' => "$module_name.login", 'uses' => "$controller_name@login"]);
//     Route::get("$module_name/register", ['as' => "$module_name.register", 'uses' => "$controller_name@showRegisterForm"]);
//     Route::post("$module_name/register", ['as' => "$module_name.register", 'uses' => "$controller_name@register"]);
//     Route::get("$module_name/logout", ['as' => "$module_name.logout", 'uses' => "$controller_name@logout"]);
    
// });

/*
*
* Frontend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['namespace' => '\Modules\Reporting\Http\Controllers\Frontend', 'as' => 'frontend.', 'middleware' => ['web'], 'prefix' => ''], function () {

    /*
     *
     *  Reportings Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'reports';
    $controller_name = 'ReportsController';        
    Route::resource("$module_name", "$controller_name");
    Route::get("$module_name/catalog/filter", ['as' => "$module_name.filterReports", 'uses' => "$controller_name@filterReports"]);
    Route::get("$module_name/{id}-{reportId}", ['as' => "$module_name.show", 'uses' => "$controller_name@show"]);
});

/*
*
* Backend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['namespace' => '\Modules\Reporting\Http\Controllers\Backend', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    /*
    * These routes need view-backend permission
    * (good if you want to allow more than one group in the backend,
    * then limit the backend features by different roles or permissions)
    *
    * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
    */

    /*
     *
     *  Reports Routes
     *
     * ---------------------------------------------------------------------
     */

    $module_name = 'reports';
    $controller_name = 'ReportsController';
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::delete("$module_name/purge/{id}", ['as' => "$module_name.purge", 'uses' => "$controller_name@purge"]);
    Route::post("$module_name/get_report", ['as' => "$module_name.getreport", 'uses' => "$controller_name@get_report"]);
    Route::post("$module_name/import", ['as' => "$module_name.import", 'uses' => "$controller_name@import"]);
    Route::resource("$module_name", "$controller_name");




    $module_name = 'types';
    $controller_name = 'TypesController';
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::delete("$module_name/purge/{id}", ['as' => "$module_name.purge", 'uses' => "$controller_name@purge"]);
    Route::post("$module_name/get_type", ['as' => "$module_name.gettype", 'uses' => "$controller_name@get_type"]);
    Route::post("$module_name/import", ['as' => "$module_name.import", 'uses' => "$controller_name@import"]);
    Route::resource("$module_name", "$controller_name");

    $module_name = 'cores';
    $controller_name = 'CoresController';
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::delete("$module_name/purge/{id}", ['as' => "$module_name.purge", 'uses' => "$controller_name@purge"]);
    Route::resource("$module_name", "$controller_name");

});
