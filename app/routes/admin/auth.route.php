<?php
$app->get("/admin/login", "LoginController@index");
$app->post("/admin/loginPost", 'LoginController@loginUser');
$app->get("/admin/logout", "LoginController@logout");