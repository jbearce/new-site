<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Filters
\* ------------------------------------------------------------------------ */

// set cookie when a query string gets passed
function new_site_set_cookie() {
    $cookie     = isset($_GET["cookie"]) ? $_get["cookie"] : false;
    $expiration = isset($_GET["expiration"]) ? time() + $_GET["expiration"] : time() + 604800;


    if ($cookie) {
        setcookie($cookie, "true", $expiration); // expires in 1 week by default
        exit;
    }
}
add_action("init", "new_site_set_cookie", 10);

// add user-content class to TinyMCE body
function new_site_tinymce_settings($settings) {
    $settings["body_class"] .= " user-content";
	return $settings;
}
add_filter("tiny_mce_before_init", "new_site_tinymce_settings");

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

// wrap tables in a div
function new_site_wrap_tables($content) {
    $content = preg_replace("/(<table(?:.|\n)*?<\/table>)/im", "<div class='table_container'>$1</div>", $content);

    return $content;
}
add_filter("the_content", "new_site_wrap_tables");
add_filter("acf_the_content", "new_site_wrap_tables");

// remove dimensions from thumbnails
function new_site_remove_thumbnail_dimensions($html, $post_id, $post_image_id) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/im', "", $html);
    return $html;
}
add_filter("post_thumbnail_html", "new_site_remove_thumbnail_dimensions", 10, 3);

// add rel="noopener" to external links
function new_site_rel_noopener($content) {
    $content = preg_replace("/(<a )(?!.*(?<= )rel=(?:'|\"))(.[^>]*>)/im", "$1 rel=\"noopener\"$2", $content);

    return $content;
}
add_filter("the_content", "new_site_rel_noopener");
add_filter("acf_the_content", "new_site_rel_noopener", 12);

// add "Download Adobe Reader" link on all pages that link to PDFs
function new_site_acrobat_link($content) {
    preg_match("/\.pdf(?:\'|\")/im", $content, $matches);

    if ($matches) {
        $content .= "<hr />";
        $content .= "<p class='_small'>" . sprintf(__("Having trouble opening PDFs? %sDownload Adobe Reader here.%s", "new_site"), "<a href='https://get.adobe.com/reader/' target='_blank' rel='noopener'>", "</a>") . "</p>";
    }

    return $content;
}
add_filter("the_content", "new_site_acrobat_link");
add_filter("acf_the_content", "new_site_acrobat_link");

// disable Ninja Forms styles
function new_site_dequeue_nf_display() {
    wp_dequeue_style("nf-display");
}
add_action("ninja_forms_enqueue_scripts", "new_site_dequeue_nf_display", 999);
/*removeIf(tribe_php)*/
// add button class to Tribe Events month links
function new_site_tribe_events_the_month_link($html) {
    $html = preg_replace("/(<a)/", "$1 class='tribe_button button _nowrap'", $html);
    return $html;
}
add_filter("tribe_events_the_next_month_link", "new_site_tribe_events_the_month_link");
add_filter("tribe_events_the_previous_month_link", "new_site_tribe_events_the_month_link");

// disable Tribe Events ical links (since I can't re-style them)
function new_site_tribe_events_list_show_ical_link() {
    return false;
}
add_filter("tribe_events_list_show_ical_link", "new_site_tribe_events_list_show_ical_link");/*endRemoveIf(tribe_php)*/
