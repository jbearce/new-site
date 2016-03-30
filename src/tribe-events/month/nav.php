<?php
if (!defined("ABSPATH")) {
	die("-1");
}

// Tribe hook
do_action("tribe_events_before_nav");

// open a pagination
echo "<p class='pagination text'>";

// check if a previous link exists
$prev_url = tribe_get_previous_month_link();
$prev_text = tribe_get_previous_month_text();

// display the previous link
if ($prev_url) {
    echo "<a class='link -prev' href='{$prev_url}' rel='prev'><i class='fa fa-caret-left'></i> {$prev_text}</a>";
}

// check if a next link exists
$next_url = tribe_get_next_month_link();
$next_text = tribe_get_next_month_text();

// display the next link
if ($next_url) {
    echo "<a class='link -next' href='{$next_url}' rel='next'>{$next_text} <i class='fa fa-caret-right'></i></a>";
}

// close the pagination
echo "</p>";

// Tribe hook
do_action("tribe_events_after_nav");
?>
