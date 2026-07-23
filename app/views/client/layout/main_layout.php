<?php /** @var array $data */ ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? 'Hotel Manager'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/client/css/style.css">

    <?php if (!empty($data['page_style'])): ?>
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/client/css/<?php echo $data['page_style']; ?>.css">
    <?php endif; ?>
</head>
<body>
    <?php require_once 'app/views/client/partials/header.php'; ?>

    <main class="inner-wrap">
        <?php require_once 'app/views/client/partials/sidebar.php'; ?>

        <div class="content">
            <?php
            if (isset($data['view_content'])) {
                require_once 'app/views/client/' . $data['view_content'] . '.php';
            }
            ?>
        </div>
    </main>

    <?php require_once 'app/views/client/partials/footer.php'; ?>

    <script>
        window.APP_URLROOT = "<?php echo URLROOT; ?>";
    </script>

    <?php if (!empty($data['page_script'])): ?>
        <script type="module" src="<?php echo URLROOT; ?>/public/client/js/pages/<?php echo $data['page_script']; ?>.js?v=<?php echo time(); ?>"></script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>