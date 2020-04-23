<?php
$template_directory = get_template_directory();

if (file_exists("{$template_directory}/functions/autoload.php")) {
    require "{$template_directory}/functions/autoload.php";
}

require "{$template_directory}/functions/custom-functions.php";
require "{$template_directory}/functions/filters.php";
require "{$template_directory}/functions/htaccess.php";
require "{$template_directory}/functions/image-sizes.php";
require "{$template_directory}/functions/menus.php";
require "{$template_directory}/functions/meta.php";
require "{$template_directory}/functions/page-speed.php";
require "{$template_directory}/functions/post-types.php";
require "{$template_directory}/functions/progressive-web-app.php";
require "{$template_directory}/functions/rewrite-rules.php";
require "{$template_directory}/functions/shortcodes.php";
require "{$template_directory}/functions/styles-scripts.php";
require "{$template_directory}/functions/theme-features.php";
require "{$template_directory}/functions/advanced-custom-fields.php";
require "{$template_directory}/functions/ninja-forms.php";
require "{$template_directory}/functions/tribe-events.php";
require "{$template_directory}/functions/woocommerce.php";
