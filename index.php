<?php
// 1. Nạp file cấu hình hệ thống
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'app/config/config.php';

// 2. Nạp bộ khung hệ thống (Core)
require_once 'app/core/App.php';
require_once 'app/core/Controller.php';
require_once 'app/core/Database.php';

// 3. Khởi tạo đối tượng Router
$app = new App();

// 4. Nạp bản đồ đường dẫn ảo
require_once 'app/routes/admin/index.route.php';

// 5. Kích hoạt hệ thống quét URL
$app->run();