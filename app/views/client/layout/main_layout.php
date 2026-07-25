<?php
/** @var array $data */
$hideSidebar = !empty($data['hide_sidebar']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? 'Hotel Manager'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/client/css/style.css">
    <?php if (!empty($data['page_style'])): ?>
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/client/css/<?= $data['page_style'] ?>.css">
    <?php endif; ?>
</head>
<body>
    <?php if (!$hideSidebar) require_once 'app/views/client/partials/header.php'; ?>

    <main class="inner-wrap<?php echo $hideSidebar ? ' no-sidebar' : ''; ?>">
        <?php if (!$hideSidebar) require_once 'app/views/client/partials/sidebar.php'; ?>
        <div class="content<?php echo $hideSidebar ? ' content-full' : ''; ?>">
            <?php
            if (isset($data['view_content'])) {
                require_once 'app/views/client/' . $data['view_content'] . '.php';
            }
            ?>
        </div>
    </main>

    <?php if (!$hideSidebar) require_once 'app/views/client/partials/footer.php'; ?>
    <script>window.APP_URLROOT = "<?php echo URLROOT; ?>";</script>
    <?php if (!empty($data['page_script'])): ?>
        <script type="module" src="<?= URLROOT ?>/public/client/js/pages/<?= $data['page_script'] ?>.js"></script>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
