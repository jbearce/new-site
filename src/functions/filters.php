<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Filters
\* ------------------------------------------------------------------------ */

// generate JSON sitemap for uncss & critical
function new_site_json_sitemap() {
    if (isset($_GET["sitemap"]) && $_GET["sitemap"] === "true") {
        $urls = array();

        /* get one 404 URL */

        $urls[] = home_url() . "/" . uniqid();

        /* get one search URL */

        $urls[] = home_url() . "/?s=" . urlencode(get_bloginfo("name"));

        /* get one URL for each post template */

        // store linked post types in an array
        $linked_post_types = array();
        // store the linked post templates in an array
        $linked_post_templates = array();
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

            // get the current post link
            $current_post_link = get_permalink();

            // get the current post template
            $current_post_template = json_decode(file_get_contents($current_post_link . "?show=template"));

            // check if the current post type has alread been linked
            if ((($current_post_type && !in_array($current_post_type, $linked_post_types)) || ($current_post_template && !in_array($current_post_template, $linked_post_templates))) && !in_array($current_post_link, $urls)) {
                // add the post type to the Linked Post Types array
                if ($current_post_type && !in_array($current_post_type, $linked_post_types)) {
                    $linked_post_types[] = $current_post_type;
                }

                // add the post template to the Linked Post Templates array
                if ($current_post_template && !in_array($current_post_template, $linked_post_templates)) {
                    $linked_post_templates[] = $current_post_template;
                }

                // add the URL to the URLs array
                $urls[] = $current_post_link;

                // check if the current post type archive link has already been linked
                if ($current_post_type_archive_link && !in_array($current_post_type_archive_link, $urls)) {
                    // add the URL to the URLs array
                    $urls[] = preg_match("/\/$/", $current_post_type_archive_link) ? $current_post_type_archive_link : $current_post_type_archive_link . "/";
                }
            }
        }

        /* get one URL for each taxonomy template */

        // store the linked taxonomies in an array
        $linked_taxonomies = array();
        // store the linked taxonomy templates in an array
        $linked_taxonomy_templates = array();
        // get all taxonomies
        $taxonomy_terms = get_terms(get_taxonomies());

        // loop through all taxonomies
        foreach ($taxonomy_terms as $term) {
            // get the current taxonomy
            $current_taxonomy = get_taxonomy($term->taxonomy);

            // get the current term link
            $current_taxonomy_link = get_term_link($term);

            // check if the current taxonomy is public
            if ($current_taxonomy && $current_taxonomy->public) {
                // get the current taxonomy template
                $current_taxonomy_template = json_decode(file_get_contents($current_taxonomy_link . "?show=template"));

                // check if the current taxonomy has already been linked, and if a term link exists
                if ((($current_taxonomy->name && !in_array($current_taxonomy->name, $linked_taxonomies)) || ($current_taxonomy_template && !in_array($current_taxonomy_template, $linked_taxonomy_templates))) && $current_taxonomy_link) {
                    // add the taxonomy to the Linked Taxonomies array
                    if ($current_taxonomy->name && !in_array($current_taxonomy->name, $linked_taxonomies)) {
                        $linked_taxonomies[] = $current_taxonomy->name;
                    }

                    // add the taxonomy template to the Linked Taxonomy Templates array
                    if ($current_taxonomy_template && !in_array($current_taxonomy_template, $linked_taxonomy_templates)) {
                        $linked_taxonomy_templates[] = $current_taxonomy_template;
                    }

                    // add the URL to the URLs array
                    $urls[] = $current_taxonomy_link;
                }
            }
        }

        // stop all other code and output the URLs
        die(json_encode($urls));
    }
}
add_action("template_redirect", "new_site_json_sitemap");

// get the current template name
function new_site_show_template($template) {
    if (isset($_GET["show"]) && $_GET["show"] === "template") {
        global $template;
        die(json_encode(basename($template)));
    }

    return $template;
}
add_action("template_include", "new_site_show_template");

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
