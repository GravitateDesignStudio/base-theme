<?php
namespace WPUtil;

use WPUtil\Models\SelectOption;
use WP_Term_Query;

abstract class Taxonomy
{
	/**
	 * Register a taxonomy using 'register_taxonomy'
	 *
	 * @param string $label_single
	 * @param string $label_plural
	 * @param string/array $post_types
	 * @param array $options
	 * @return void
	 */
	public static function register(string $label_single, string $label_plural, $post_types, array $options = []): void
	{
		$default_options = array(
			'labels' => array(
				'name' => $label_plural,
				'singular_name' => $label_single,
				'search_items' => "Search {$label_plural}",
				'all_items' => "All {$label_plural}",
				'parent_item' => "Parent {$label_single}",
				'parent_item_colon' => "Parent {$label_single}:",
				'edit_item' => "Edit {$label_single}",
				'update_item' => "Update {$label_single}",
				'add_new_item' => "Add New {$label_single}",
				'new_item_name' => "New {$label_single} Name"
			),
			'hierarchical' => true,
			'show_ui' => true,
			'query_var' => true,
		);

		$slug = sanitize_title($label_plural);
		$options = array_merge($default_options, $options);

		add_action('init', function() use (&$slug, &$post_types, &$options) {
			register_taxonomy($slug, $post_types, $options);
		});
	}

	/**
	 * Remove a taxonomy by slug name
	 *
	 * @param string $slug
	 * @return void
	 */
	public static function remove(string $slug): void
	{
		$slugs = is_array($slug) ? $slug : array($slug);

		add_action('init', function() use (&$slugs) {
			global $wp_taxonomies;

			foreach ($slugs as $slug) {
				if (taxonomy_exists($slug)) {
					unset($wp_taxonomies[$slug]);
				}
			}
		}, 999);
	}

	/**
	 * Retrieve taxonomy values to be used as select control options
	 *
	 * @param string $taxonomy The taxonomy name to retrieve values for
	 * @param array<string, mixed> $opts Additional options passed to WP_Term_Query
	 * @return array<\Bullhorn\Models\SelectOption> A key/value array of taxonomy slug/title to be used as select control options
	 */
	public static function get_taxonomy_filter_options(string $taxonomy, array $opts = []): array
	{
		$query_opts = array_merge([
			'taxonomy' => $taxonomy,
			'orderby' => 'name',
			'order' => 'ASC'
		], $opts);

		$term_query = new WP_Term_Query($query_opts);

		$options = array_reduce($term_query->terms ?? [], function ($acum, $term) {
			$slug = trim($term->slug ?? '');
			$name = trim($term->name ?? '');

			if ($slug && $name) {
				$option = new SelectOption();
				$option->value = $slug;
				$option->label = $name;

				$acum[] = $option;
			}

			return $acum;
		}, []);

		return $options;
	}
}
