<?php
/* ------------------------------------------------------------------------ *\
 * Tribe Events
\* ------------------------------------------------------------------------ */

// stop if Tribe isn't installed
if (!function_exists("tribe_get_events")) {
    return;
}

/* FUNCTIONS */

// determine if the current page is a tribe page
function __gulp_init_namespace___is_tribe_page() {
    $queried_object = get_queried_object();


    $post_id = isset($post) ? $post->ID : (isset($queried_object->ID) ? $queried_object->ID : 0);
    $term_id = isset($queried_object->term_id) ? $queried_object->term_id : 0;

    if (function_exists("tribe_is_month") && tribe_is_month() && !is_tax()) {
        $variant = array("month");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

    	return array(
            "object_id" => 0,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_month") && tribe_is_month() && is_tax()) {
        $variant = array("month");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

        return array(
            "object_id" => $term_id,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_past") && tribe_is_past() && !is_tax()) {
        $variant = array("list", "past");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

        return array(
            "object_id" => 0,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_upcoming") && tribe_is_upcoming() && !is_tax()) {
        $variant = array("list", "future");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

        return array(
            "object_id" => 0,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_past") && tribe_is_past() && is_tax()) {
        $variant = array("list", "past");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

        return array(
            "object_id" => $term_id,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_upcoming") && tribe_is_upcoming() && is_tax()) {
        $variant = array("list", "future");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

        return array(
            "object_id" => $term_id,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_week") && tribe_is_week() && !is_tax()) {
        $variant = array("week");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

        return array(
            "object_id" => 0,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_week") && tribe_is_week() && is_tax()) {
        $variant = array("week");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

        return array(
            "object_id" => $term_id,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_day") && tribe_is_day() && !is_tax()) {
        $variant = array("day");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

        return array(
            "object_id" => 0,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_day") && tribe_is_day() && is_tax()) {
        $variant = array("day");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

        return array(
            "object_id" => $term_id,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_map") && tribe_is_map() && !is_tax()) {
        $variant = array("map");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

        return array(
            "object_id" => 0,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_map") && tribe_is_map() && is_tax()) {
        $variant = array("map");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

        return array(
            "object_id" => $term_id,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_photo") && tribe_is_photo() && !is_tax()) {
        $variant = array("photo");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

        return array(
            "object_id" => 0,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_photo") && tribe_is_photo() && is_tax()) {
        $variant = array("photo");

        if (property_exists($queried_object, "taxonomy")) {
            $variant[] = $queried_object->taxonomy;
        }

        return array(
            "object_id" => $term_id,
            "type"      => "archive",
            "variants"  => $variant,
        );
    } elseif (function_exists("tribe_is_event") && tribe_is_event() && is_single()) {
        return array(
            "object_id" => $post_id,
            "type"      => "single",
            "variants"  => array("tribe_events"),
        );
    } elseif (function_exists("tribe_is_venue") && tribe_is_venue()) {
        return array(
            "object_id" => $post_id,
            "type"      => "single",
            "variants"     => "tribe_venue",
        );
    } elseif (get_post_type() === "tribe_organizer" && is_single()) {
        return array(
            "object_id" => $post_id,
            "type"      => "single",
            "variants"  => array("tribe_organizer"),
        );
    } else {
        return false;
    }
}

// retrieve a date and time string
function __gulp_init_namespace___get_tribe_date_and_time_strings($event_id) {
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

/**
 * Use correct Tribe templates, if they exist
 */
function __gulp_init_namespace___tribe_force_page_templates($template) {
    $tribe_page = __gulp_init_namespace___is_tribe_page();

    if ($tribe_page && $tribe_page["type"] === "single") {
        if (in_array("tribe_events", $tribe_page["variants"])) {
            return locate_template(array("single-tribe_events.php", "single.php", "page.php", "index.php"));
        }

        if (in_array("tribe_venue", $tribe_page["variants"])) {
            return locate_template(array("single-tribe_venue.php", "single-tribe_events.php", "single.php", "page.php", "index.php"));
        }

        if (in_array("tribe_organizer", $tribe_page["variants"])) {
            return locate_template(array("single-tribe_organizer.php", "single-tribe_events.php", "single.php", "page.php", "index.php"));
        }
    }

    if ($tribe_page && $tribe_page["type"] === "archive") {
        if (in_array("tribe_events_cat", $tribe_page["variants"])) {
            return locate_template(array("taxonomy-tribe_events_cat.php", "archive-tribe_events.php", "page.php", "index.php"));
        } else {
            return locate_template(array("archive-tribe_events.php", "page.php", "index.php"));
        }
    }

    return $template;
}
add_filter("template_include", "__gulp_init_namespace___tribe_force_page_templates");

// dequeue & deregister tribe calendar styles, keep bootstrap datepicker
function __gulp_init_namespace___tribe_dequeue_calendar_styles() {
    wp_dequeue_style("tribe-events-calendar-style", 999);
    wp_deregister_style("tribe-events-calendar-style");

    if (__gulp_init_namespace___is_tribe_page()) {
        wp_enqueue_style("tribe-events-bootstrap-datepicker-css");
    }
}
add_action("wp_enqueue_scripts", "__gulp_init_namespace___tribe_dequeue_calendar_styles");

// remove the tribe events promo
function __gulp_init_namespace___tribe_disable_promo() {
    return false;
}
add_action("tribe_events_promo_banner", "__gulp_init_namespace___tribe_disable_promo");

// remove __gulp_init_namespace___add_user_content_classes from tribe events pages
function __gulp_init_namespace___tribe_remove_content_filters() {
    if (__gulp_init_namespace___is_tribe_page()) {
        remove_filter("the_content", "__gulp_init_namespace___add_user_content_classes", 20);
        remove_filter("the_content", "__gulp_init_namespace___lazy_load_images", 20);
    }
}
add_action("loop_start", "__gulp_init_namespace___tribe_remove_content_filters", 999);

// add __gulp_init_namespace___add_user_content_classes filter to the_content before tribe events single content
function __gulp_init_namespace___tribe_single_content_add_filters() {
    add_filter("the_content", "__gulp_init_namespace___add_user_content_classes", 20);
    add_filter("the_content", "__gulp_init_namespace___lazy_load_images", 20);
}
add_action("tribe_events_single_event_before_the_content", "__gulp_init_namespace___tribe_single_content_add_filters");

// remove __gulp_init_namespace___add_user_content_classes filter from the_content after tribe events single content
function __gulp_init_namespace___tribe_single_content_remove_filters() {
    remove_filter("the_content", "__gulp_init_namespace___add_user_content_classes", 20);
    remove_filter("the_content", "__gulp_init_namespace___lazy_load_images", 20);
}
add_action("tribe_events_single_event_after_the_content", "__gulp_init_namespace___tribe_single_content_remove_filters");

// add 'menu-list_link link' to list of classes for tribe monthly pagination link
function __gulp_init_namespace___tribe_add_pagination_menu_link_class($html) {
    if ($html) {
        $DOM = new DOMDocument();

        // disable errors to get around HTML5 warnings...
        libxml_use_internal_errors(true);

        // load in content
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$html}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NODEFDTD);

        // reset errors to get around HTML5 warnings...
        libxml_clear_errors();

        $anchors = $DOM->getElementsByTagName("a");

        foreach ($anchors as $anchor) {
            $anchor->setAttribute("class", "menu-list__link link {$anchor->getAttribute("class")}");
        }

        // remove unneeded tags (inserted for parsing reasons)
        $html = __gulp_init_namespace___remove_extra_tags($DOM);
    }

    return $html;
}
add_filter("tribe_events_the_previous_month_link", "__gulp_init_namespace___tribe_add_pagination_menu_link_class");
add_filter("tribe_events_the_next_month_link", "__gulp_init_namespace___tribe_add_pagination_menu_link_class");
add_filter("tribe_the_day_link", "__gulp_init_namespace___tribe_add_pagination_menu_link_class");

// add 'title--divider' class to tribe date headers
function __gulp_init_namespace___tribe_add_title_class_to_date_headers($html) {
    if ($html) {
        $DOM = new DOMDocument();

        // disable errors to get around HTML5 warnings...
        libxml_use_internal_errors(true);

        // load in content
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$html}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NODEFDTD);

        // reset errors to get around HTML5 warnings...
        libxml_clear_errors();

        $heading2s = $DOM->getElementsByTagName("h2");

        foreach ($heading2s as $heading2) {
            $heading2->setAttribute("class", "tribe-events-title title title--h4 title--divider {$heading2->getAttribute("class")}");
        }

        // remove unneeded tags (inserted for parsing reasons)
        $html = __gulp_init_namespace___remove_extra_tags($DOM);
    }

    return $html;
}
add_filter("tribe_events_list_the_date_headers", "__gulp_init_namespace___tribe_add_title_class_to_date_headers");

// add 'tribe-events-text_text text' class to tribe excerpts
function __gulp_init_namespace___tribe_add_text_class_to_excerpt($excerpt) {
    if ($excerpt) {
        $DOM = new DOMDocument();

        // disable errors to get around HTML5 warnings...
        libxml_use_internal_errors(true);

        // load in content
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$excerpt}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NODEFDTD);

        // reset errors to get around HTML5 warnings...
        libxml_clear_errors();

        $paragraphs = $DOM->getElementsByTagName("p");

        foreach ($paragraphs as $paragraph) {
            $paragraph->setAttribute("class", "tribe-events-text__text text {$paragraph->getAttribute("class")}");
        }

        // remove unneeded tags (inserted for parsing reasons)
        $excerpt = __gulp_init_namespace___remove_extra_tags($DOM);
    }

    return $excerpt;
}
add_filter("tribe_events_get_the_excerpt", "__gulp_init_namespace___tribe_add_text_class_to_excerpt");

// add text classes to tribe notices
function __gulp_init_namespace___tribe_add_text_class_to_notices($html) {
    if ($html) {
        $DOM = new DOMDocument();

        // disable errors to get around HTML5 warnings...
        libxml_use_internal_errors(true);

        // load in content
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$html}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NODEFDTD);

        // reset errors to get around HTML5 warnings...
        libxml_clear_errors();

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
    }

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
    if (is_singular("tribe_events") && $featured_image) {
        $DOM = new DOMDocument();

        // disable errors to get around HTML5 warnings...
        libxml_use_internal_errors(true);

        // load in content
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$featured_image}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NODEFDTD);

        // reset errors to get around HTML5 warnings...
        libxml_clear_errors();

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

        // disable errors to get around HTML5 warnings...
        libxml_use_internal_errors(true);

        // load in content
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$title}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NODEFDTD);

        // reset errors to get around HTML5 warnings...
        libxml_clear_errors();

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

// add 'input' class to tribe events bar inputs
function __gulp_init_namespace___tribe_add_bar_input_class($html) {
    if ($html) {
        $DOM = new DOMDocument();

        // disable errors to get around HTML5 warnings...
        libxml_use_internal_errors(true);

        // load in content
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$html}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NODEFDTD);

        // reset errors to get around HTML5 warnings...
        libxml_clear_errors();

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

/**
 * Remove recurring events duplicates from search results
 * @see https://www.relevanssi.com/knowledge-base/showing-one-recurring-event/
 */
function __gulp_init_namespace___relevanssi_cull_recurring_events($hits) {
    $ok_results     = array();
    $posts_seen     = array();
    $index_by_title = array();
    $date_by_title  = array();

    $i = 0;

    foreach ($hits[0] as $hit) {
        if (!isset($posts_seen[$hit->post_title])) {
            $ok_results[]                     = $hit;
            $date_by_title[$hit->post_title]  = get_post_meta($hit->ID, "_EventStartDate", true);
            $index_by_title[$hit->post_title] = $i;
            $posts_seen[$hit->post_title]     = true;
            $i++;
        } elseif (get_post_meta($hit->ID, "_EventStartDate", true) < $date_by_title[$hit->post_title]) {
            if (strtotime(get_post_meta($hit->ID, "_EventStartDate", true)) < time()) continue;
            $date_by_title[$hit->post_title]               = get_post_meta($hit->ID, "_EventStartDate", true);
            $ok_results[$index_by_title[$hit->post_title]] = $hit;
        }
    }

    $hits[0] = $ok_results;

    return $hits;
}
add_filter("relevanssi_hits_filter", "__gulp_init_namespace___relevanssi_cull_recurring_events");
