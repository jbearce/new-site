<?php
$template_directory = get_template_directory();

if (file_exists("{$template_directory}/functions/autoload.php")) {
    require_once("{$template_directory}/functions/autoload.php");
}
require_once("{$template_directory}/functions/vendor.php");
require_once("{$template_directory}/functions/custom-functions.php");
require_once("{$template_directory}/functions/filters.php");
require_once("{$template_directory}/functions/htaccess.php");
require_once("{$template_directory}/functions/image-sizes.php");
require_once("{$template_directory}/functions/menus.php");
require_once("{$template_directory}/functions/meta.php");
require_once("{$template_directory}/functions/page-speed.php");
require_once("{$template_directory}/functions/post-types.php");
require_once("{$template_directory}/functions/progressive-web-app.php");
require_once("{$template_directory}/functions/rewrite-rules.php");
require_once("{$template_directory}/functions/shortcodes.php");
require_once("{$template_directory}/functions/styles-scripts.php");
require_once("{$template_directory}/functions/theme-features.php");
require_once("{$template_directory}/functions/advanced-custom-fields.php");
require_once("{$template_directory}/functions/ninja-forms.php");
require_once("{$template_directory}/functions/tribe-events.php");
