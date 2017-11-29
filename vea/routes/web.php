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
    
    return redirect('/home');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->middleware('revalidate');
Route::get('/home/changepassword', 'ChangePasswordController@show')->middleware('revalidate');
Route::post('/home/changepassword', 'ChangePasswordController@validatepassword')->middleware('revalidate');

//USER ROUTES
Route::get('users','UsersController@index')->middleware('revalidate');
Route::get('users/create','UsersController@create')->middleware('revalidate');
Route::post('users/create','UsersController@store')->middleware('revalidate');
Route::get('users/{user}/edit','UsersController@edit')->middleware('revalidate'); 
Route::post('users/{user}/edit','UsersController@update')->middleware('revalidate');
Route::get('users/{user}/delete','UsersController@delete')->middleware('revalidate');

//ENQUIRY ROUTES
Route::get('enquiry','EnquiryController@index')->middleware('revalidate');
Route::get('enquiry/create','EnquiryController@create')->middleware('revalidate');
Route::post('enquiry/create','EnquiryController@store')->middleware('revalidate');
Route::get('enquiry/{enquiry}/edit','EnquiryController@edit')->middleware('revalidate'); 
Route::patch('enquiry/{enquiry}/edit','EnquiryController@update')->middleware('revalidate');
Route::get('enquiry/{enquiry}/delete','EnquiryController@delete')->middleware('revalidate'); 
Route::get('enquiry/filters','EnquiryController@filters')->middleware('revalidate'); 
Route::get('enquiry/search','EnquiryController@search')->middleware('revalidate');


//AJAX ROUTES
Route::get('ajax/orientation/{enquiry}','OrientationController@view')->middleware('revalidate'); 
Route::post('ajax/orientation/{enquiry}','OrientationController@add')->middleware('revalidate'); 
Route::get('ajax/admission/{standard}/{branch}','AdmissionController@standard')->middleware('revalidate'); 
Route::get('ajax/admissionbatch/{batch}/{standard}/{branch}','AdmissionController@batch')->middleware('revalidate'); 

//ORIENTATION LIST
Route::get('orientation/list','OrientationController@index')->middleware('revalidate');
Route::get('orientation/filters','OrientationController@filters')->middleware('revalidate'); 
Route::get('orientation/search','OrientationController@search')->middleware('revalidate');

//ADMISSION ROUTES
Route::get('admission','AdmissionController@index')->middleware('revalidate');
Route::get('admission/create','AdmissionController@create')->middleware('revalidate');
Route::post('admission/create','AdmissionController@save')->middleware('revalidate');
Route::get('admission/{admission}/list','AdmissionController@list')->middleware('revalidate');
Route::get('admission/{admission}/edit','AdmissionController@edit')->middleware('revalidate');
Route::post('admission/{admission}/edit','AdmissionController@update')->middleware('revalidate');
Route::get('admission/{admission}/delete','AdmissionController@delete')->middleware('revalidate');
Route::get('admission/filters','AdmissionController@filters')->middleware('revalidate'); 
Route::get('admission/{batch}/{standard}/search','AdmissionController@search')->middleware('revalidate');
Route::get('batch/{batch}/{standard}/admission','AdmissionController@admissionsview')->middleware('revalidate');

//BATCH ROUTES
Route::get('batch','BatchController@index')->middleware('revalidate');
Route::get('batch/create','BatchController@create')->middleware('revalidate');
Route::post('batch/create','BatchController@save')->middleware('revalidate');
Route::get('batch/{batch}/list','BatchController@list')->middleware('revalidate');
Route::get('batch/{batch}/edit','BatchController@edit')->middleware('revalidate');
Route::post('batch/{batch}/edit','BatchController@update')->middleware('revalidate');
Route::get('batch/{batch}/delete','BatchController@delete')->middleware('revalidate');
Route::get('batch/filters','BatchController@filters')->middleware('revalidate'); 
Route::get('batch/search','BatchController@search')->middleware('revalidate');

//FEE ROTES
Route::get('fee','FeeController@index')->middleware('revalidate');
Route::get('fee/create','FeeController@create')->middleware('revalidate');
Route::post('fee/create','FeeController@save')->middleware('revalidate');
Route::get('fee/{fee}/edit','FeeController@edit')->middleware('revalidate');
Route::post('fee/{fee}/edit','FeeController@update')->middleware('revalidate');
Route::get('fee/{fee}/delete','FeeController@delete')->middleware('revalidate');

//FEE ADD ROUTES
Route::get('admission/{admission}/{installment}/fee','AdmissionController@fee')->middleware('revalidate');
Route::post('admission/{admission}/{installment}/fee','AdmissionController@feeadd')->middleware('revalidate');

Route::get('admission/{admission}/{installment}/viewfeereceipt','AdmissionController@viewfeereceipt')->middleware('revalidate');
Route::get('admission/{admission}/{installment}/downloadreceipt','AdmissionController@downloadreceipt')->middleware('revalidate');
Route::get('admission/{admission}/{installment}/emailreceipt','AdmissionController@emailreceipt')->middleware('revalidate');

//TO-DO LIST

Route::get('todolist','TodoListController@index')->middleware('revalidate');
Route::get('todolist/create','TodoListController@create')->middleware('revalidate');
Route::post('todolist/create','TodoListController@store')->middleware('revalidate');
Route::get('todolist/{todolist}/edit','TodoListController@edit')->middleware('revalidate'); 
Route::patch('todolist/{todolist}/edit','TodoListController@update')->middleware('revalidate');
Route::get('todolist/{todolist}/delete','TodoListController@delete')->middleware('revalidate'); 
Route::get('todolist/filters','TodoListController@filters')->middleware('revalidate'); 
Route::get('todolist/search','TodoListController@search')->middleware('revalidate');

//EXPORT TEMPLATE
Route::get('export/create','ExportController@create')->middleware('revalidate');
Route::post('export/create','ExportController@export')->middleware('revalidate');




//PARENTS MEET
Route::get('parentsmeet','ParentsmeetController@index')->middleware('revalidate');
Route::get('parentsmeet/create','ParentsmeetController@create')->middleware('revalidate');
Route::post('parentsmeet/create','ParentsmeetController@store')->middleware('revalidate');
Route::get('parentsmeet/{parentsmeet}/edit','ParentsmeetController@edit')->middleware('revalidate'); 
Route::patch('parentsmeet/{parentsmeet}/edit','ParentsmeetController@update')->middleware('revalidate');
Route::get('parentsmeet/{parentsmeet}/delete','ParentsmeetController@delete')->middleware('revalidate'); 
Route::get('parentsmeet/filters','ParentsmeetController@filters')->middleware('revalidate'); 
Route::get('parentsmeet/search','ParentsmeetController@search')->middleware('revalidate');



//MARKS ROUTES
Route::get('marks/BHANDUP/create','MarksController@batchlist_bhandup')->middleware('revalidate');
Route::get('marks/MULUND/create','MarksController@batchlist_mulund')->middleware('revalidate');
Route::get('marks/{branch}/{batch}/{standard}/newmarks','MarksController@createmarks')->middleware('revalidate');
Route::post('marks/{branch}/{batch}/{standard}/newmarks','MarksController@addmarks')->middleware('revalidate');
Route::get('marks/{branch}/{batch}/{standard}/listmarks','MarksController@listmarks')->middleware('revalidate');
Route::get('marks/{branch}/{batch}/{standard}/summaryreport','MarksController@summaryreportacayear')->middleware('revalidate');
Route::post('marks/{branch}/{batch}/{standard}/summaryreport','MarksController@viewreport')->middleware('revalidate');
Route::get('marks/{branch}/{marks}/{batch}/{standard}/editmarks','MarksController@editmarks')->middleware('revalidate');
Route::post('marks/{branch}/{marks}/{batch}/{standard}/editmarks','MarksController@updatemarks')->middleware('revalidate');
Route::get('marks/{branch}/{marks}/{batch}/{standard}/deletemarks','MarksController@deletemarks')->middleware('revalidate');
Route::get('marks/{branch}/{batch}/{standard}/search','MarksController@search')->middleware('revalidate');

Route::get('marks/{branch}/{marks}/{batch}/{standard}/addstudentmarks','MarksController@addstudentmarks')->middleware('revalidate');
Route::post('marks/{branch}/{marks}/{batch}/{standard}/addstudentmarks','MarksController@storestudentmarks')->middleware('revalidate');

Route::get('marks/{branch}/{marks}/{batch}/{standard}/liststudentmarks','MarksController@liststudentmarks')->middleware('revalidate');


//SCHOOL MARKS ROUTES
Route::get('schoolmarks/BHANDUP/create','SchoolMarksController@batchlist_bhandup')->middleware('revalidate');
Route::get('schoolmarks/MULUND/create','SchoolMarksController@batchlist_mulund')->middleware('revalidate');
Route::get('schoolmarks/{branch}/{batch}/{standard}/newmarks','SchoolMarksController@createmarks')->middleware('revalidate');
Route::post('schoolmarks/{branch}/{batch}/{standard}/newmarks','SchoolMarksController@addmarks')->middleware('revalidate');
Route::get('schoolmarks/{branch}/{batch}/{standard}/listmarks','SchoolMarksController@listmarks')->middleware('revalidate');
Route::get('schoolmarks/{branch}/{batch}/{standard}/summaryreport','SchoolMarksController@summaryreportacayear')->middleware('revalidate');
Route::post('schoolmarks/{branch}/{batch}/{standard}/summaryreport','SchoolMarksController@viewreport')->middleware('revalidate');
Route::get('schoolmarks/{branch}/{marks}/{batch}/{standard}/editmarks','SchoolMarksController@editmarks')->middleware('revalidate');
Route::post('schoolmarks/{branch}/{marks}/{batch}/{standard}/editmarks','SchoolMarksController@updatemarks')->middleware('revalidate');
Route::get('schoolmarks/{branch}/{marks}/{batch}/{standard}/deletemarks','SchoolMarksController@deletemarks')->middleware('revalidate');
Route::get('schoolmarks/{branch}/{batch}/{standard}/search','SchoolMarksController@search')->middleware('revalidate');

Route::get('schoolmarks/{branch}/{marks}/{batch}/{standard}/addstudentmarks','SchoolMarksController@addstudentmarks')->middleware('revalidate');
Route::post('schoolmarks/{branch}/{marks}/{batch}/{standard}/addstudentmarks','SchoolMarksController@storestudentmarks')->middleware('revalidate');

Route::get('schoolmarks/{branch}/{marks}/{batch}/{standard}/liststudentmarks','SchoolMarksController@liststudentmarks')->middleware('revalidate');

