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
function __gulp_init_namespace___construct_manifest() {
    if (get_query_var("manifest")) {
        header("Content-Type: application/json");

        $name             = __gulp_init_namespace___get_field("full_name", "pwa");
        $short_name       = __gulp_init_namespace___get_field("short_name", "pwa");
        $background_color = __gulp_init_namespace___get_field("background_color", "pwa");
        $theme_color      = __gulp_init_namespace___get_field("theme_color", "pwa");

        $manifest = array(
            "start_url"        => "/",
            "display"          => "standalone",
            "name"             => $name ? $name : "<%= pwa_name %>",
            "short_name"       => $short_name ? $short_name : "<%= pwa_short_name %>",
            "background_color" => $background_color ? $background_color : "<%= pwa_theme_color %>",
            "theme_color"      => $theme_color ? $theme_color : "<%= pwa_theme_color %>",
            "icons"            => array(
                array(
                    "src"      => get_theme_file_uri("assets/media/android/splash-icon-512x512.png"),
                    "type"     => "image/png",
                    "sizes"    => "512x512",
                    "platform" => "android",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/android/launcher-icon-192x192.png"),
                    "type"     => "image/png",
                    "sizes"    => "192x192",
                    "platform" => "android",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/android/launcher-icon-144x144.png"),
                    "type"     => "image/png",
                    "sizes"    => "144x144",
                    "platform" => "android",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/android/launcher-icon-96x96.png"),
                    "type"     => "image/png",
                    "sizes"    => "96x96",
                    "platform" => "android",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/android/launcher-icon-72x72.png"),
                    "type"     => "image/png",
                    "sizes"    => "72x72",
                    "platform" => "android",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/android/launcher-icon-48x48.png"),
                    "type"     => "image/png",
                    "sizes"    => "48x48",
                    "platform" => "android",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/android/notification-icon-96x96.png"),
                    "type"     => "image/png",
                    "sizes"    => "96x96",
                    "platform" => "android",
                    "purpose"  => "badge",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/android/notification-icon-72x72.png"),
                    "type"     => "image/png",
                    "sizes"    => "72x72",
                    "platform" => "android",
                    "purpose"  => "badge",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/android/notification-icon-48x48.png"),
                    "type"     => "image/png",
                    "sizes"    => "48x48",
                    "platform" => "android",
                    "purpose"  => "badge",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/android/notification-icon-36x36.png"),
                    "type"     => "image/png",
                    "sizes"    => "36x36",
                    "platform" => "android",
                    "purpose"  => "badge",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/android/notification-icon-24x24.png"),
                    "type"     => "image/png",
                    "sizes"    => "24x24",
                    "platform" => "android",
                    "purpose"  => "badge",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-640x960.png"),
                    "type"     => "image/png",
                    "sizes"    => "640x960",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-960x640.png"),
                    "type"     => "image/png",
                    "sizes"    => "960x640",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-640x1136.png"),
                    "type"     => "image/png",
                    "sizes"    => "640x1136",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-1136x640.png"),
                    "type"     => "image/png",
                    "sizes"    => "1136x640",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-750x1334.png"),
                    "type"     => "image/png",
                    "sizes"    => "750x1334",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-1334x750.png"),
                    "type"     => "image/png",
                    "sizes"    => "1334x750",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-1242x2208.png"),
                    "type"     => "image/png",
                    "sizes"    => "1242x2208",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-2208x1242.png"),
                    "type"     => "image/png",
                    "sizes"    => "2208x1242",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-1125x2436.png"),
                    "type"     => "image/png",
                    "sizes"    => "1125x2436",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-2436x1125.png"),
                    "type"     => "image/png",
                    "sizes"    => "2436x1125",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-828x1792.png"),
                    "type"     => "image/png",
                    "sizes"    => "828x1792",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-1792x828.png"),
                    "type"     => "image/png",
                    "sizes"    => "1792x828",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-1242x2688.png"),
                    "type"     => "image/png",
                    "sizes"    => "1242x2688",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-2688x1242.png"),
                    "type"     => "image/png",
                    "sizes"    => "2688x1242",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-768x1024.png"),
                    "type"     => "image/png",
                    "sizes"    => "768x1024",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-1024x768.png"),
                    "type"     => "image/png",
                    "sizes"    => "1024x768",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-1536x2048.png"),
                    "type"     => "image/png",
                    "sizes"    => "1536x2048",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-2048x1536.png"),
                    "type"     => "image/png",
                    "sizes"    => "2048x1536",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-1668x2224.png"),
                    "type"     => "image/png",
                    "sizes"    => "1668x2224",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-2224x1668.png"),
                    "type"     => "image/png",
                    "sizes"    => "2224x1668",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-1668x2388.png"),
                    "type"     => "image/png",
                    "sizes"    => "1668x2388",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-2388x1668.png"),
                    "type"     => "image/png",
                    "sizes"    => "2388x1668",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-2048x2732.png"),
                    "type"     => "image/png",
                    "sizes"    => "2048x2732",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/startup-image-2732x2048.png"),
                    "type"     => "image/png",
                    "sizes"    => "2732x2048",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/touch-icon-1024x1024.png"),
                    "type"     => "image/png",
                    "sizes"    => "1024x1024",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/touch-icon-180x180.png"),
                    "type"     => "image/png",
                    "sizes"    => "180x180",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/touch-icon-167x167.png"),
                    "type"     => "image/png",
                    "sizes"    => "167x167",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/touch-icon-152x152.png"),
                    "type"     => "image/png",
                    "sizes"    => "152x152",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/touch-icon-120x120.png"),
                    "type"     => "image/png",
                    "sizes"    => "120x120",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/touch-icon-76x76.png"),
                    "type"     => "image/png",
                    "sizes"    => "76x76",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/spotlight-icon-120x120.png"),
                    "type"     => "image/png",
                    "sizes"    => "120x120",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/spotlight-icon-80x80.png"),
                    "type"     => "image/png",
                    "sizes"    => "80x80",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/settings-icon-87x87.png"),
                    "type"     => "image/png",
                    "sizes"    => "87x87",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/settings-icon-58x58.png"),
                    "type"     => "image/png",
                    "sizes"    => "58x58",
                    "platform" => "ios",
                    "purpose"  => "any",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/notification-icon-60x60.png"),
                    "type"     => "image/png",
                    "sizes"    => "60x60",
                    "platform" => "ios",
                    "purpose"  => "badge",
                ),
                array(
                    "src"      => get_theme_file_uri("assets/media/ios/notification-icon-40x40.png"),
                    "type"     => "image/png",
                    "sizes"    => "40x40",
                    "platform" => "ios",
                    "purpose"  => "badge",
                ),
            ),
        );

        echo json_encode($manifest);
        exit;
    }
}
add_action("wp", "__gulp_init_namespace___construct_manifest", 0);

// add the PWA meta tags to the head
function __gulp_init_namespace___add_pwa_meta_to_head() {
    echo "<link href='" . get_theme_file_uri("manifest.json") . "' rel='manifest' />\n";
}
add_action("admin_head", "__gulp_init_namespace___add_pwa_meta_to_head", 0);
add_action("login_head", "__gulp_init_namespace___add_pwa_meta_to_head", 0);
add_action("wp_head", "__gulp_init_namespace___add_pwa_meta_to_head", 0);

// add the iOS meta tags to the head
function __gulp_init_namespace___add_ios_meta_to_head() {
    // don't print if the user isn't on iOS
    if (!__gulp_init_namespace___is_platform("ios")) {
        return;
    }

    // declare web app support
    echo "<meta name='apple-mobile-web-app-capable' content='yes' />\n";

    // set status bar color
    echo "<meta name='apple-mobile-web-app-status-bar-style' content='black-translucent' />\n";

    // set home screen icons
    echo "<link href='" . get_theme_file_uri("assets/media/ios/touch-icon-76x76.png") . "' rel='apple-touch-icon' sizes='76x76' />\n";
    echo "<link href='" . get_theme_file_uri("assets/media/ios/touch-icon-120x120.png") . "' rel='apple-touch-icon' sizes='120x120' />\n";
    echo "<link href='" . get_theme_file_uri("assets/media/ios/touch-icon-152x152.png") . "' rel='apple-touch-icon' sizes='152x152' />\n";
    echo "<link href='" . get_theme_file_uri("assets/media/ios/touch-icon-180x180.png") . "' rel='apple-touch-icon' sizes='180x180' />\n";
    echo "<link href='" . get_theme_file_uri("assets/media/ios/touch-icon-1024x1024.png") . "' rel='apple-touch-icon' sizes='1024x1024' />\n";

    // array of splash screen images for each iOS device
    $splash_screens = array(
        "iPhone 4 (portrait)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-640x960.png"),
            "media" => "(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPhone 4 (landscape)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-960x640.png"),
            "media" => "(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
        "iPhone 5 (portrait)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-640x1136.png"),
            "media" => "(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPhone 5 (landscape)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1136x640.png"),
            "media" => "(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
        "iPhone 6, 6S, 7, 8 (portrait)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-750x1334.png"),
            "media" => "(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPhone 6, 6S, 7, 8 (landscape)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1334x750.png"),
            "media" => "(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
        "iPhone 6+, 6S+, 7+, 8+ (portrait)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1242x2208.png"),
            "media" => "(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)",
        ),
        "iPhone 6+, 6S+, 7+, 8+ (landscape)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2208x1242.png"),
            "media" => "(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)",
        ),
        "iPhone X, XS (portrait)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1125x2436.png"),
            "media" => "(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)",
        ),
        "iPhone X, XS (landscape)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2436x1125.png"),
            "media" => "(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)",
        ),
        "iPhone XR (portrait)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-828x1792.png"),
            "media" => "(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPhone XR (landscape)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1792x828.png"),
            "media" => "(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
        "iPhone XS Max (portrait)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1242x2688.png"),
            "media" => "(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)",
        ),
        "iPhone XS Max (landscape)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2688x1242.png"),
            "media" => "(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)",
        ),
        "iPad 1, 2, Mini (portrait)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-768x1024.png"),
            "media" => "(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 1) and (orientation: portrait)",
        ),
        "iPad 1, 2, Mini (landscape)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1024x768.png"),
            "media" => "(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 1) and (orientation: landscape)",
        ),
        "iPad 3, 4, Air, Mini 2, Pro 9.7 (portrait)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1536x2048.png"),
            "media" => "(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPad 3, 4, Air, Mini 2, Pro 9.7 (landscape)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2048x1536.png"),
            "media" => "(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
        "iPad Pro 10.5 (portrait)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1668x2224.png"),
            "media" => "(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPad Pro 10.5 (landscape)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2224x1668.png"),
            "media" => "(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
        "iPad Pro 11 (portrait)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-1668x2388.png"),
            "media" => "(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPad Pro 11 (landscape)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2388x1668.png"),
            "media" => "(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
        "iPad Pro 12.9 (portrait)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2048x2732.png"),
            "media" => "(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)",
        ),
        "iPad Pro 12.9 (landscape)" => array(
            "href"  => get_theme_file_uri("assets/media/ios/startup-image-2732x2048.png"),
            "media" => "(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)",
        ),
    );

    foreach ($splash_screens as $splash_screen) {
        echo "<link href='{$splash_screen["href"]}' media='{$splash_screen["media"]}' rel='apple-touch-startup-image' />\n";
    }
}
add_action("admin_head", "__gulp_init_namespace___add_ios_meta_to_head", 0);
add_action("login_head", "__gulp_init_namespace___add_ios_meta_to_head", 0);
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
