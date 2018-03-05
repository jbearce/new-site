<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Rewrites
\* ------------------------------------------------------------------------ */

function __gulp_init__namespace_rewrite_rules() {
    add_rewrite_rule("service-worker\.js$", substr(wp_make_link_relative(get_bloginfo("template_directory")), 1) . "/assets/scripts/service-worker.js", "top");
    add_rewrite_rule("offline\/?$", substr(wp_make_link_relative(get_bloginfo("template_directory")), 1) . "/408.php", "top");
}
add_action("init", "__gulp_init__namespace_rewrite_rules");
