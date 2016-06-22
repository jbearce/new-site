<?php
if (!defined("ABSPATH")) {
	die("-1");
}

$events_label_plural = tribe_get_event_label_plural();
$posts = tribe_get_list_widget_events();

// Check if any event posts are found.
if ($posts) {

	echo "<ol class='event-list'>";
	// Setup the post data for each event.
	foreach ($posts as $post) {
		setup_postdata($post);
		echo "<li class='event-item ";
        tribe_events_event_classes();
        echo "'>";

        // Event Title

		do_action("tribe_events_list_widget_before_the_event_title");
        echo "<h4 class='title'><a href='" . esc_url(tribe_get_event_link()) . "' rel='bookmark'>" . get_the_title() . "</a></h4>";
		do_action("tribe_events_list_widget_after_the_event_title");

        // Event Time

        do_action("tribe_events_list_widget_before_the_meta");
		echo "<div class='duration'>" . tribe_events_event_schedule_details() . "</div>";
		do_action("tribe_events_list_widget_after_the_meta");

        echo "</li>";
    }
	echo "</ol>";

	echo "<p class='footer'><a href='" . esc_url(tribe_get_events_link()) . "' rel='bookmark'>";
    printf(esc_html__("View All %s", "new_site"), $events_label_plural);
    echo "</a></p>";

// No events were found.
} else {
	echo "<p class='footer'>";
    printf(esc_html__("There are no upcoming %s at this time.", "new_site"), strtolower($events_label_plural));
    echo "</p>";
}
?>
