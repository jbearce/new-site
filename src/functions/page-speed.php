<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Page Speed
\* ------------------------------------------------------------------------ */

/**
 * Push assets and preconnections over HTTP/2
 */
function __gulp_init_namespace___resource_hints($urls, $relation_type) {
    if ($relation_type === "preconnect") {
        $urls[] = "https://www.google-analytics.com";
        $urls[] = "https://www.gstatic.com";
        $urls[] = "https://www.google.com";
    }

    if ($relation_type !== "prefetch") {
        return $urls;
    }

    if ($GLOBALS["wp_scripts"]) {
        foreach ($GLOBALS["wp_scripts"]->queue as $script) {
            $data = $GLOBALS["wp_scripts"]->registered[$script];

            if ($data->src && !isset($data->extra["conditional"])) {
                $urls[] = $data->src;
            }
        }
    }

    if ($GLOBALS["wp_styles"]) {
        foreach ($GLOBALS["wp_styles"]->queue as $style) {
            $data = $GLOBALS["wp_styles"]->registered[$style];

            if ($data->src && !isset($data->extra["conditional"])) {
                $urls[] = $data->src;
            }
        }
    }

    return $urls;
}
add_filter("wp_resource_hints", "__gulp_init_namespace___resource_hints", 10, 2);

/**
 * Remove version strings
 */
function __gulp_init_namespace___remove_script_version($src) {
    if ($src) {
        $parts = explode("?ver", $src);
        $src   = $parts[0];
    }

    return $src;
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
function __gulp_init_namespace___make_scripts_async($tag, $handle) {
    if (!is_admin() && !in_array($handle, array())) {
        $tag = str_replace(" src=", " defer='defer' src=", $tag);
    }

    return $tag;
}
add_filter("script_loader_tag", "__gulp_init_namespace___make_scripts_async", 10, 2);

/**
 * Load styles asynchronously when critical CSS is present
 */
function __gulp_init_namespace___make_styles_async($tag, $handle, $src) {
    $pagenow  = $GLOBALS["pagenow"];
    $template = isset($GLOBALS["template"]) ? $GLOBALS["template"] : false;

    $is_login     = (isset($_SERVER["SCRIPT_URI"]) && $_SERVER["SCRIPT_URI"] === wp_login_url()) || $pagenow === "wp-login.php";
    $critical_css = __gulp_init_namespace___get_critical_css($template);
    $is_external  = __gulp_init_namespace___is_external_url($src);
    $is_other     = __gulp_init_namespace___is_other_asset($src);

    if (!is_admin() && !$is_login && ($critical_css || $is_external || $is_other) && !in_array($handle, array())) {
        $tag = str_replace("rel='stylesheet'", "rel='preload' as='style' " . (!(isset($_GET["debug"]) && $_GET["debug"] === "critical_css") ? "onload=\"this.rel='stylesheet'\"" : ""), $tag) . "<noscript>{$tag}</noscript>";
    }

    return $tag;
}
add_filter("style_loader_tag", "__gulp_init_namespace___make_styles_async", 10, 3);

/**
 * Add critical CSS to the top of wp_head
 */
function __gulp_init_namespace___critical_css() {
    $template = $GLOBALS["template"];

    // critical styles
    $critical_styles = __gulp_init_namespace___get_critical_css($template);
    if ($critical_styles) {
        echo "<style type='text/css'>{$critical_styles}</style>";
    }
}
add_action("wp_head", "__gulp_init_namespace___critical_css", 5, 0);

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
