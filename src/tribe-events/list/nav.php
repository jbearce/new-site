<?php
if (!defined("ABSPATH")) {
	die("-1");
}

global $wp_query;

$events_label_plural = tribe_get_event_label_plural();

// Tribe hook
do_action("tribe_events_before_nav");

// open a pagination
echo "<p class='pagination-text text'>";

// display the previous link
if (tribe_has_previous_event()) {
    echo "<a class='pagination-link link _left' href='" . esc_url(tribe_get_listview_prev_link()) . "' rel='prev'>";
    printf("<i class='fa fa-caret-left'></i> " . esc_html__("Previous %s", "new-site"), $events_label_plural);
    echo "</a>";
}

// display the next link
if (tribe_has_next_event()) {
    echo "<a class='pagination-link link _right' href='" . esc_url(tribe_get_listview_next_link()) . "' rel='next'>";
    printf(esc_html__("Next %s", "new-site"), $events_label_plural .  "<i class='fa fa-caret-right'></i>");
    echo "</a>";
}

// close the pagination
echo "</p>";

// Tribe hook
do_action("tribe_events_after_nav");
?>
