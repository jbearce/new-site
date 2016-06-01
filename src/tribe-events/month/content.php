<?php
if (!defined("ABSPATH")) {
	die("-1");
}

// open a header
echo "<header class='article_header header'>";

// Tribe hook
do_action("tribe_events_before_the_title");

// display the title
echo "<h1 class='article_title title'>" . tribe_get_events_title() . "</h1>";
// tribe_events_title();

// Tribe hook
do_action("tribe_events_after_the_title");

// close the header
echo "</header>";

// Tribe bar
tribe_get_template_part("modules/bar");

// Notices
tribe_the_notices();

// Month grid
tribe_get_template_part("month/loop", "grid");

// Tribe hook
do_action("tribe_events_before_footer");

// display the pagination
echo "<footer class='pagination-container'>";
tribe_get_template_part("month/nav");
echo "</footer>";

// Tribe hook
do_action("tribe_events_after_footer");

// JS stuff (I think)
tribe_get_template_part("month/mobile");
tribe_get_template_part("month/tooltip");
?>
