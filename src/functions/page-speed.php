<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Page Speed
\* ------------------------------------------------------------------------ */

// remove version strings
function __gulp_init__namespace_remove_script_version($src) {
    $parts = explode("?ver", $src);
    return $parts[0];
}
add_filter("script_loader_src", "__gulp_init__namespace_remove_script_version", 15, 1);
add_filter("style_loader_src", "__gulp_init__namespace_remove_script_version", 15, 1);

// disable oEmbed
function __gulp_init__namespace_stop_loading_wp_embed() {
    if (!is_admin()) {
        wp_deregister_script("wp-embed");
    }
}
add_action("init", "__gulp_init__namespace_stop_loading_wp_embed");

// disable Emoji
remove_action("wp_head", "print_emoji_detection_script", 7);
remove_action("wp_print_styles", "print_emoji_styles");

// load scripts asynchronously
function __gulp_init__namespace_make_scripts_async($tag, $handle, $src) {
    if (!is_admin()) {
        return str_replace("<script", "<script defer='defer'", $tag);
        exit;
    }

    return $tag;
}
add_filter("script_loader_tag", "__gulp_init__namespace_make_scripts_async", 10, 3);

// load styles asynchronously
function __gulp_init__namespace_make_styles_async($tag, $handle, $src) {
    global $template;

    $critical_css = get_critical_css($template);

    if (!is_admin() && $critical_css) {
        return str_replace("rel='stylesheet'", "rel='preload' as='style' onload=\"this.rel='stylesheet'\"", $tag) . "<noscript>{$tag}</noscript>";
        exit;
    }

    return $tag;
}
add_filter("style_loader_tag", "__gulp_init__namespace_make_styles_async", 10, 3);

// create a local copy of Google Analytics instead and serve that for caching purposes
function __gulp_init__namespace_cache_google_analytics($url) {
    $local_path = ABSPATH . "analytics.js";

    if (!file_exists($local_path) || date("c", filemtime($local_path)) <= date("c", strtotime("-2 hours"))) {
        file_put_contents($local_path, fopen($url, "r"));
    }

    return home_url("/analytics.js");
}
add_filter("gadwp_analytics_script_path", "__gulp_init__namespace_cache_google_analytics");
