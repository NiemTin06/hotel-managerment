<?php

$app->get("/admin", 'HomeController@index');

require_once 'auth.route.php';
require_once 'dashboard.route.php';
require_once 'user.route.php';
require_once 'customer.route.php';
require_once 'room.route.php';
require_once 'room-type.route.php';
require_once 'booking.route.php';