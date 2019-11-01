<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Ninja Forms
\* ------------------------------------------------------------------------ */

/**
 * Disable display styles
 *
 * @return void
 */
function __gulp_init_namespace___ninja_forms_dequeue_display_styles(): void {
    wp_dequeue_style("nf-display");
}
add_action("ninja_forms_enqueue_scripts", "__gulp_init_namespace___ninja_forms_dequeue_display_styles", 999);

/**
 * Change order of scripts so that `nf-front-end` always comes after all dependencies
 *
 * @return void
 */
function __gulp_init_namespace___ninja_forms_fix_scripts_order(): void {
    $wp_scripts = $GLOBALS["wp_scripts"];

    // match every script prefixed with `nf-`, except `nf-front-end` and `nf-front-end-deps`
    $pattern = "/^nf-(?!front-end(?:-deps)?$)/";

    // change all `nf-front-end` dependencies to `nf-front-end-deps`
    foreach ($wp_scripts->registered as $script) {
        if (!preg_match($pattern, $script->handle)) {
            continue;
        }

        if (!($key = array_search("nf-front-end", $script->deps))) {
            continue;
        }

        $script->deps[$key] = "nf-front-end-deps";
    }

    $last_nf_key      = false;
    $front_end_nf_key = array_search("nf-front-end", $wp_scripts->queue);

    // find the nast `nf-` prefixed script in the queue
    foreach ($wp_scripts->queue as $key => $handle) {
        if (preg_match($pattern, $handle)) {
            $last_nf_key = $key;
        }
    }

    // move `nf-front-end` just after the last `nf-` prefixed script in the queue
    if ($last_nf_key !== false) {
        unset($wp_scripts->queue[$front_end_nf_key]);
        array_splice($wp_scripts->queue, $last_nf_key, 0, "nf-front-end");
    }
}
add_action("nf_display_enqueue_scripts", "__gulp_init_namespace___ninja_forms_fix_scripts_order");

/**
 * Fix page title on "ninja forms" pages
 *
 * @param  string $title
 *
 * @return string
 */
function __gulp_init_namespace___ninja_forms_fix_wpseo_title(string $title): string {
    $wpdb = $GLOBALS["wpdb"];

    if ($public_link_key = get_query_var("nf_public_link")) {
        $query      = $wpdb->prepare("SELECT `parent_id` FROM {$wpdb->prefix}nf3_form_meta WHERE `key` = 'public_link_key' AND `value` = %s", $public_link_key);
        $results    = $wpdb->get_col($query);
        $form_id    = reset($results);

        $query      = $wpdb->prepare("SELECT `title` FROM {$wpdb->prefix}nf3_forms WHERE `id` = %s", $form_id);
        $results    = $wpdb->get_col($query);
        $form_title = reset($results);

        $title = sanitize_text_field($form_title) . " - " . get_bloginfo("name");
    }

    return $title;
}
add_filter("wpseo_title", "__gulp_init_namespace___ninja_forms_fix_wpseo_title");

/**
 * Redirect to the page template if a ninja form is being viewed
 *
 * @param  string $template
 *
 * @return string
 */
function __gulp_init_namespace___ninja_forms_fix_template(string $template): string {
    if (get_query_var("nf_public_link")) {
        $template = locate_template(array("page.php", "index.php"));
    }

    return $template;
}
add_filter("template_include", "__gulp_init_namespace___ninja_forms_fix_template");

/**
 * Fix various HTML field formatting
 *
 * @param  array<array<mixed>> $fields
 *
 * @return array<array<mixed>>
 */
function __gulp_init_namespace___ninja_forms_format_html(array $fields): array {
    if (is_admin()) return $fields;

    foreach ($fields as $key => $field) {
        if (isset($field["desc_text"]) && trim($field["desc_text"])) {
            $fields[$key]["desc_text"] = apply_filters("the_content", stripslashes($field["desc_text"]));
        }

        if (isset($field["help_text"]) && trim($field["help_text"])) {
            $fields[$key]["help_text"] = apply_filters("the_content", stripslashes($field["help_text"]));
        }

        if (isset($field["type"]) && $field["type"] === "html" && isset($field["value"]) && trim($field["value"])) {
            $fields[$key]["value"] = apply_filters("the_content", stripslashes($field["value"]));
        }
    }

    return $fields;
}
add_filter("ninja_forms_display_fields", "__gulp_init_namespace___ninja_forms_format_html", 10, 1);

/**
 * Fix success message formatting
 *
 * @param  array<mixed> $action_settings
 *
 * @return array<mixed>
 */
function __gulp_init_namespace___ninja_forms_format_success_message(array $action_settings): array {
    if ($action_settings["type"] === "successmessage") {
        $action_settings["success_msg"] = apply_filters("the_content", $action_settings["success_msg"]);
    }

    return $action_settings;
}
add_filter("ninja_forms_run_action_settings", "__gulp_init_namespace___ninja_forms_format_success_message", 10, 1);

/**
 * Fix Ninja Forms not being output when no content exists and selected via meta box
 *
 * @param  string $content
 *
 * @return string
 */
function __gulp_init_namespace___ninja_forms_fix_appended_forms_with_no_content(string $content): string {
    return !$content && get_post_meta(get_the_ID(), "ninja_forms_form", true) ? "<!-- ninja form -->" : $content;
}
add_filter("the_content", "__gulp_init_namespace___ninja_forms_fix_appended_forms_with_no_content", 5);

/**
 * Add missing formHoneypot label
 */
function __gulp_init_namespace___ninja_forms_honeypot_label($nfi18n) {
    $nfi18n["formHoneypot"] = __("If you are a human seeing this field, please leave it empty.", "fvpd");

    return $nfi18n;
}
add_filter("ninja_forms_i18n_front_end", "__gulp_init_namespace___ninja_forms_honeypot_label");
