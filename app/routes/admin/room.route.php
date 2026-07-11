<?php

$app->get('/admin/rooms', 'RoomController@index');
$app->get('/rooms/data', 'RoomController@getRoomData');
$app->patch('/admin/rooms/change-multi', 'RoomController@changeMulti');
$app->get('/admin/roomsW/create', 'RoomController@create');
