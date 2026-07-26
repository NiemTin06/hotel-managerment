<?php

$app->get('/my-account', 'ClientAccountController@index');
$app->post('/my-account/update', 'ClientAccountController@update');
