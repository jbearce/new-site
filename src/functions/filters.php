<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Filters
\* ------------------------------------------------------------------------ */

// set cookie when a query string gets passed
function new_site_set_cookie() {
    $cookie = $_GET["cookie"];
    $expires = $_GET["expiration"] ? time() + $_GET["expires"] : time() + 604800;

    if ($cookie) {
        setcookie($cookie, true, $expires); // expires in 1 week by default
        exit;
    }
}
add_filter("init", "new_site_set_cookie", 10);

// fix shortcode formatting
function new_site_fix_shortcodes($content) {
	$array = array (
		"<p>["         => "[",
		"]</p>"        => "]",
		"]<br />"      => "]",
        "<p>&#91;"     => "[",
        "&#93;</p>"    => "]",
        "&#93;<br />"  => "]",
	);
	$content = strtr($content, $array);

    return $content;
}
add_filter("the_content", "new_site_fix_shortcodes");
add_filter("acf_the_content", "new_site_fix_shortcodes", 12);

// remove dimensions from thumbnails
function new_site_remove_thumbnail_dimensions($html, $post_id, $post_image_id) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}
add_filter("post_thumbnail_html", "new_site_remove_thumbnail_dimensions", 10, 3);

// add rel="noopener" to external links
function new_site_rel_noopener($content) {
    $content = preg_replace("/(<a )(?!.*(?<= )rel=(?:'|\"))(.[^>]*>)/i", "$1 rel=\"noopener\"$2", $content);

    return $content;
}
add_filter("the_content", "new_site_rel_noopener");
add_filter("acf_the_content", "new_site_rel_noopener", 12);

// disable Ninja Forms styles
function new_site_dequeue_nf_display() {
    wp_dequeue_style("nf-display");
}
add_action("ninja_forms_enqueue_scripts", "new_site_dequeue_nf_display", 999);

// add user-content class to TinyMCE body
function new_site_tinymce_settings($settings) {
    $settings["body_class"] .= " user-content";
	return $settings;
}
add_filter("tiny_mce_before_init", "new_site_tinymce_settings");

// add button class to Tribe Events month links
function new_site_tribe_events_the_month_link($html) {
    $html = preg_replace("/(<a)/", "$1 class='tribe_button button _nowrap'", $html);
    return $html;
}
add_filter("tribe_events_the_next_month_link", "new_site_tribe_events_the_month_link");
add_filter("tribe_events_the_previous_month_link", "new_site_tribe_events_the_month_link");

// diable Tribe Events ical links (since I can't re-style them)
function new_site_tribe_events_list_show_ical_link() {
    return false;
}
add_filter("tribe_events_list_show_ical_link", "new_site_tribe_events_list_show_ical_link");
