<?php foreach ($args['posts'] as $post) : ?>
	<div class="columns small-12 medium-4">
		<article <?php post_class('', $post->ID) ?>>
			<a class="card-resource" href="<?= get_permalink($post->ID) ?>">
				<div class="corner-icon">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 23">
						<path fill="#fff" d="m2.4 14.7-.6-1.4-1.4-.7 1.4-.6.6-1.4.7 1.4 1.4.6-1.4.7-.7 1.4ZM6 21.3l-1-2-2-1 2-1 1-2.1 1 2 2 1-2 1-1 2.1Zm0-11.2L5 8 3 7l2-1 1-2 1 2 2 1-2 1-1 2Zm12.4 12.7a12 12 0 0 1-5.5-4.6A11.6 11.6 0 0 1 12 6.4c.8-1.7 2-3.2 3.3-4.5h-4.7V.3h7.8V8h-1.7V3a13.4 13.4 0 0 0-3 4 10 10 0 0 0-1.2 4.5c0 1.9.5 3.7 1.6 5.5 1 1.7 2.5 3 4.3 3.9v1.9Zm1.1-4.3v-2.8h-2.8V14h2.8v-2.8h1.7V14H24v1.7h-2.8v2.8h-1.7Z" />
					</svg>
				</div>
				<div class="card-resource__image-container">
					<?php if (has_post_thumbnail($post->ID)) : ?>
						<img class="card-resource__image" src="<?= get_the_post_thumbnail_url($post->ID) ?>" alt="<?= get_the_title($post->ID) ?>">
					<?php endif ?>
				</div>
				<div class="card-blog__content-container">
					<div class="card-blog__category h6 text-neutral-600 card-resource__category">
						<?= get_the_category($post->ID)[0]->name ?>
					</div>
					<h5 class="card-blog__title text-teal"><?= get_the_title($post->ID) ?></h3>
						<div class="card-blog__content">
							<?= get_the_excerpt($post->ID) ?>
						</div>
				</div>
			</a>
		</article>
	</div>
<?php endforeach; ?>
<?php wp_reset_postdata(); ?>