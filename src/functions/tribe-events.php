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

/* FILTERS */

// dequue tribe calendar styles
function __gulp_init__namespace_tribe_dequeue_calendar_styles() {
    wp_dequeue_style("tribe-events-calendar-style", 999);
}
add_action("wp_enqueue_scripts", "__gulp_init__namespace_tribe_dequeue_calendar_styles");

// remove the tribe events promo
function __gulp_init__namespace_tribe_disable_promo($echo) {
    return false;
}
add_action("tribe_events_promo_banner", "__gulp_init__namespace_tribe_disable_promo");

// remove wpautop from tribe events pages
function __gulp_init__namespace_tribe_remove_content_filters() {
    if (is_tribe_page()) {
        remove_filter("the_content", "wpautop", 12);
        remove_filter("acf_the_content", "wpautop", 12);
    }
}
add_action("loop_start", "__gulp_init__namespace_tribe_remove_content_filters", 999);

// add 'menu-list_link link' to list of classes for tribe monthly pagination link
function __gulp_init__namespace_tribe_add_pagination_menu_link_class($html) {
    $DOM = new DOMDocument();
    $DOM->loadHTML(mb_convert_encoding("<html>{$html}</html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $anchors = $DOM->getElementsByTagName("a");

    foreach ($anchors as $anchor) {
        $anchor->setAttribute("class", "menu-list_link link {$anchor->getAttribute("class")}");
    }

    // remove unneeded HTML tag
    $DOM = remove_root_tag($DOM);

    $html = $DOM->saveHTML();

    return $html;
}
add_filter("tribe_events_the_previous_month_link", "__gulp_init__namespace_tribe_add_pagination_menu_link_class");
add_filter("tribe_events_the_next_month_link", "__gulp_init__namespace_tribe_add_pagination_menu_link_class");

// add 'button' to list of classes for tribe ical buttons
function __gulp_init__namespace_tribe_ical_link_button_class($calendar_links) {
    $DOM = new DOMDocument();
    $DOM->loadHTML(mb_convert_encoding("<html>{$calendar_links}</html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $anchors = $DOM->getElementsByTagName("a");

    foreach ($anchors as $anchor) {
        $anchor->setAttribute("class", "button {$anchor->getAttribute("class")}");
    }

    // remove unneeded HTML tag
    $DOM = remove_root_tag($DOM);

    $calendar_links = $DOM->saveHTML();

    return $calendar_links;
}
add_filter("tribe_events_ical_single_event_links", "__gulp_init__namespace_tribe_ical_link_button_class");

// add 'title -divider' class to tribe date headers
function __gulp_init__namespace_tribe_add_title_class_to_date_headers($html) {
    if ($html) {
        $html = "<h4 class='tribe-events-title title -h4 -divider'>$html</h4>";
    }

    return $html;
}
add_filter("tribe_events_list_the_date_headers", "__gulp_init__namespace_tribe_add_title_class_to_date_headers");

// add 'tribe-events-text_text text' class to tribe excerpts
function __gulp_init__namespace_tribe_add_text_class_to_excerpt($excerpt) {
    $DOM = new DOMDocument();
    $DOM->loadHTML(mb_convert_encoding("<html>{$excerpt}</html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $paragraphs = $DOM->getElementsByTagName("p");

    foreach ($paragraphs as $paragraph) {
        $paragraph->setAttribute("class", "tribe-events-text_text text {$paragraph->getAttribute("class")}");
    }

    // remove unneeded HTML tag
    $DOM = remove_root_tag($DOM);

    $excerpt = $DOM->saveHTML();

    return $excerpt;
}
add_filter("tribe_events_get_the_excerpt", "__gulp_init__namespace_tribe_add_text_class_to_excerpt");
