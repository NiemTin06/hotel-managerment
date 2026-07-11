<?php

$app->get('/admin/rooms-type', 'RoomTypeController@index');
$app->get('/admin/rooms-type/data', 'RoomTypeController@getRoomTypeData');
$app->post('/admin/rooms-type/create', 'RoomTypeController@create');
// $app->patch('/admin/rooms/change-multi', 'RoomController@changeMulti');
