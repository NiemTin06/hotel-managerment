<?php

$app->get('/', 'HomeController@index');
require_once 'home.route.php';
require_once 'room.route.php';
require_once 'booking.route.php';
require_once 'booking-lookup.route.php';