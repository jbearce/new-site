<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Filters
\* ------------------------------------------------------------------------ */

// generate JSON sitemap for uncss & critical
function new_site_json_sitemap() {
    if (isset($_GET["sitemap"])) {
        $sitemap_query = new WP_Query(array(
            "post_status" => "publish",
            "post_type"   => "any",
            "showposts"   => "-1",
        ));
        $urls = array();

        while ($sitemap_query->have_posts()) {
            $sitemap_query->the_post();
            $urls[] = get_permalink();
        }

        die(json_encode($urls));
    }
}
add_action("template_redirect", "new_site_json_sitemap");

// remove dimensions from thumbnails
function new_site_remove_thumbnail_dimensions($html, $post_id, $post_image_id) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}
add_filter("post_thumbnail_html", "new_site_remove_thumbnail_dimensions", 10, 3);

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
