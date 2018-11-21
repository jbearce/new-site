<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Ninja Forms
\* ------------------------------------------------------------------------ */

// disable display styles
function __gulp_init_namespace___ninja_forms_dequeue_display_styles() {
    wp_dequeue_style("nf-display");
}
add_action("ninja_forms_enqueue_scripts", "__gulp_init_namespace___ninja_forms_dequeue_display_styles", 999);

// change order of scripts so that `nf-front-end` always comes after all dependencies
function __gulp_init_namespace___ninja_forms_fix_scripts_order() {
    global $wp_scripts;

    // match every script prefixed with `nf-`, except `nf-front-end` and `nf-front-end-deps`
    $pattern = "/^nf-(?!front-end(?:-deps)?$)/";

    // change all `nf-front-end` dependencies to `nf-front-end-deps`
    foreach ($wp_scripts->registered as $script) {
        if (preg_match($pattern, $script->handle)) {
            $key = $script ? array_search("nf-front-end", $script->deps) : false;

            if ($key !== false) {
                $script->deps[$key] = "nf-front-end-deps";
            }
        }
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

// fix HTML field formatting
function __gulp_init_namespace___ninja_forms_format_html($default_value, $field_class, $field_settings) {
    if ($field_settings["type"] === "html") {
        $default_value = apply_filters("the_content", $default_value);
    }

    return $default_value;
}
add_filter("ninja_forms_render_default_value", "__gulp_init_namespace___ninja_forms_html", 10, 3);

// fix success message formatting
function __gulp_init_namespace___ninja_forms_format_success_message($action_settings, $form_id, $action_id, $form_settings) {
    if ($action_settings["type"] === "successmessage") {
        $action_settings["success_msg"] = apply_filters("the_content", $action_settings["success_msg"]);
    }

    return $action_settings;
}
add_filter("ninja_forms_run_action_settings", "__gulp_init_namespace___ninja_forms_format_success_message", 10, 4);

// fix Ninja Forms not being output when no content exists and selected via meta box
function __gulp_init_namespace___ninja_forms_fix_appended_forms_with_no_content($content) {
    return !$content && get_post_meta(get_the_ID(), "ninja_forms_form", true) ? "<!-- ninja form -->" : $content;
}
add_filter("the_content", "__gulp_init_namespace___ninja_forms_fix_appended_forms_with_no_content", 5);
