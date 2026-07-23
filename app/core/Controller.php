<?php

class Controller {
    public function __construct() {
        require_once __DIR__ . '/../config/session.php';
        require_once __DIR__ . '/../helpers/auth.helper.php';

        initSession();
    }
    // Nạp Model tính từ thư mục gốc
    public function model($modelName) {
         $fileName = strtolower($modelName) . ".model.php";
         $className = ucfirst($modelName) . "Model";
        if (file_exists("app/models/" . $fileName)) {
            require_once "app/models/" . $fileName;
            return new $className();
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