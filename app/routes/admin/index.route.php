<?php

$app->get("/admin", 'HomeController@index');

require_once 'auth.route.php';
require_once 'room.route.php';
require_once 'room-type.route.php';