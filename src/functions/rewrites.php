<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Rewrites
\* ------------------------------------------------------------------------ */

function new_site_rewrite_rules() {
    add_rewrite_rule("^service-worker.js$", get_bloginfo("template_directory") . "/assets/scripts/service-worker.js");
}
add_action("init", "new_site_rewrite_rules");
