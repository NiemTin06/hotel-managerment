<?php
$app->get('/rooms', 'ClientRoomController@index');
$app->get('/rooms/data', 'ClientRoomController@getData');
