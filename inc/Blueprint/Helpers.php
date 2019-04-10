<?php
namespace Blueprint;

abstract class Helpers
{
	public static function display_quick_links($post_id = 0)
	{
		if (!$post_id) {
			$post_id = get_the_ID();
		}

		if (!$post_id) {
			return;
		}

		$show_quick_nav = get_field('show_quick_nav', $post_id);

		if (!$show_quick_nav) {
			return;
		}

		$quick_nav_links = get_field('quick_nav_links', $post_id);

		if (!$quick_nav_links) {
			return;
		}

		$quick_nav_links = array_map(function($link) {
			return [
				'title' => $link['quick_link_title'],
				'anchor' => $link['quick_link_anchor']
			];
		}, $quick_nav_links);

		\WPUtil\Component::render('components/ui/quick-links', [
			'links' => $quick_nav_links
		]);
	}
}
