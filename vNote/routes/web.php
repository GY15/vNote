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
    return view('home');
});
//login
Route::post('/login','UserController@login');

Route::post('/register','UserController@register');

Route::get('/user/{id}', 'UserController@show');

Route::group(['prefix' => 'Ajax', 'namespace' => 'Ajax'], function(){
    Route::post('addNewNote', 'NoteController@addNewNote');
    Route::post('allnotes', 'NoteController@getAllNote');
    Route::post('updateNote', 'NoteController@updateNote');
    Route::post('open_note', 'NoteController@getOneNote');
    Route::post('deleteNote', 'NoteController@deleteNote');
    Route::post('getAllBook', 'NoteBookController@getAllBook');
    Route::post('addBook', 'NoteBookController@addNewBook');
    Route::post('deleteBook', 'NoteBookController@deleteBook');
    Route::post('updateBook', 'NoteBookController@updateBook');
    Route::post('getNote0fTag', 'NoteController@getNoteOfTag');
    Route::post('getBookOfTag', 'NoteBookController@getBookOfTag');
    Route::post('searchCommunity', 'NoteController@searchCommunity');
    Route::post('searchTop', 'NoteController@searchTop');
    Route::post('likeNote', 'NoteController@likeNote');
    Route::post('getAllUser', 'ManagerController@getAllUser');
    Route::post('deleteUser', 'ManagerController@deleteUser');
    Route::post('modifyUser', 'ManagerController@modifyUser');
    Route::post('getUserMessage', 'ManagerController@getUserMessage');
});

