<?php
$filtered_postids = array();
$post_tags = get_the_tags();
$post_categories = get_the_category();
$this_post = get_the_ID();

if ($post_tags) {
    $ids = array();
    foreach ($post_tags as $tag) {
        $ids[] = $tag->term_id;
    }

    foreach ($ids as $id) {
        $args = array(
            'tag_id' => $id,
            'numberposts' => ($block_limit ? $block_limit : -1),
            'post__not_in' => array($this_post)
        );

        foreach (get_posts($args) as $post) {
            $filtered_postids[$post->ID] = (isset($filtered_postids[$post->ID]) ? ($filtered_postids[$post->ID] + 1) : 1);
        }
    }
}

if (count($filtered_postids) < $block_limit && !empty($post_categories)) {
    foreach ($post_categories as $category) {
        $ids[] = $category->term_id;
    }

    foreach ($ids as $id) {
        $args = array(
            'cat' => $id,
            'numberposts' => ($block_limit ? $block_limit : -1),
            'post__not_in' => array($this_post)
        );

        foreach (get_posts($args) as $post) {
            $filtered_postids[$post->ID] = (isset($filtered_postids[$post->ID]) ? ($filtered_postids[$post->ID] + 1) : 1);
        }
    }
}

arsort($filtered_postids);

$found_postids = array_keys(array_slice($filtered_postids, 0, $block_limit, true));
