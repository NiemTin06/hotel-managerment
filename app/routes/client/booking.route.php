<?php
$app->get('/bookings', 'ClientBookingController@index');
$app->get('/bookings/data', 'ClientBookingController@getData');
$app->post('/bookings/process', 'ClientBookingController@process');
