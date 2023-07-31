<?php
$data = $args['data'] ?? [];
$block = $args['block'] ?? '';

if (empty($data) || empty($block)) {
    return;
}
?>

<div class="accordion-container">
    <?php foreach($data as $i => $item) { ?>
        <?php $is_active = $i == 0 ? 'active' : ''; ?>
        <div class="accordion-container__section">
            <div class="accordion-container__header <?= $is_active ?>" data-acc-heading='<?= "acc-" . $i ?>' >
                <p class="acc-heading-text"><?= $item[$block.'_heading'] ?></p>
                <?php the_svg('general/chevron-left') ?>
            </div>
            <div class="accordion-container__content <?php echo "acc-" . $i;?>">
                <?= $item[$block.'_content']; ?>
            </div>
        </div>
    <?php 
    } ?>
</div>