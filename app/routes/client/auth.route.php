<?php

$app->get('/login', 'ClientLoginController@index');
$app->post('/login', 'ClientLoginController@loginUser');
$app->get('/register', 'ClientLoginController@register');
$app->post('/register', 'ClientLoginController@registerUser');
$app->get('/logout', 'ClientLoginController@logout');
