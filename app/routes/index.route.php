<?php

$app->get("/", 'HomeController@index');

require_once 'auth.route.php';