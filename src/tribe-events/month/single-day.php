<?php
if (!defined("ABSPATH")) {
	die("-1");
}

$day = tribe_events_get_current_month_day();
$events_label = (1 === $day["total_events"]) ? tribe_get_event_label_singular() : tribe_get_event_label_plural();

// day header
echo "<div id='tribe-events-daynum-{$day["daynum-id"]}'>";
if ($day["total_events"] > 0 && tribe_events_is_view_enabled("day")) {
	echo "<a class='link' href='" . esc_url(tribe_get_day_link($day["date"])) . "'>{$day["daynum"]}</a>";
} else {
	echo $day["daynum"];
}
echo "</div>";

// Events List
while ($day["events"]->have_posts()) {
    $day["events"]->the_post();
    tribe_get_template_part("month/single", "event");
}

// View More
if ($day["view_more"]) {
    $view_all_label = sprintf(
        _n(
        "View %1\$s %2\$s",
        "View All %1\$s %2\$s",
        $day["total_events"],
        "the-events-calendar"
    ),
    $day["total_events"],
    $events_label
    );
	echo "<div class='tribe-events-viewmore'><a href='" . esc_url($day["view_more"]) . "'>{$view_all_label} &raquo;</a></div>";
}
?>
