<?php
/* ------------------------------------------------------------------------ *\
 * Tribe Events
\* ------------------------------------------------------------------------ */

/* FUNCTIONS */

// determine if the current page is a tribe page
function is_tribe_page() {
    $queried_object = get_queried_object();

    $post_id = isset($post) ? $post->ID : (isset($queried_object->ID) ? $queried_object->ID : 0);
    $term_id = isset($queried_object->term_id) ? $queried_object->term_id : 0;

    if (function_exists("tribe_is_month") && tribe_is_month() && !is_tax()) {
    	return array(
            "archive"  => true,
            "view"     => "month",
            "taxonomy" => false,
            "term_id"  => 0,
        );
    } elseif (function_exists("tribe_is_month") && tribe_is_month() && is_tax()) {
        return array(
            "archive"  => true,
            "view"     => "month",
            "taxonomy" => true,
            "term_id"  => $term_id,
        );
    } elseif (function_exists("tribe_is_past") && tribe_is_past() && !is_tax()) { // List View Page
        return array(
            "archive"  => true,
            "view"     => "list",
            "mode"     => "past",
            "taxonomy" => false,
            "term_id"  => 0,
        );
    } elseif (function_exists("tribe_is_upcoming") && tribe_is_upcoming() && !is_tax()) {
        return array(
            "archive"  => true,
            "view"     => "list",
            "mode"     => "upcoming",
            "taxonomy" => false,
            "term_id"  => 0,
        );
    } elseif (function_exists("tribe_is_past") && tribe_is_past() && is_tax()) {
        return array(
            "archive"  => true,
            "view"     => "list",
            "mode"     => "past",
            "taxonomy" => true,
            "term_id"  => $term_id,
        );
    } elseif (function_exists("tribe_is_upcoming") && tribe_is_upcoming() && is_tax()) {
        return array(
            "archive"  => true,
            "view"     => "list",
            "mode"     => "upcoming",
            "taxonomy" => true,
            "term_id"  => $term_id,
        );
    } elseif (function_exists("tribe_is_week") && tribe_is_week() && !is_tax()) {
        return array(
            "archive"  => true,
            "view"     => "week",
            "taxonomy" => false,
            "term_id"  => 0,
        );
    } elseif (function_exists("tribe_is_week") && tribe_is_week() && is_tax()) {
        return array(
            "archive"  => true,
            "view"     => "week",
            "taxonomy" => true,
            "term_id"  => $term_id,
        );
    } elseif (function_exists("tribe_is_day") && tribe_is_day() && !is_tax()) {
        return array(
            "archive"  => true,
            "view"     => "day",
            "taxonomy" => false,
            "term_id"  => 0,
        );
    } elseif (function_exists("tribe_is_day") && tribe_is_day() && is_tax()) {
        return array(
            "archive"  => true,
            "view"     => "day",
            "taxonomy" => true,
            "term_id"  => $term_id,
        );
    } elseif (function_exists("tribe_is_map") && tribe_is_map() && !is_tax()) {
        return array(
            "archive"  => true,
            "view"     => "map",
            "taxonomy" => false,
            "term_id"  => 0,
        );
    } elseif (function_exists("tribe_is_map") && tribe_is_map() && is_tax()) { // Map View Category Page
        return array(
            "archive"  => true,
            "view"     => "map",
            "taxonomy" => true,
            "term_id"  => $term_id,
        );
    } elseif (function_exists("tribe_is_photo") && tribe_is_photo() && !is_tax()) { // Photo View Page
        return array(
            "archive"  => true,
            "view"     => "photo",
            "taxonomy" => false,
            "term_id"  => 0,
        );
    } elseif (function_exists("tribe_is_photo") && tribe_is_photo() && is_tax()) { // Photo View Category Page
        return array(
            "archive"  => true,
            "view"     => "photo",
            "taxonomy" => true,
            "term_id"  => $term_id,
        );
    } elseif (function_exists("tribe_is_event") && tribe_is_event() && is_single()) { // Single Events
        return array(
            "single"    => true,
            "post_type" => "tribe_events",
            "post_id"   => $post_id,
        );
    } elseif (function_exists("tribe_is_venue") && tribe_is_venue()) { // Single Venues
        return array(
            "single"    => true,
            "post_type" => "tribe_venue",
            "post_id"   => $post_id,
        );
    } elseif (get_post_type() === "tribe_organizer" && is_single()) { // Single Organizers
        return array(
            "single"    => true,
            "post_type" => "tribe_organizer",
            "post_id"   => $post_id,
        );
    } else {
        return false;
    }
}

// retrieve a date and time string
function get_tribe_date_and_time_strings($event_id) {
    $is_all_day   = tribe_event_is_all_day($event_id);
    $is_multiday  = tribe_event_is_multiday($event_id);

    $time_start = !$is_all_day ? tribe_get_start_date($event_id, false, (tribe_get_start_date($event_id, false, "i") === "00" ? "ga" : "g:ia")) : false;
    $time_end   = !$is_all_day ? tribe_get_end_date($event_id, false, (tribe_get_end_date($event_id, false, "i") === "00" ? "ga" : "g:ia")) : false;

    $year_current = current_time("Y");

    $year_start   = tribe_get_start_date($event_id, false, "Y");
    $month_start  = tribe_get_start_date($event_id, false, "n");

    $year_end     = tribe_get_end_date($event_id, false, "Y");
    $month_end    = tribe_get_end_date($event_id, false, "n");

    $date_start_format = "l, F j"; // Sunday, January 1
    $date_end_format   = "l, F j"; // Sunday, January 1

    // if the same years and the same months
    if ($is_multiday && $year_start === $year_end && $month_start === $month_end) {
        $date_start_format = "M j"; // Jan 1
        $date_end_format   = (!$is_all_day ? "M " : "") . "j" . ($year_end !== $year_current ? ", Y" : ""); // Jan 1, 2018
    // if the same years but different months
    } elseif ($is_multiday && $year_start === $year_end && $month_start !== $month_end) {
        $date_start_format = "M j"; // Jan 1
        $date_end_format   = "M j" . ($year_end !== $year_current ? ", Y" : ""); // Jan 1, 2018
    // if different years
    } elseif ($year_start !== $year_end || $year_start !== $year_current) {
        $date_start_format = "M j, Y"; // Jan 1, 2018
        $date_end_format   = "M j, Y"; // Jan 1, 2018
    }

    $date_start = tribe_get_start_date($event_id, false, $date_start_format);
    $date_end   = tribe_get_end_date($event_id, false, $date_end_format);

    if ($is_multiday && !$is_all_day) {
        $date_start .= ", {$time_start}";
        $date_end .= ", {$time_end}";
    }

    $date_string = $date_start . ($is_multiday ? " &ndash; {$date_end}" : "");

    $time_string = !$is_multiday && !$is_all_day ? $time_start . ($time_start !== $time_end ? " &ndash; {$time_end}" : "") : false;

    return array(
        "date" => $date_string,
        "time" => $time_string,
    );
}

/* FILTERS */

// dequeue & deregister tribe calendar styles
function __gulp_init_namespace___tribe_dequeue_calendar_styles() {
    wp_dequeue_style("tribe-events-calendar-style", 999);
    wp_deregister_style("tribe-events-calendar-style");
}
add_action("wp_enqueue_scripts", "__gulp_init_namespace___tribe_dequeue_calendar_styles");

// remove the tribe events promo
function __gulp_init_namespace___tribe_disable_promo($echo) {
    return false;
}
add_action("tribe_events_promo_banner", "__gulp_init_namespace___tribe_disable_promo");

// remove __gulp_init_namespace___add_user_content_classes from tribe events pages
function __gulp_init_namespace___tribe_remove_content_filters() {
    if (is_tribe_page()) {
        remove_filter("the_content", "__gulp_init_namespace___add_user_content_classes", 20);
        remove_filter("acf_the_content", "__gulp_init_namespace___add_user_content_classes", 20);
        remove_filter("the_content", "__gulp_init_namespace___lazy_load_images", 20);
        remove_filter("acf_the_content", "__gulp_init_namespace___lazy_load_images", 20);
    }
}
add_action("loop_start", "__gulp_init_namespace___tribe_remove_content_filters", 999);

// add __gulp_init_namespace___add_user_content_classes filter to the_content before tribe events single content
function __gulp_init_namespace___tribe_single_content_add_filters() {
    if (is_tribe_page()) {
        add_filter("the_content", "__gulp_init_namespace___add_user_content_classes", 20);
        add_filter("acf_the_content", "__gulp_init_namespace___add_user_content_classes", 20);
        add_filter("the_content", "__gulp_init_namespace___lazy_load_images", 20);
        add_filter("acf_the_content", "__gulp_init_namespace___lazy_load_images", 20);
    }
}
add_action("tribe_events_single_event_before_the_content", "__gulp_init_namespace___tribe_single_content_add_filters");

// remove __gulp_init_namespace___add_user_content_classes filter from the_content after tribe events single content
function __gulp_init_namespace___tribe_single_content_remove_filters() {
    if (is_tribe_page()) {
        remove_filter("the_content", "__gulp_init_namespace___add_user_content_classes", 20);
        remove_filter("acf_the_content", "__gulp_init_namespace___add_user_content_classes", 20);
        remove_filter("the_content", "__gulp_init_namespace___lazy_load_images", 20);
        remove_filter("acf_the_content", "__gulp_init_namespace___lazy_load_images", 20);
    }
}
add_action("tribe_events_single_event_after_the_content", "__gulp_init_namespace___tribe_single_content_remove_filters");

// add 'menu-list_link link' to list of classes for tribe monthly pagination link
function __gulp_init_namespace___tribe_add_pagination_menu_link_class($html) {
    $DOM = new DOMDocument();
    $DOM->loadHTML(mb_convert_encoding("<html><body>{$html}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $anchors = $DOM->getElementsByTagName("a");

    foreach ($anchors as $anchor) {
        $anchor->setAttribute("class", "menu-list__link link {$anchor->getAttribute("class")}");
    }

    // remove unneeded tags (inserted for parsing reasons)
    $html = __gulp_init_namespace___remove_extra_tags($DOM);

    return $html;
}
add_filter("tribe_events_the_previous_month_link", "__gulp_init_namespace___tribe_add_pagination_menu_link_class");
add_filter("tribe_events_the_next_month_link", "__gulp_init_namespace___tribe_add_pagination_menu_link_class");
add_filter("tribe_the_day_link", "__gulp_init_namespace___tribe_add_pagination_menu_link_class");

// add 'title--divider' class to tribe date headers
function __gulp_init_namespace___tribe_add_title_class_to_date_headers($html) {
    if ($html) {
        $DOM = new DOMDocument();
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$html}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $h2s = $DOM->getElementsByTagName("h2");

        foreach ($h2s as $h2) {
            $h2->setAttribute("class", "tribe-events-title title title--h4 title--divider {$h2->getAttribute("class")}");
        }

        // remove unneeded tags (inserted for parsing reasons)
        $html = __gulp_init_namespace___remove_extra_tags($DOM);
    }

    return $html;
}
add_filter("tribe_events_list_the_date_headers", "__gulp_init_namespace___tribe_add_title_class_to_date_headers");

// add 'tribe-events-text_text text' class to tribe excerpts
function __gulp_init_namespace___tribe_add_text_class_to_excerpt($excerpt) {
    $DOM = new DOMDocument();
    $DOM->loadHTML(mb_convert_encoding("<html><body>{$excerpt}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $paragraphs = $DOM->getElementsByTagName("p");

    foreach ($paragraphs as $paragraph) {
        $paragraph->setAttribute("class", "tribe-events-text__text text {$paragraph->getAttribute("class")}");
    }

    // remove unneeded tags (inserted for parsing reasons)
    $excerpt = __gulp_init_namespace___remove_extra_tags($DOM);

    return $excerpt;
}
add_filter("tribe_events_get_the_excerpt", "__gulp_init_namespace___tribe_add_text_class_to_excerpt");

// add text classes to tribe notices
function __gulp_init_namespace___tribe_add_text_class_to_notices($html) {
    $DOM = new DOMDocument();
    $DOM->loadHTML(mb_convert_encoding("<html><body>{$html}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $unordered_lists = $DOM->getElementsByTagName("ul");

    foreach ($unordered_lists as $unordered_list) {
        $unordered_list->setAttribute("class", "tribe-events-notices__text text text--list text--clean __light __nomargin {$unordered_list->getAttribute("class")}");
    }

    $list_items = $DOM->getElementsByTagName("li");

    foreach ($list_items as $list_item) {
        $list_item->setAttribute("class", "text__list-item {$list_item->getAttribute("class")}");
    }

    // remove unneeded tags (inserted for parsing reasons)
    $html = __gulp_init_namespace___remove_extra_tags($DOM);

    return $html;
}
add_filter("tribe_the_notices", "__gulp_init_namespace___tribe_add_text_class_to_notices");

// disable tribe ical links
function __gulp_init_namespace___tribe_disable_ical_links() {
    return false;
}
add_filter("tribe_events_ical_single_event_links", "__gulp_init_namespace___tribe_disable_ical_links");
add_filter("tribe_events_list_show_ical_link", "__gulp_init_namespace___tribe_disable_ical_links");

// add proper classes to Tribe featured images
function __gulp_init_namespace___tribe_add_class_to_featured_image($featured_image) {
    if (is_singular("tribe_events")) {
        $DOM = new DOMDocument();
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$featured_image}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $divs = $DOM->getElementsByTagName("div");

        foreach ($divs as $div) {
            $div->setAttribute("class", "article__figure {$div->getAttribute("class")}");
        }

        $images = $DOM->getElementsByTagName("img");

        foreach ($images as $image) {
            $image->setAttribute("class", "article__image {$image->getAttribute("class")}");
        }

        // remove unneeded tags (inserted for parsing reasons)
        $featured_image = __gulp_init_namespace___remove_extra_tags($DOM);
    }

    return $featured_image;
}
add_filter("tribe_event_featured_image", "__gulp_init_namespace___tribe_add_class_to_featured_image");

// add 'link' class to tribe events title links
function __gulp_init_namespace___tribe_add_events_title_link_class($title) {
    if ($title) {
        $DOM = new DOMDocument();
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$title}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $anchors = $DOM->getElementsByTagName("a");

        foreach ($anchors as $anchor) {
            $anchor->setAttribute("class", "title__link link {$anchor->getAttribute("class")}");
        }

        // remove unneeded tags (inserted for parsing reasons)
        $title = __gulp_init_namespace___remove_extra_tags($DOM);
    }

    return $title;
}
add_filter("tribe_events_title", "__gulp_init_namespace___tribe_add_events_title_link_class");

// add 'link' class to tribe events title links
function __gulp_init_namespace___tribe_add_bar_input_class($html) {
    if ($html) {
        $DOM = new DOMDocument();
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$html}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $inputs = $DOM->getElementsByTagName("input");

        foreach ($inputs as $input) {
            $input->setAttribute("class", "tribe-bar-filters-input {$input->getAttribute("class")}");
        }

        // remove unneeded tags (inserted for parsing reasons)
        $html = __gulp_init_namespace___remove_extra_tags($DOM);
    }

    return $html;
}
add_filter("__gulp_init_namespace___tribe_add_bar_input_class", "__gulp_init_namespace___tribe_add_bar_input_class");
