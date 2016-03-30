<?php
if (!defined("ABSPATH")) {
	die("-1");
}

// Tribe hook
do_action("tribe_events_before_nav");

// open a pagination
echo "<p class='pagination text'>";

// display the previous link
tribe_the_day_link("previous day", "<span class='link -prev'><i class='fa fa-caret-left'></i> Previous Day</span>");

// display the next link
tribe_the_day_link("next day", "<span class='link -next'>Next Day <i class='fa fa-caret-right'></i></span>");

// close the pagination
echo "</p>";

// Tribe hook
do_action("tribe_events_after_nav");
?>
