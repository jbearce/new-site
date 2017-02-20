<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Styles & Scripts
\* ------------------------------------------------------------------------ */

// enqueue styles & scripts
function new_site_enqueue_scripts() {
    wp_enqueue_script("jquery");
}
add_action("wp_enqueue_scripts", "new_site_enqueue_scripts");
