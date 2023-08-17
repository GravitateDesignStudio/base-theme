<?php 
$data = get_sub_field($block.'_faqs');
?>

<div class="block-inner">
    <div class="columns small-12">
        <?php get_template_part('components/accordion/accordion', 'accordion',[
            'block' => $block, // 'faq
            'data' => $data 
        ]); ?>
    </div>
</div>
