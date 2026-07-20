<?php

$app->get('/admin/users', 'UserController@index');


// $app->get('/admin/users/data', 'UserController@getUserData');
// $app->post('/admin/users/create', 'UserController@create');
// $app->patch('/admin/users/change-multi', 'UserController@changeMulti');
// $app->patch('/admin/users/delete-multi', 'UserController@deleteMulti');
// $app->get('/admin/users/{id}', 'UserController@getUserOne');
// $app->post('/admin/users/update/{id}', 'UserController@update');
// $app->delete("/admin/users/delete","UserController@delete");