<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Rewrites
\* ------------------------------------------------------------------------ */

// set up rewirte rules for PWA functionality
function __gulp_init_namespace___pwa_rewrite_rules() {
    add_rewrite_endpoint("manifest", EP_NONE);
    add_rewrite_rule("manifest\.json$", "index.php?manifest=true", "top");

    add_rewrite_endpoint("offline", EP_NONE);
    add_rewrite_rule("offline/?$", "index.php?offline=true", "top");
}
add_action("init", "__gulp_init_namespace___pwa_rewrite_rules");
