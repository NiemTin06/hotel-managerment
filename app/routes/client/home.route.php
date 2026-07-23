<?php
$app->get('/', 'ClientHomeController@index');
$app->get('/home', 'ClientHomeController@index');
$app->get('/home/data', 'ClientHomeController@getData');