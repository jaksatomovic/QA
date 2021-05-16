<?php

Auth::routes();

Route::get('/js/lang/{lang}.js', 'LanguageController')->name('assets.lang');

Route::get('/', 'PageController@home')->name('home');
Route::get('/page/{page_slug}', 'PageController@view');
Route::get('/pages', 'PageController@index');

Route::get('/questions', 'PageController@questions')->name('questions');
Route::get('/questions/get', 'QuestionController@index')->name('questions-get');
Route::get('/questions/feed', 'QuestionController@feed')->name('feed');
Route::resource('questions', 'QuestionController')->except(['index', 'show']);
Route::get('/questions/{question_slug}', 'PageController@question')->name('question');
Route::post('/questions/{question}/favorites', 'FavoritesController@store')->name('question-favorite');
Route::delete('/questions/{question}/favorites', 'FavoritesController@destroy')->name('question-unfavorite');
Route::post('/questions/{question}/vote', 'VoteQuestionController')->name('question-vote');
Route::post('/questions/{question}/close', 'QuestionController@close')->name('question-close');

Route::get('/questions/{question}/answers', 'AnswerController@index')->name('question-answers');
Route::resource('questions.answers', 'AnswerController')->except(['index', 'show']);
Route::get('/answers/{answer}', 'AnswerController@show')->name('answer');
Route::post('answers/{answer}/accept', 'AnswerController@accept')->name('answer-accept');
Route::post('/answers/{answer}/vote', 'VoteAnswerController')->name('answer->vote');

Route::get('/topics', 'PageController@topics')->name('topics');
Route::get('/topics/all', 'TopicController@all')->name('topics-all');
Route::get('/topics/get', 'TopicController@index')->name('topics-get');
Route::get('/topics/{topic_slug}', 'PageController@topic')->name('topic');
Route::post('/topics/{topic}/follow', 'UserTopicController@store')->name('topic-follow');
Route::delete('/topics/{topic}/follow', 'UserTopicController@destroy')->name('topic-unfollow');

Route::get('/spaces', 'PageController@spaces')->name('spaces');
Route::post('/spaces', 'TopicController@store')->name('space-store');
Route::put('/spaces/{topic}', 'TopicController@update')->name('space-update');
Route::get('/spaces/{topic_slug}', 'PageController@topic')->name('space');

Route::get('/users', 'PageController@users')->name('users');
Route::get('/users/get', 'UserController@index')->name('users-get');
Route::get('/users/id{user}', 'PageController@user')->name('user');
Route::get('/users/id{user}/answers', 'AnswerController@index')->name('user-answers');
Route::put('/users/id{user}/account', 'UserController@updateAccount')->name('user-account');
Route::put('/users/id{user}/profile', 'UserController@updateProfile')->name('user-profile');
Route::post('/users/id{user}/avatar', 'UserAvatarController@store')->name('user-avatar');
Route::put('/users/id{user}/password', 'UserController@updatePassword')->name('user-password');
Route::put('/users/id{user}/close', 'UserController@closeAccount')->name('user-close');

Route::get('/notifications', 'PageController@notifications')->name('notifications');
Route::get('/notifications/check', 'NotificationController@check')->name('notifications-check');
Route::get('/notifications/get', 'NotificationController@get')->name('notifications-get');
Route::put('/notifications/read', 'NotificationController@read')->name('notifications-read');
Route::put('/notifications/{notification}/read', 'NotificationController@update')->name('notifications-view');
Route::delete('/notifications/{notification}/delete', 'NotificationController@destroy')->name('notification-delete');

Route::post('/reports', 'ReportController@store')->name('report-store');

Route::post('/upload-image', 'ImageController@store')->name('image-upload');

Route::get('/search/{search}', 'PageController@search')->name('search');
Route::post('/search/{search}', 'SearchController@index')->name('search-get');

Route::prefix('admin')->group(function () {

    Route::get('/index', 'Admin\PageController@dashboard')->name('admin');

    Route::get('/questions', 'Admin\PageController@questions');
    Route::get('/questions/get', 'Admin\QuestionController@index');
    Route::get('/questions-count', 'Admin\QuestionController@countPerRange');
    Route::get('/questions-counted', 'Admin\QuestionController@countPerDay');
    Route::put('/questions/{question}/open', 'Admin\QuestionController@open');
    Route::put('/questions/{question}/close', 'Admin\QuestionController@close');
    Route::put('/questions/{question}', 'Admin\QuestionController@update');
    Route::delete('/questions/{question}', 'Admin\QuestionController@destroy');

    Route::get('/answers', 'Admin\PageController@answers');
    Route::get('/answers/get', 'Admin\AnswerController@index');
    Route::get('/answers-count', 'Admin\AnswerController@countPerRange');
    Route::get('/answers-counted', 'Admin\AnswerController@countPerDay');
    Route::put('/answers/{answer}', 'Admin\AnswerController@update');
    Route::delete('/answers/{answer}', 'Admin\AnswerController@destroy');

    Route::get('/topics', 'Admin\PageController@topics');
    Route::post('/topics', 'Admin\TopicController@store');
    Route::get('/topics/get', 'Admin\TopicController@index');
    Route::get('/topics-count', 'Admin\TopicController@countPerRange');
    Route::get('/topics-counted', 'Admin\TopicController@countPerDay');
    Route::get('/topics-count-unapproved', 'Admin\TopicController@countUnapproved');
    Route::put('/topics/{topic}', 'Admin\TopicController@update');
    Route::put('/topics/{topic}/approve', 'Admin\TopicController@approve');
    Route::put('/topics/{topic}/disapprove', 'Admin\TopicController@disapprove');
    Route::delete('/topics/{topic}', 'Admin\TopicController@destroy');

    Route::get('/users', 'Admin\PageController@users');
    Route::get('/users/get', 'Admin\UserController@index');
    Route::get('/users-count', 'Admin\UserController@countPerRange');
    Route::get('/users-counted', 'Admin\UserController@countPerDay');
    Route::put('/users/{user}/ban', 'Admin\UserController@ban');
    Route::put('/users/{user}/unban', 'Admin\UserController@unban');
    Route::delete('/users/{user}', 'Admin\UserController@destroy');

    Route::get('/reports', 'Admin\PageController@reports');
    Route::get('/reports/get', 'Admin\ReportController@index');
    Route::get('/reports-count', 'Admin\ReportController@countPerRange');
    Route::get('/reports-count-unsolved', 'Admin\ReportController@countUnsolved');
    Route::get('/reports-counted', 'Admin\ReportController@countPerDay');
    Route::put('/reports/solved', 'Admin\ReportController@solveAll');
    Route::put('/reports/{report}', 'Admin\ReportController@solve');
    Route::delete('/reports/{report}', 'Admin\ReportController@destroy');

    Route::get('/pages', 'Admin\PageController@pages');
    Route::post('/pages', 'Admin\PageController@store');
    Route::get('/pages/get', 'Admin\PageController@index');
    Route::put('/pages/{page}', 'Admin\PageController@update');
    Route::delete('/pages/{page}', 'Admin\PageController@destroy');

    Route::get('/settings', 'Admin\PageController@settings');
    Route::put('/settings', 'Admin\AdminController@updateSettings');
    Route::get('/about', 'Admin\PageController@about');
    Route::get('/clear-cache', 'Admin\AdminController@clearCache');
});
