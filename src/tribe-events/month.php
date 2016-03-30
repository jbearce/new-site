<?php
if (!defined("ABSPATH")) {
	die("-1");
}

// Tribe hook
do_action("tribe_events_before_template");

// Main events content
tribe_get_template_part("month/content");

// Tribe hook
do_action("tribe_events_after_template");
?>
