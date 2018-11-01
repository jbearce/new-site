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
add_action("wp", "__gulp_init_namespace___construct_manifest");

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
