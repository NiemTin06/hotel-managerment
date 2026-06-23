<?php /** @var array $data */ ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? 'Hotel Manager'; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/style.css">
</head>
<body>

    <?php require_once 'app/views/partials/header.php'; ?>

    <main class="container" style="min-height: 400px; padding: 20px;">
        <?php 
            // File Controller truyền tên file ruột qua biến $data['view_content']
            if (isset($data['view_content'])) {
                require_once 'app/views/' . $data['view_content'] . '.php';
            }
        ?>
    </main>

    <?php require_once 'app/views/partials/footer.php'; ?>

</body>
</html>