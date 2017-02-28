<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Filters
\* ------------------------------------------------------------------------ */

// generate JSON sitemap for uncss & critical
function new_site_json_sitemap() {
    if (isset($_GET["sitemap"]) === true) {
        $urls = array();

        /* get one 404 URL */

        $urls[] = home_url() . "/" . uniqid();

        /* get one search URL */

        $urls[] = home_url() . "/?s=" . get_bloginfo("name");

        /* get one URL for each post type single */
        /* @TODO get one for each post template, not simply each post type */

        // store linked post types in an array
        $linked_post_types = array();
        // get all posts of any post type
        $post_type_query = new WP_Query(array(
            "post_status" => "publish",
            "post_type"   => "any",
            "showposts"   => "-1",
        ));

        // loop through all posts of any post type
        while ($post_type_query->have_posts()) {
            // iterate the post index
            $post_type_query->the_post();

            // get the current post type
            $current_post_type = get_post_type();

            // get the current post type archive link
            $current_post_type_archive_link = get_post_type_archive_link($current_post_type);

            // check if the current post type has alread been linked
            if ($current_post_type && !in_array($current_post_type, $linked_post_types) && !in_array(get_permalink(), $urls)) {
                // add the post type to the Linked Post Types array
                $linked_post_types[] = $current_post_type;
                // add the URL to the URLs array
                $urls[] = get_permalink();

                // check if the current post type archive link has already been linked
                if ($current_post_type_archive_link && !in_array($current_post_type_archive_link, $urls)) {
                    // add the URL to the URLs array
                    $urls[] = preg_match("/\/$/", $current_post_type_archive_link) ? $current_post_type_archive_link : $current_post_type_archive_link . "/";
                }
            }
        }

        /* get one URL for each taxonomy */
        /* @TODO get one for each taxonomy template, not simply each taxonomy */

        // store linked taxonomies in an array
        $linked_taxonomies = array();
        // get all taxonomies
        $taxonomy_terms = get_terms(get_taxonomies());

        // loop through all taxonomies
        foreach ($taxonomy_terms as $term) {
            // get the current taxonomy
            $current_taxonomy = get_taxonomy($term->taxonomy);
            // get the current term link
            $term_link = get_term_link($term);

            // check if the current taxonomy is public, if it's already been linked, and if a term link exists
            if ($current_taxonomy && $current_taxonomy->public && !in_array($current_taxonomy->name, $linked_taxonomies) && $term_link) {
                $linked_taxonomies[] = $current_taxonomy->name;
                $urls[] = $term_link;
            }
        }

        // stop all other code and output the URLs
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
