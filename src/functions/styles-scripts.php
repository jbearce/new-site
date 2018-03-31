<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Styles & Scripts
\* ------------------------------------------------------------------------ */

// enqueue styles & scripts
function __gulp_init__namespace_enqueue_scripts() {
    global $template;

    /* styles */

    // Google fonts
    wp_register_style("__gulp_init__namespace-google-fonts", "https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic");

    // modern styles
    wp_register_style("__gulp_init__namespace-styles-modern", get_theme_file_uri("assets/styles/modern.css"), array("__gulp_init__namespace-google-fonts"), "@@version");

    // legacy styles
    wp_register_style("__gulp_init__namespace-styles-legacy", get_theme_file_uri("assets/styles/legacy.css"), array("__gulp_init__namespace-styles-modern"), "@@version");

    // print styles
    wp_register_style("__gulp_init__namespace-styles-print", get_theme_file_uri("assets/styles/print.css"), array("__gulp_init__namespace-styles-modern"), "@@version", "print");

    // critical styles
    $critical_styles = get_critical_css($template);
    if ($critical_styles) wp_add_inline_style("__gulp_init__namespace-styles-modern", $critical_styles);

    /* scripts */

    // FontAwesome 5 Pro
    wp_register_script("__gulp_init__namespace-scripts-fontawesome", "https://pro.fontawesome.com/releases/v5.0.8/js/all.js", array(), "@@version");

    // modern scripts
    wp_register_script("__gulp_init__namespace-scripts-modern", get_theme_file_uri("assets/scripts/modern.js"), array(), "@@version", true);

    // legacy scripts
    wp_register_script("__gulp_init__namespace-scripts-legacy", get_theme_file_uri("assets/scripts/legacy.js"), array(), "@@version");

    // critical scripts
    $critical_scripts = file_get_contents(get_theme_file_path("assets/scripts/critical.js"));
    wp_add_inline_script("__gulp_init__namespace-scripts-modern", $critical_scripts);

    // Service Worker
    $service_worker_uri = get_theme_file_uri("assets/scripts/service-worker.js");
    wp_add_inline_script("__gulp_init__namespace-scripts-modern", apply_filters("__gulp_init__namespace_service_worker_register", `
        if ("serviceWorker" in navigator) {
            window.addEventListener("load", () => {
                navigator.serviceWorker.register("{$service_worker_uri}").then((registration) => {
                    console.log("Success: Service worker successfully registered!");
                }).catch((error) => {
                    console.log("Error: ", error);
                });
            });
        }
    `));

    /* enqueue everything */

    wp_enqueue_style("__gulp_init__namespace-styles-google-fonts");
    wp_enqueue_style("__gulp_init__namespace-styles-modern");
    wp_enqueue_style("__gulp_init__namespace-styles-legacy");
    wp_enqueue_style("__gulp_init__namespace-styles-print");

    wp_enqueue_script("__gulp_init__namespace-scripts-fontawesome");
    wp_enqueue_script("__gulp_init__namespace-scripts-modern");
    wp_enqueue_script("__gulp_init__namespace-scripts-legacy");
    wp_enqueue_script("__gulp_init__namespace-scripts-critical");

    /* mark legacy stuff as <= IE9 */

    wp_style_add_data("__gulp_init__namespace-styles-legacy", "conditional", "lte IE 9");
    wp_script_add_data("__gulp_init__namespace-scripts-legacy", "conditional", "lte IE 9");
}
add_action("wp_enqueue_scripts", "__gulp_init__namespace_enqueue_scripts");


// add licenisng attributes to FontAwesome 5 Pro script
function __gulp_init__namespace_add_fontawesome_license_attributes($tag, $handle, $src) {
    // <script defer src="https://pro.fontawesome.com/releases/v5.0.8/js/all.js" integrity="sha384-816IUmmhAwCMonQiPZBO/PTgzgsjHtpb78rpsLzldhb4HZjFzBl06Z3eu4ZuwHTz" crossorigin="anonymous"></script>

    if (!is_admin() && $handle === "__gulp_init__namespace-scripts-fontawesome") {
        return str_replace("<script", "<script integrity='sha384-816IUmmhAwCMonQiPZBO/PTgzgsjHtpb78rpsLzldhb4HZjFzBl06Z3eu4ZuwHTz' crossorigin='anonymous'", $tag);
    }

    return $tag;
}
add_filter("script_loader_tag", "__gulp_init__namespace_add_fontawesome_license_attributes", 10, 3);
