<?php
get_header();

WPUtil\Component::render('components/banners/banner-default', [
	'title' => 'Gravitate WordPress Theme'
]);

?>
<main class="main-content">
	<?php WPUtil\Vendor\BlueprintBlocks::safe_display() ?>
</main>
<?php

get_footer();
