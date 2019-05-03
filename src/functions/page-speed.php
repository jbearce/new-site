<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Page Speed
\* ------------------------------------------------------------------------ */

// remove version strings
function __gulp_init_namespace___remove_script_version($src) {
    $parts = explode("?ver", $src);
    return $parts[0];
}
add_filter("script_loader_src", "__gulp_init_namespace___remove_script_version", 15, 1);
add_filter("style_loader_src", "__gulp_init_namespace___remove_script_version", 15, 1);

// disable oEmbed
function __gulp_init_namespace___stop_loading_wp_embed() {
    if (!is_admin()) {
        wp_deregister_script("wp-embed");
    }
}
add_action("init", "__gulp_init_namespace___stop_loading_wp_embed");

// disable Emoji
remove_action("wp_head", "print_emoji_detection_script", 7);
remove_action("wp_print_styles", "print_emoji_styles");

// load scripts asynchronously
function __gulp_init_namespace___make_scripts_async($tag, $handle, $src) {
    if (!is_admin()) {
        return str_replace(" src=", " defer='defer' src=", $tag);
        exit;
    }

    return $tag;
}
add_filter("script_loader_tag", "__gulp_init_namespace___make_scripts_async", 10, 3);

// load styles asynchronously
function __gulp_init_namespace___make_styles_async($tag, $handle, $src) {
    global $pagenow;
    global $template;

    $critical_css = __gulp_init_namespace___get_critical_css($template);
    $is_external  = __gulp_init_namespace___is_external_url($src);
    $is_other     = __gulp_init_namespace___is_other_asset($src);

    if (!is_admin() && $pagenow !== "wp-login.php" && ($critical_css || $is_external || $is_other)) {
        return str_replace("rel='stylesheet'", "rel='preload' as='style' " . (!(isset($_GET["debug"]) && $_GET["debug"] === "critical_css") ? "onload=\"this.rel='stylesheet'\"" : ""), $tag) . "<noscript>{$tag}</noscript>"; exit;
    }

    return $tag;
}
add_filter("style_loader_tag", "__gulp_init_namespace___make_styles_async", 10, 3);

// create a local copy of Google Analytics instead and serve that for caching purposes
function __gulp_init_namespace___cache_google_analytics($url) {
    $local_path = WP_CONTENT_DIR . "/cache/scripts/analytics.js";

    if (!file_exists($local_path) || date("c", filemtime($local_path)) <= date("c", strtotime("-2 hours"))) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $script = curl_exec($curl);

        curl_close($curl);

        if (!is_dir(WP_CONTENT_DIR . "/cache")) {
            mkdir(WP_CONTENT_DIR . "/cache");
        }

        if (!is_dir(WP_CONTENT_DIR . "/cache/scripts")) {
            mkdir(WP_CONTENT_DIR . "/cache/scripts");
        }

        file_put_contents($local_path, $script);
    }

    return WP_CONTENT_URL . "/cache/scripts/analytics.js";
}
add_filter("gadwp_analytics_script_path", "__gulp_init_namespace___cache_google_analytics");
