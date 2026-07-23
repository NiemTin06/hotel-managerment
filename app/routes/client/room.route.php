<?php
$app->get('/rooms', 'ClientRoomController@index');
$app->get('/rooms/client/data', 'ClientRoomController@getData');
