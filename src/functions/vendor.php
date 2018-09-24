<?php
/* ------------------------------------------------------------------------ *\
 * Vendor Imports
\* ------------------------------------------------------------------------ */

// include page-for-post-type plugin
require_once(get_theme_file_path("/functions/vendor/page-for-post-type.php"));
add_action("after_setup_theme", array("Page_For_Post_Type", "get_instance"));

// include hm_get_template_part function
require_once(get_theme_file_path("/functions/vendor/hm-get-template-part.php"));
