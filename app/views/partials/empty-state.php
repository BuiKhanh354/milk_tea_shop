<?php
/**
 * Reusable Empty State Component
 * Variables expected:
 * - $empty_icon (FontAwesome class, e.g., 'fa-box-open')
 * - $empty_title (Heading text)
 * - $empty_message (Description text)
 * - $empty_btn_text (Button text)
 * - $empty_btn_link (Button URL)
 */
?>

<div class="empty-state text-center py-5 my-4">
    <div class="empty-icon-wrapper mb-4 text-sage opacity-50">
        <i class="<?= $empty_icon ?? 'fa-solid fa-ghost' ?>" style="font-size: 5rem;"></i>
    </div>
    <h3 class="font-serif fw-bold text-forest mb-3"><?= $empty_title ?? 'Nothing here yet' ?></h3>
    <p class="text-muted mb-4 mx-auto" style="max-width: 400px;">
        <?= $empty_message ?? 'Looks like this section is currently empty.' ?>
    </p>
    <?php if (isset($empty_btn_text) && isset($empty_btn_link)): ?>
        <a href="<?= BASE_URL . $empty_btn_link ?>" class="btn btn-forest px-4 py-2 text-uppercase tracking-wide">
            <?= $empty_btn_text ?>
        </a>
    <?php endif; ?>
</div>
