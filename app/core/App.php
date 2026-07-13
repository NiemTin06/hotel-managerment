<?php

class App{
    private $routes =[];
    private $dynamicRoutes = [];

    public function get($url, $action){
        $this->addRoute("GET", $url, $action);
    }

    public function post($url, $action){
        $this->addRoute("POST", $url, $action);
    }

    public function patch($url, $action){
        $this->addRoute("PATCH", $url, $action);
    }

    public function delete($url, $action){
        $this->addRoute("DELETE", $url, $action);
    }

    private function addRoute($method, $url, $action){
        // Nếu có { } => route động
        if (preg_match('/\{[a-zA-Z_][a-zA-Z0-9_]*\}/', $url)) {
            $pattern = preg_replace(
                '/\{[a-zA-Z_][a-zA-Z0-9_]*\}/',
                '([^/]+)',
                $url
            );
            $pattern = "#^{$pattern}$#";
            $this->dynamicRoutes[$method][$pattern] = $action;
        }
        // Route tĩnh
        else {
            $this->routes[$method][$url] = $action;
        }
    }

    private function execute($callback, $params = []){
        list($controllerName, $actionName) = explode('@', $callback);
        $fileName = strtolower(
            str_replace('Controller', '.controller', $controllerName)
        );
        if (file_exists("app/controllers/admin/$fileName.php")) {
            require_once "app/controllers/admin/$fileName.php";
        }
        elseif (file_exists("app/controllers/client/$fileName.php")) {
            require_once "app/controllers/client/$fileName.php";
        }
        else {
            die("Không tìm thấy Controller: $controllerName");
        }
        $controller = new $controllerName();
        call_user_func_array(
            [$controller, $actionName],
            $params
        );
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
            $this->execute(
                $this->routes[$method][$routePath]
            );
            return;
        }
            // Route động
        if (isset($this->dynamicRoutes[$method])) {
            foreach ($this->dynamicRoutes[$method] as $pattern => $action) {
                if (preg_match($pattern, $routePath, $matches)) {
                    // Xoa phan tu da tien cua mang 
                    array_shift($matches);
                    $this->execute($action, $matches);
                    return;
                }
            }
        }
            
        http_response_code(404);   
        echo "<h3>error 404 </h3>";
    }
}