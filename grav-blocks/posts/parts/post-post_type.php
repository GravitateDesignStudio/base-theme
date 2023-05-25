<?php
$post_type_query = new WP_Query(array(
    'post_type' => $args['block_filter_param'],
    'posts_per_page' => $args['block_limit'] ?? 500,
)); ?>


<div class="row">
    <?php foreach ($post_type_query->posts as $post) :
        get_template_part('components/cards/card', $args['block_filter_param'], [
            'post_id' => $post->ID,
        ]);
    endforeach ?>
</div>
