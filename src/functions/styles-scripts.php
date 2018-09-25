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

    // modern scripts
    wp_register_script("__gulp_init__namespace-scripts-modern", get_theme_file_uri("assets/scripts/modern.js"), array(), "@@version", true);

    // legacy scripts
    wp_register_script("__gulp_init__namespace-scripts-legacy", get_theme_file_uri("assets/scripts/legacy.js"), array(), "@@version");

    // critical scripts
    $critical_scripts = file_get_contents(get_theme_file_path("assets/scripts/critical.js"));
    wp_add_inline_script("__gulp_init__namespace-scripts-modern", $critical_scripts);

    // Service Worker
    $service_worker_uri = get_site_url(null, "service-worker.js");
    wp_add_inline_script("__gulp_init__namespace-scripts-modern", apply_filters("__gulp_init__namespace_service_worker_register", "
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('{$service_worker_uri}');
            });
        }
    "));

    /* localize scripts */

    $l10n = array(
        "noty" => array(
            "offline" => array(
                "text" => __("You appear to be offline right now. Some parts of this site may be unavailable until you come back online.", "__gulp_init__namespace"),
            ),
        ),
    );

    wp_localize_script("__gulp_init__namespace-scripts-modern", "l10n", $l10n);
    wp_localize_script("__gulp_init__namespace-scripts-legacy", "l10n", $l10n);

    /* enqueue everything */

    wp_enqueue_style("__gulp_init__namespace-styles-google-fonts");
    wp_enqueue_style("__gulp_init__namespace-styles-modern");
    wp_enqueue_style("__gulp_init__namespace-styles-legacy");
    wp_enqueue_style("__gulp_init__namespace-styles-print");

    wp_enqueue_script("__gulp_init__namespace-scripts-modern");
    wp_enqueue_script("__gulp_init__namespace-scripts-legacy");

    /* mark legacy stuff as <= IE9 */

    wp_style_add_data("__gulp_init__namespace-styles-legacy", "conditional", "lte IE 9");
    wp_script_add_data("__gulp_init__namespace-scripts-legacy", "conditional", "lte IE 9");
}
add_action("wp", "__gulp_init__namespace_enqueue_scripts");

// adjust WordPress login screen styles
function __gulp_init__namespace_enqueue_scripts_login() {
    wp_enqueue_style("__gulp_init__namespace-styles-login", get_bloginfo("template_directory") . "/assets/styles/wp-login.css", array(), "@@version");
}
add_action("login_enqueue_scripts", "__gulp_init__namespace_enqueue_scripts_login");
