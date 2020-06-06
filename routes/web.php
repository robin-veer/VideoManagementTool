<?php

Route::get('/', 'VideoController@index');
Route::resource('tag', 'TagController');

//|--------------------------------------------------------------------------
//| Video Routes
//|--------------------------------------------------------------------------

Route::group([
    'as' => 'video.',
], function () {
    Route::get('video', 'VideoController@index')->name('index');
    Route::get('sort', 'VideoController@showUnsorted')->name('sort');
    Route::patch('video/update', 'VideoController@update')->name('update');
    Route::get('video/{video}', 'VideoController@show')->name('show');
});


