<?php

class Controller {
    
    // Nạp Model tính từ thư mục gốc
    public function model($modelName) {
        if (file_exists("app/models/" . $modelName . ".php")) {
            require_once "app/models/" . $modelName . ".php";
            return new $modelName();
        } else {
            die("Lỗi: Không tìm thấy Model $modelName");
        }
    }

    // Nạp View tính từ thư mục gốc
    public function view($viewName, $data = []) {
        if (file_exists("app/views/" . $viewName . ".php")) {
            require_once "app/views/" . $viewName . ".php";
        } else {
            die("Lỗi: Không tìm thấy View $viewName");
        }
    }
}