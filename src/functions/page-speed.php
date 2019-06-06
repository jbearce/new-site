<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Page Speed
\* ------------------------------------------------------------------------ */

/**
 * Push CSS over HTTP/2
 */
function __gulp_init_namespace___http2_push() {
    global $wp_styles;

    $http2_string = "";

    foreach ($wp_styles->queue as $style) {
        $data = $wp_styles->registered[$style];

        // only push over HTTP/2 if no condtional tags exist (exclude IE styles)
        if (!isset($data->extra["conditional"])) {
            $http2_string .= ($http2_string !== "" ? ", " : "") . "<{$data->src}>; rel=preload; as=style";
        }
    }

    header("Link: {$http2_string}");
}
add_action("wp_enqueue_scripts", "__gulp_init_namespace___http2_push", 11);

/**
 * Remove version strings
 */
function __gulp_init_namespace___remove_script_version($src) {
    $parts = explode("?ver", $src);
    return $parts[0];
}
add_filter("script_loader_src", "__gulp_init_namespace___remove_script_version", 15, 1);
add_filter("style_loader_src", "__gulp_init_namespace___remove_script_version", 15, 1);

/**
 * Disable scripts that WordPress inserts which aren't used by this theme
 */
function __gulp_init_namespace___wp_disable_default_scripts() {
    if (!is_admin()) {
        wp_deregister_style("wp-block-library");
        wp_deregister_script("wp-embed");
    }
}
add_action("init", "__gulp_init_namespace___wp_disable_default_scripts");

/**
 * Disable Emoji
 */
remove_action("wp_head", "print_emoji_detection_script", 7);
remove_action("wp_print_styles", "print_emoji_styles");

/**
 * Load all JavaScript asynchronously
 */
function __gulp_init_namespace___make_scripts_async($tag, $handle, $src) {
    if (!is_admin()) {
        return str_replace(" src=", " defer='defer' src=", $tag);
        exit;
    }

    return $tag;
}
add_filter("script_loader_tag", "__gulp_init_namespace___make_scripts_async", 10, 3);

/**
 * Load styles asynchronously when critical CSS is present
 */
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

/**
 * Cache Google Analytics JavaScript to better control how it loads
 * (updates every 2 hours)
 */
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
