<?php
$sectionSmall = $sectionSmall ?? '';
$sectionTitle = $sectionTitle ?? '';
$sectionText = $sectionText ?? '';
$sectionLink = $sectionLink ?? '';
$sectionLinkText = $sectionLinkText ?? '';
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
    <div>
        <?php if ($sectionSmall !== ''): ?>
            <span class="d-block small text-primary fw-bold mb-1"><?php echo htmlspecialchars($sectionSmall, ENT_QUOTES, 'UTF-8'); ?></span>
        <?php endif; ?>

        <h2 class="h4 mb-1"><?php echo htmlspecialchars($sectionTitle, ENT_QUOTES, 'UTF-8'); ?></h2>

        <?php if ($sectionText !== ''): ?>
            <p class="text-muted mb-0"><?php echo htmlspecialchars($sectionText, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
    </div>

    <?php if ($sectionLink !== '' && $sectionLinkText !== ''): ?>
        <a href="<?php echo htmlspecialchars($sectionLink, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-primary">
            <?php echo htmlspecialchars($sectionLinkText, ENT_QUOTES, 'UTF-8'); ?>
        </a>
    <?php endif; ?>
</div>

<?php
unset($sectionSmall, $sectionTitle, $sectionText, $sectionLink, $sectionLinkText);
?>
