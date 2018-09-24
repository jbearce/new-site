<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Rewrites
\* ------------------------------------------------------------------------ */

// set up rewirte rules for PWA functionality
function __gulp_init__namespace_pwa_rewrite_rules() {
    add_rewrite_rule("service-worker\.js$", substr(wp_make_link_relative(get_theme_file_uri("/assets/scripts/service-worker.js")), 1), "top");
    add_rewrite_rule("offline\/?$", "/index.php?offline=true", "top");
}
add_action("init", "__gulp_init__namespace_pwa_rewrite_rules");
