<?php

$app->get("/admin/users", "UserController@index");               // Load giao diện trang quản lý
$app->get("/admin/users/data", "UserController@getUserData");     // API lấy danh sách (phân trang/tìm kiếm)
$app->post("/admin/users/create", "UserController@create");       // API tạo tài khoản mới
$app->get("/admin/users/{id}", "UserController@getOne");          // API lấy chi tiết 1 tài khoản
$app->post("/admin/users/update/{id}", "UserController@update");  // API cập nhật (không đụng mật khẩu)
$app->post("/admin/users/reset-pass/{id}", "UserController@resetPass"); // API đặt lại mật khẩu về 123456
$app->delete("/admin/users/delete", "UserController@delete");     // API xóa tài khoản