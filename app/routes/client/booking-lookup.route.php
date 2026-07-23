<?php
$app->get('/booking-lookup', 'ClientBookingLookupController@index');
$app->post('/booking-lookup/search', 'ClientBookingLookupController@search');
