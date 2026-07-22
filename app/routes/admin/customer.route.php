<?php

// Router cho Quản lý Khách hàng
$app->get('/admin/customers', 'CustomerController@index');
$app->get('/admin/customers/data', 'CustomerController@getCustomerData');
$app->post('/admin/customers/create', 'CustomerController@create');
$app->get('/admin/customers/{id}', 'CustomerController@getOne');
$app->post('/admin/customers/update/{id}', 'CustomerController@update');
$app->delete('/admin/customers/delete', 'CustomerController@delete');