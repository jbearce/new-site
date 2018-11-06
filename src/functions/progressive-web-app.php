<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Menus
\* ------------------------------------------------------------------------ */

// change allowed service worker scope to the root
function __gulp_init_namespace___fix_service_worker_scope() {
    header("Service-Worker-Allowed: /");
}
add_action("wp", "__gulp_init_namespace___fix_service_worker_scope");

// construct a manifest when the user visits {theme_folder}/manifest.json
function __gulp_init_namespace___construct_manifest($template) {
    if (get_query_var("manifest")) {
        header("Content-Type: application/json");

        $name             = function_exists("get_field") ? get_field("full_name", "pwa") : false;
        $short_name       = function_exists("get_field") ? get_field("short_name", "pwa") : false;
        $background_color = function_exists("get_field") ? get_field("background_color", "pwa") : false;
        $theme_color      = function_exists("get_field") ? get_field("theme_color", "pwa") : false;

        $manifest = array(
            "start_url"        => "/",
            "display"          => "standalone",
            "name"             => $name ? $name : "<%= pwa_name %>",
            "short_name"       => $short_name ? $short_name : "<%= pwa_short_name %>",
            "background_color" => $background_color ? $background_color : "<%= pwa_theme_color %>",
            "theme_color"      => $theme_color ? $theme_color : "<%= pwa_theme_color %>",
            "icons"            => array(
                array(
                    "src"   => get_theme_file_uri("assets/media/android/splash-icon-512x512.png"),
                    "type"  => "image/png",
                    "sizes" => "512x512",
                ),
                array(
                    "src"   => get_theme_file_uri("assets/media/android/launcher-icon-192x192.png"),
                    "type"  => "image/png",
                    "sizes" => "192x192",
                ),
                array(
                    "src"   => get_theme_file_uri("assets/media/android/launcher-icon-144x144.png"),
                    "type"  => "image/png",
                    "sizes" => "144x144",
                ),
                array(
                    "src"   => get_theme_file_uri("assets/media/android/launcher-icon-96x96.png"),
                    "type"  => "image/png",
                    "sizes" => "96x96",
                ),
                array(
                    "src"   => get_theme_file_uri("assets/media/android/launcher-icon-72x72.png"),
                    "type"  => "image/png",
                    "sizes" => "72x72",
                ),
                array(
                    "src"   => get_theme_file_uri("assets/media/android/launcher-icon-48x48.png"),
                    "type"  => "image/png",
                    "sizes" => "48x48",
                ),
            ),
        );

        echo json_encode($manifest); exit;
    }
}
add_action("wp", "__gulp_init_namespace___construct_manifest", 0);

// add the PWA meta tags to the head
function __gulp_init_namespace___add_pwa_meta_to_head() {
    echo "<link href='" . get_theme_file_uri("manifest.json") . "' rel='manifest' />\n";
}
add_action("wp_head", "__gulp_init_namespace___add_pwa_meta_to_head", 0);

// add the iOS meta tags to the head
function __gulp_init_namespace___add_ios_meta_to_head() {
    // don't print if the user isn't on iOS
    if (!__gulp_init_namespace___is_platform("ios")) return;

    $theme_color = function_exists("get_field") ? get_field("theme_color", "pwa") : false;
    $theme_color = $theme_color ? $theme_color : "<%= pwa_theme_color %>";

    // iOS status bar color
    echo "<meta name='apple-mobile-web-app-status-bar-style' content='black-translucent' />";

    // iOS home screen icon
    echo "<link href='" . get_theme_file_uri("assets/media/ios/touch-icon-180x180.png") . "' rel='apple-touch-icon' />";

    // array of splash screen images for each iOS device
    $splash_screens = array(
        "iPhone_4_portrait" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-640x960.png"),
            "media" => "(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPhone_4_landscape" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-960x640.png"),
            "media" => "(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
        "iPhone_5_portrait" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-640x1136.png"),
            "media" => "(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPhone_5_landscape" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1136x640.png"),
            "media" => "(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
        "iPhone_6_6S_7_8_portrait" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-750x1334.png"),
            "media" => "(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPhone_6_6S_7_8_landscape" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1334x750.png"),
            "media" => "(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
        "iPhone_6+_6S+_7+_8+_portrait" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1242x2208.png"),
            "media" => "(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)",
        ),
        "iPhone_6+_6S+_7+_8+_landscape" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2208x1242.png"),
            "media" => "(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)",
        ),
        "iPhone_X_XS_portrait" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1125x2436.png"),
            "media" => "(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)",
        ),
        "iPhone_X_XS_landscape" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2436x1125.png"),
            "media" => "(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)",
        ),
        "iPhone_XR_portrait" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-828x1792.png"),
            "media" => "(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPhone_XR_landscape" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1792x828.png"),
            "media" => "(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
        "iPhone_XS-Max_portrait" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1242x2688.png"),
            "media" => "(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)",
        ),
        "iPhone_XS-Max_landscape" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2688x1242.png"),
            "media" => "(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)",
        ),
        "iPad_1-2-Mini_portrait" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-768x1024.png"),
            "media" => "(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 1) and (orientation: portrait)",
        ),
        "iPad_1_2_Mini_landscape" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1024x768.png"),
            "media" => "(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 1) and (orientation: landscape)",
        ),
        "iPad_3_4_Air_Mini-2_Pro-9.7_portrait" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1536x2048.png"),
            "media" => "(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPad_3_4_Air_Mini-2_Pro-9.7_landscape" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2048x1536.png"),
            "media" => "(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
        "iPad_Pro-10.5_portrait" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1668x2224.png"),
            "media" => "(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPad_Pro-10.5_landscape" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2224x1668.png"),
            "media" => "(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
        "iPad_Pro-12.9_portrait" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2048x2732.png"),
            "media" => "(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPad_Pro-12.9_landscape" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2732x2048.png"),
            "media" => "(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
    );

    foreach ($splash_screens as $splash_screen) {
        echo "<link href='{$splash_screen["href"]}' media='{$splash_screen["media"]}' rel='apple-touch-startup-image' />\n";
    }
}
add_action("wp_head", "__gulp_init_namespace___add_ios_meta_to_head", 0);

// load the "offline" template when the user visits /offline/
function __gulp_init_namespace___load_offline_template($template) {
    if (get_query_var("offline")) {
        return get_theme_file_path("/offline.php");
    }

    return $template;
}
add_action("template_include", "__gulp_init_namespace___load_offline_template");

// fix page title on "offline" template
function __gulp_init_namespace___fix_offline_page_title($title) {
    if (get_query_var("offline")) {
        return $title = sprintf(__("No Internet Connection - %s", "__gulp_init_namespace__"), get_bloginfo("name"));
    }

    return $title;
}
add_filter("wpseo_title", "__gulp_init_namespace___fix_offline_page_title");
