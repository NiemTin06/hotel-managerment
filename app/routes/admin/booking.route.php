<?php

$app->get('/admin/bookings', 'BookingController@index');
$app->get('/admin/bookings/data', 'BookingController@getBookingData');
$app->post('/admin/bookings/create', 'BookingController@create');
$app->get('/admin/bookings/{id}', 'BookingController@getOne');
$app->get('/admin/bookings/available-rooms/{id}', 'BookingController@getAvailableRooms');
$app->post('/admin/bookings/checkin/{id}', 'BookingController@checkIn');
$app->post('/admin/bookings/checkout/{id}', 'BookingController@checkOut');
$app->delete('/admin/bookings/delete', 'BookingController@delete');
$app->post('/admin/bookings/update/{id}', 'BookingController@update');