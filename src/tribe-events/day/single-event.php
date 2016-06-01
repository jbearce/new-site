<?php
if (!defined("ABSPATH")) {
	die("-1");
}

$permalink = esc_url(tribe_get_event_link());

$venue_details = tribe_get_venue_details();

// Venue microformats
$has_venue = ($venue_details) ? " vcard" : "";
$has_venue_address = (!empty($venue_details["address"])) ? " location" : "";

// Tribe hook
do_action("tribe_events_before_the_event_title");

// display the title
echo "<h2 class='article-title title -sub'><a class='article-link link' href='{$permalink}'>" . get_the_title() . "</a></h2>";

// Tribe hoook
do_action("tribe_events_after_the_event_title");

// Tribe hook
do_action("tribe_events_before_the_meta");

// display the date
echo " <h3 class='article-title title -sub'><a class='article-link link' href='{$permalink}'>" . tribe_events_event_schedule_details() . "</a></h3>";

/*
// display the venue
if ($venue_details) {
    echo implode(", ", $venue_details);
}

// display the cost
if (tribe_get_cost()) {
	echo tribe_get_cost(null, true);
}
*/

// Tribe hook
do_action("tribe_events_after_the_meta");

// Tribe hook
do_action("tribe_events_before_the_content");

// display the excerpt
echo "<div class='article-content'><p class='article-text text'><a href='{$permalink}'>" . strip_tags(tribe_events_get_the_excerpt()) . "</a></p></div>";

// Tribe hook
do_action("tribe_events_after_the_content");
?>
