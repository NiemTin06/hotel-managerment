<?php
$pageEyebrow = $pageEyebrow ?? '';
$pageActionUrl = $pageActionUrl ?? '';
$pageActionText = $pageActionText ?? '';
$pageActionClass = $pageActionClass ?? 'btn btn-primary';
?>

<div class="text-center mb-4">
    <?php if ($pageEyebrow !== ''): ?>
        <span class="d-block small text-primary fw-bold mb-1"><?php echo htmlspecialchars($pageEyebrow, ENT_QUOTES, 'UTF-8'); ?></span>
    <?php endif; ?>

    <h1 class="h3 mb-2"><?php echo htmlspecialchars($data['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h1>
    <p class="text-muted mb-0"><?php echo htmlspecialchars($data['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>

    <?php if ($pageActionUrl !== '' && $pageActionText !== ''): ?>
        <a href="<?php echo htmlspecialchars($pageActionUrl, ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo htmlspecialchars($pageActionClass, ENT_QUOTES, 'UTF-8'); ?> mt-3">
            <?php echo htmlspecialchars($pageActionText, ENT_QUOTES, 'UTF-8'); ?>
        </a>
    <?php endif; ?>
</div>

<?php
unset($pageEyebrow, $pageActionUrl, $pageActionText, $pageActionClass);
?>
