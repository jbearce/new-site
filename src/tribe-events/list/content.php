<?php
if (!defined("ABSPATH")) {
	die("-1");
}

// open a header
echo "<header class='article_header'>";

// Tribe hook
do_action("tribe_events_before_the_title");

// display the title
echo "<h1 class='article_title title'>" . tribe_get_events_title() . "</h1>";

// Tribe hook
do_action("tribe_events_after_the_title");

// close the header
echo "</header>";

// Tribe bar
tribe_get_template_part("modules/bar");

// Notices
tribe_the_notices();

// Events Loop
if (have_posts()) {
    // Tribe hook
	do_action("tribe_events_before_loop");

    // display the events
    tribe_get_template_part("list/loop");

    // Tribe hook
	do_action("tribe_events_after_loop");
}

// Tribe hook
do_action("tribe_events_before_footer");

// display the pagination
echo "<footer class='pagination-menu'>";
tribe_get_template_part("list/nav");
echo "</footer>";

// Tribe hook
do_action("tribe_events_after_footer");
?>
