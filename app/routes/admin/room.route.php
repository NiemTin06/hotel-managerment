<?php

$app->get('/admin/rooms', 'RoomController@index');
$app->get('/admin/rooms/data', 'RoomController@getRoomData');
$app->patch('/admin/rooms/change-multi', 'RoomController@changeMulti');
$app->post('/admin/rooms/create', 'RoomController@create');
$app->patch('/admin/rooms/delete-multi', 'RoomController@deleteMulti');
$app->get('/admin/rooms/{id}', 'RoomController@getRoomOne');
$app->post('/admin/rooms/update/{id}', 'RoomController@update');
$app->delete("/admin/rooms/delete","RoomController@delete");