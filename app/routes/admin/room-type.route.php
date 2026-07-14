<?php

$app->get('/admin/rooms-type', 'RoomTypeController@index');
$app->get('/admin/rooms-type/data', 'RoomTypeController@getRoomTypeData');
$app->post('/admin/rooms-type/create', 'RoomTypeController@create');
$app->patch('/admin/rooms-type/change-multi', 'RoomTypeController@changeMulti');
$app->patch('/admin/rooms-type/delete-multi', 'RoomTypeController@deleteMulti');
$app->get('/admin/rooms-type/{id}', 'RoomTypeController@getRoomTypeOne');
$app->post('/admin/rooms-type/update/{id}', 'RoomTypeController@update');
$app->delete("/admin/rooms-type/delete","RoomTypeController@delete");