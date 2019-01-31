<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Styles & Scripts
\* ------------------------------------------------------------------------ */

// enqueue styles & scripts
function __gulp_init_namespace___enqueue_scripts() {
    global $template;

    /* styles */

    // Google fonts
    wp_register_style("__gulp_init_namespace__-google-fonts", "https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic");

    // modern styles
    wp_register_style("__gulp_init_namespace__-styles-modern", get_theme_file_uri(__gulp_init_namespace___get_theme_file_path("assets/styles/", "modern.*.css")), array(), "<%= version %>");

    // legacy styles
    wp_register_style("__gulp_init_namespace__-styles-legacy", get_theme_file_uri(__gulp_init_namespace___get_theme_file_path("assets/styles/", "legacy.*.css")), array("__gulp_init_namespace__-styles-modern"), "<%= version %>");

    // print styles
    wp_register_style("__gulp_init_namespace__-styles-print", get_theme_file_uri(__gulp_init_namespace___get_theme_file_path("assets/styles/", "print.*.css")), array("__gulp_init_namespace__-styles-modern"), "<%= version %>", "print");

    // critical styles
    $critical_styles = __gulp_init_namespace___get_critical_css($template);
    if ($critical_styles) wp_add_inline_style("__gulp_init_namespace__-styles-modern", $critical_styles);

    /* scripts */

    // FontAwesome scripts
    wp_register_script("__gulp_init_namespace__-scripts-fontawesome", get_theme_file_uri(__gulp_init_namespace___get_theme_file_path("assets/scripts/", "fontawesome.*.js")), array(), "<%= version %>", true);

    // modern scripts
    wp_register_script("__gulp_init_namespace__-scripts-modern", get_theme_file_uri(__gulp_init_namespace___get_theme_file_path("assets/scripts/", "modern.*.js")), array(), "<%= version %>", true);

    // legacy scripts
    wp_register_script("__gulp_init_namespace__-scripts-legacy", get_theme_file_uri(__gulp_init_namespace___get_theme_file_path("assets/scripts/", "legacy.*.js")), array(), "<%= version %>");

    // critical scripts
    if (!(isset($_GET["debug"]) && $_GET["debug"] === "critical_css")) {
        $critical_scripts = file_get_contents(get_theme_file_path(__gulp_init_namespace___get_theme_file_path("assets/scripts/", "critical.*.js")));
        wp_add_inline_script("__gulp_init_namespace__-scripts-modern", $critical_scripts);
    }

    // Service Worker
    $service_worker_uri = get_theme_file_uri(__gulp_init_namespace___get_theme_file_path("assets/scripts/", "service-worker.js"));
    wp_add_inline_script("__gulp_init_namespace__-scripts-modern", "
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('{$service_worker_uri}', { scope: '/' }).then(function (registration) {
                    // attempt to update the service worker
                    registration.update();
                });
            });
        }
    ");

    /* localize scripts */

    $l10n = array(
        "noty" => array(
            "offline" => array(
                "text" => __("You appear to be offline right now. Some parts of this site may be unavailable until you come back online.", "__gulp_init_namespace__"),
            ),
        ),
    );

    wp_localize_script("__gulp_init_namespace__-scripts-modern", "l10n", $l10n);
    wp_localize_script("__gulp_init_namespace__-scripts-legacy", "l10n", $l10n);

    /* enqueue everything */

    wp_enqueue_style("__gulp_init_namespace__-styles-modern");
    wp_enqueue_style("__gulp_init_namespace__-styles-legacy");
    wp_enqueue_style("__gulp_init_namespace__-styles-print");

    if (!(isset($_GET["disable"]) && $_GET["disable"] === "critical_css") ) {
        wp_enqueue_style("__gulp_init_namespace__-styles-google-fonts");
    }

    wp_enqueue_script("__gulp_init_namespace__-scripts-fontawesome");
    wp_enqueue_script("__gulp_init_namespace__-scripts-modern");
    wp_enqueue_script("__gulp_init_namespace__-scripts-legacy");

    /* mark legacy stuff as <= IE9 */

    wp_style_add_data("__gulp_init_namespace__-styles-legacy", "conditional", "lte IE 9");
    wp_script_add_data("__gulp_init_namespace__-scripts-legacy", "conditional", "lte IE 9");
}
add_action("get_header", "__gulp_init_namespace___enqueue_scripts");

// add noscript tag to the head to hide elemetns with the .__js class if script isn't enabled
function __gulp_init_namespace___noscript_hide_js_elements() {
    echo "<noscript><style>.__js {display: none !important;}</style></noscript>\n";
}
add_action("wp_head", "__gulp_init_namespace___noscript_hide_js_elements");

// adjust WordPress login screen styles
function __gulp_init_namespace___enqueue_scripts_login() {
    wp_enqueue_style("__gulp_init_namespace__-styles-login", get_theme_file_uri(__gulp_init_namespace___get_theme_file_path("assets/styles/", "wp-login.*.css")), array(), "<%= version %>");
}
add_action("login_enqueue_scripts", "__gulp_init_namespace___enqueue_scripts_login");

// add BrowserSync script to the footer when active
function __gulp_init_namespace___simplify_browsersync() {
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
add_action("wp_footer", "__gulp_init_namespace___simplify_browsersync", 999);

// add editor styles to Gutenberg
function __gulp_init_namespace___gutenberg_styles() {
	 wp_enqueue_style("__gulp_init_namespace__-styles-editor", get_theme_file_uri(__gulp_init_namespace___get_theme_file_path("assets/styles/", "editor.*.css")), false, '<%= version %>' );
}
add_action("enqueue_block_editor_assets", "__gulp_init_namespace___gutenberg_styles");
