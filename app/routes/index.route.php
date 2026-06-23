<?php

$app->get("/", 'HomeController@index');

$app->get('/signup', 'SignupController@index');
$app->post('/signupPost', 'SignupController@signupUser');