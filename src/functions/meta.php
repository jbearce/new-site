<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Menus
\* ------------------------------------------------------------------------ */

// add the favicon meta tags to the head
function __gulp_init_namespace___add_favicon_meta_to_head() {
    echo "<link href='" . get_theme_file_uri("assets/media/logo-favicon.png") . "' rel='shortcut icon' />\n";
}
add_action("admin_head", "__gulp_init_namespace___add_favicon_meta_to_head");
add_action("wp_head", "__gulp_init_namespace___add_favicon_meta_to_head");

// add the iOS meta tags to the head
function __gulp_init_namespace___add_ios_meta_to_head() {
    // don't print if the user isn't on iOS
    if (!__gulp_init_namespace___is_platform("ios")) return;

    $theme_color = function_exists("get_field") ? get_field("theme_color", "pwa") : false;
    $theme_color = $theme_color ? $theme_color : "<%= pwa_theme_color %>";

    // iOS status bar color
    echo "<meta name='apple-mobile-web-app-status-bar-style' content='{$theme_color}' />";

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
add_action("wp_head", "__gulp_init_namespace___add_ios_meta_to_head");

// add the PWA meta tags to the head
function __gulp_init_namespace___add_pwa_meta_to_head() {
    echo "<link href='" . get_theme_file_uri("manifest.json") . "' rel='manifest' />\n";
}
add_action("wp_head", "__gulp_init_namespace___add_pwa_meta_to_head");

// add the settings meta tags to the head
function __gulp_init_namespace___add_settings_meta_to_head() {
    echo "<meta content='text/html;charset=utf-8' http-equiv='content-type' />\n";
    echo "<meta content='width=device-width, initial-scale=1' name='viewport' />\n";
}
add_action("wp_head", "__gulp_init_namespace___add_settings_meta_to_head");

// add the theme color meta tags to the head
function __gulp_init_namespace___add_theme_color_meta_to_head() {
    $theme_color = function_exists("get_field") ? get_field("theme_color", "pwa") : false;
    $theme_color = $theme_color ? $theme_color : "<%= pwa_theme_color %>";

    // Chrome
    if (__gulp_init_namespace___is_platform("chrome")) {
        echo "<meta name='theme-color' content='{$theme_color}' />\n";
    }

    // Safari
    if (__gulp_init_namespace___is_platform("safari")) {
        echo "<link rel='mask-icon' href='" . get_theme_file_uri("assets/media/safari/mask-icon.svg") . "' color='{$theme_color}' />\n";
    }

    // Internet Explorer
    if (__gulp_init_namespace___is_platform("ie")) {
        echo "<meta name='msapplication-navbutton-color' content='{$theme_color}'>\n";
    }
}
add_action("wp_head", "__gulp_init_namespace___add_theme_color_meta_to_head");
