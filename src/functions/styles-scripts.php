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
    wp_register_style("__gulp_init__namespace-styles-modern", get_theme_file_uri(__gulp_init__namespace_get_theme_file_path_hashed("assets/styles/", "modern.*.css")), array("__gulp_init__namespace-google-fonts"), "@@version");

    // legacy styles
    wp_register_style("__gulp_init__namespace-styles-legacy", get_theme_file_uri(__gulp_init__namespace_get_theme_file_path_hashed("assets/styles/", "legacy.*.css")), array("__gulp_init__namespace-styles-modern"), "@@version");

    // print styles
    wp_register_style("__gulp_init__namespace-styles-print", get_theme_file_uri(__gulp_init__namespace_get_theme_file_path_hashed("assets/styles/", "print.*.css")), array("__gulp_init__namespace-styles-modern"), "@@version", "print");

    // critical styles
    $critical_styles = get_critical_css($template);
    if ($critical_styles) wp_add_inline_style("__gulp_init__namespace-styles-modern", $critical_styles);

    /* scripts */

    // modern scripts
    wp_register_script("__gulp_init__namespace-scripts-modern", get_theme_file_uri(__gulp_init__namespace_get_theme_file_path_hashed("assets/scripts/", "modern.*.js")), array(), "@@version", true);

    // legacy scripts
    wp_register_script("__gulp_init__namespace-scripts-legacy", get_theme_file_uri(__gulp_init__namespace_get_theme_file_path_hashed("assets/scripts/", "legacy.*.js")), array(), "@@version");

    // critical scripts
    $critical_scripts = file_get_contents(get_theme_file_path(__gulp_init__namespace_get_theme_file_path_hashed("assets/scripts/", "critical.*.js")));
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

// add BrowserSync script to the footer when active
function __gulp_init__namespace_simplify_browsersync() {
    $browsersync_port    = isset($_SERVER["HTTP_X_BROWSERSYNC_PORT"]) ? $_SERVER["HTTP_X_BROWSERSYNC_PORT"] : false;
    $browsersync_version = isset($_SERVER["HTTP_X_BROWSERSYNC_VERSION"]) ? $_SERVER["HTTP_X_BROWSERSYNC_VERSION"] : false;

    if ($browsersync_port && $browsersync_version) {
        $server_protocol = stripos($_SERVER["SERVER_PROTOCOL"], "https") === true ? "https://" : "http://"; ?>
        <script id="__bs_script__">
            //<![CDATA[
                document.write("<script async src='<?php echo $server_protocol; ?>HOST:<?php echo $browsersync_port; ?>/browser-sync/browser-sync-client.js?v=<?php echo $browsersync_version; ?>'><\/script>".replace("HOST", location.hostname));
            //]]>
        </script>
    <?php }
}
add_action("wp_footer", "__gulp_init__namespace_simplify_browsersync", 999);
