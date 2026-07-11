<?php /** @var array $data */ ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? 'Hotel Manager'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/admin/css/style.css">
</head>
<body>

    <?php require_once 'app/views/admin/partials/header.php'; ?>

    <main class="inner-wrap">
        <!-- <div class="inner-wrap"> -->
            <?php  require_once 'app/views/admin/partials/sidebar.php'; ?>
            <div class="content">
                <?php 
                    // File Controller truyền tên file ruột qua biến $data['view_content']
                
                
                    if (isset($data['view_content'])) {
                        require_once 'app/views/admin/' . $data['view_content'] . '.php';
                    }
                ?>
            <!-- </div> -->
        </div>
    </main>
    <?php require_once 'app/views/admin/partials/footer.php'; ?>
    
    <script>
        window.APP_URLROOT = "<?= URLROOT ?>";
    </script>

    <?php if (!empty($data['page_script'])): ?>
        <script type="module" src="<?= URLROOT ?>/public/admin/js/pages/<?= $data['page_script'] ?>.js"></script>
        <?php endif; ?>
    <script type="module" src="<?= URLROOT ?>/public/admin/js/pages/default.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>