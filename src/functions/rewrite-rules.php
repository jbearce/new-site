<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Rewrites
\* ------------------------------------------------------------------------ */

// set up rewirte rules for PWA functionality
function __gulp_init_namespace___pwa_rewrite_rules() {
    add_rewrite_rule("service-worker\.js$", "index.php?sw_script=true", "top");
    add_rewrite_rule("offline\/?$", "index.php?offline=true", "top");
}
add_action("init", "__gulp_init_namespace___pwa_rewrite_rules");
