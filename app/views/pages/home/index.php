<?php
/** @var array $data */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
...

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">
</head>
<body>

    <div style="text-align: center; margin-top: 100px; font-family: Arial, sans-serif;">
        <h1><?php echo $data['title']; ?></h1>
        <p><?php echo $data['description']; ?></p>
        
        <hr style="width: 300px; margin: 30px auto;">

        <div class="menu-links">
            <a href="<?php echo URLROOT; ?>/signup" style="padding: 10px 20px; background: #007BFF; color: white; text-decoration: none; border-radius: 5px; margin: 5px;">Đăng Ký Tài Khoản</a>
            <a href="<?php echo URLROOT; ?>/login" style="padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 5px;">Đăng Nhập</a>
        </div>
    </div>

</body>
</html>