<?php

$app->get('/signup', 'SignupController@index');
$app->post('/signupPost', 'SignupController@signupUser');

$app->get("/login", "LoginController@index");
$app->post("/loginPost", 'LoginController@loginUser');