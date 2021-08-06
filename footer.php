<?php
use ClientNamespace\Constants;

WPUtil\Component::render('components/footer');

wp_footer();

if (!defined('IGNORE_USER_SCRIPTS') || !constant('IGNORE_USER_SCRIPTS')) {
	the_field(Constants\ACF::THEME_OPTIONS_SCRIPTS_BASE . '_global_body_bottom_content', 'option', false);
}

?>
</body>
</html>
