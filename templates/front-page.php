<?php
get_header();

get_template_part('components/banners/banner', 'default', [
	'title' => 'Gravitate WordPress Theme'
]);

?>
<main class="main-content">
	<?php WPUtil\Vendor\BlueprintBlocks::safe_display() ?>
</main>
<?php

get_footer();
