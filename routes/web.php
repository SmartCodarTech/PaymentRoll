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

Route::get('/', function () {
    return view('dashboard');
})->middleware('auth');

Auth::routes();

Route::get('/dashboard', 'DashboardController@index');
// Route::get('/system-management/{option}', 'SystemMgmtController@index');
Route::get('/profile', 'ProfileController@index');



Route::post('user-management/search', 'UserManagementController@search')->name('user-management.search');
Route::resource('user-management', 'UserManagementController');

Route::resource('employee-management', 'EmployeeManagementController');
Route::post('employee-management/search', 'EmployeeManagementController@search')->name('employee-management.search');

Route::post('mail-management/search', 'SendEmailController@search')->name('mail-management.search');
Route::resource('mail-management', 'SendEmailController');


Route::resource('civilian-management', 'CivilianEmployeesController');
Route::post('civilian-management-management/search', 'CivilianEmployeesController@search')->name('civilian-management.search');

Route::resource('system-management/department', 'DepartmentController');
Route::post('system-management/department/search', 'DepartmentController@search')->name('department.search');

Route::resource('system-management/bank', 'BankController');
Route::post('system-management/bank/search', 'BankController@search')->name('bank.search');

Route::resource('system-management/division', 'DivisionController');
Route::post('system-management/division/search', 'DivisionController@search')->name('division.search');

Route::resource('system-management/premium', 'PremiumController');
Route::post('system-management/premium/search', 'PremiumController@search')->name('premium.search');

Route::resource('system-management/salary', 'EmployeeSalaryController');
Route::post('system-management/salary/search', 'EmployeeSalaryController@search')->name('salary.search');


Route::resource('system-management/debit', 'DebitController');
Route::post('system-management/debit/search', 'DebitController@search')->name('debit.search');

Route::resource('system-management/credit', 'CreditController');
Route::post('system-management/credit/search', 'CreditController@search')->name('credit.search');

Route::resource('system-management/penalty', 'PenaltyController');
Route::post('system-management/penalty/search', 'PenaltyController@search')->name('penalty.search');

Route::get('system-management/report', 'ReportController@index');
Route::post('system-management/report/search', 'ReportController@search')->name('report.search');
Route::post('system-management/report/excel', 'ReportController@exportExcel')->name('report.excel');
Route::post('system-management/report/pdf', 'ReportController@exportPDF')->name('report.pdf');

Route::get('avatars/{name}', 'EmployeeManagementController@load');
Route::get('avatars/{name}', 'CivilianEmployeesController@load');
Route::get('my-chart', 'ChartController@index');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
