<?php

class App{
    private $routes =[];

    public function get($url, $action){
        $this->routes['GET'][$url] = $action;
    }
    public function post($url, $action){
        $this->routes['POST'][$url] = $action;
    }

    public function patch($url, $action) {
        $this->routes['PATCH'][$url] = $action;
    }

    public function delete($url, $action) {
        $this->routes['DELETE'][$url] = $action;
    }

    public function run(){
        $requestUrl = $_SERVER['REQUEST_URI'];
        $requestUrl = explode('?', $requestUrl)[0]; // Bỏ biến ?id=... nếu có
        $method = $_SERVER['REQUEST_METHOD'];

        // Lấy đường dẫn thư mục hiện tại của dự án 
        $projectDir = str_replace('\\', '/', dirname(__DIR__, 2));
        $scriptName = str_replace($_SERVER['DOCUMENT_ROOT'], '', $projectDir);
        
        // Cắt bỏ tên thư mục gốc để lấy URL ảo sạch (ví dụ: /auth/login)
        $routePath = '/' . ltrim(str_replace($scriptName, '', $requestUrl), '/');

        if (isset($this->routes[$method][$routePath])) {
            $callback = $this->routes[$method][$routePath];
            list($controllerName, $actionName) = explode('@', $callback);

            
            //  Dòng mới: Ép chữ "LoginController" thành "login.controller" để tìm đúng file của bạn
            $fileName = strtolower(str_replace('Controller', '.controller', $controllerName));
            // Vì file index.php gọi từ ngoài gốc, đường dẫn kéo controller tính từ gốc

            if (file_exists("app/controllers/admin/" . $fileName . ".php")) {
                require_once "app/controllers/admin/" . $fileName . ".php";
                
                $controllerObj = new $controllerName();
                $controllerObj->$actionName();
            }
            else if (file_exists("app/controllers/client/" . $fileName . ".php")) {
                require_once "app/controllers/client/" . $fileName . ".php";
                
                $controllerObj = new $controllerName();
                $controllerObj->$actionName();
            } 
        
            else {
                die("Lỗi: Không tìm thấy file Controller: $controllerName");
            }
        } else {
            http_response_code(404);
            echo "<h3>error 404 </h3>";
        }
    }
    
}