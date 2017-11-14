<?php
/* ------------------------------------------------------------------------ *\
 * Vendor Imports
\* ------------------------------------------------------------------------ */

// include page-for-post-type plugin
require_once(TEMPLATEPATH . "/functions/vendor/page-for-post-type.php");
add_action("after_setup_theme", array("Page_For_Post_Type", "get_instance"));

// include wp14050 hotfix
require_once(TEMPLATEPATH . "/functions/vendor/wp14050-hotfix.php");
