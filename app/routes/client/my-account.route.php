<?php

$app->get('/my-account', 'ClientAccountController@index');
$app->get('/my-account/history', 'ClientAccountController@getHistory');
$app->post('/my-account/update', 'ClientAccountController@update');
