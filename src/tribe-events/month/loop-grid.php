<?php
if (!defined("ABSPATH")) {
	die("-1");
}

$days_of_week = tribe_events_get_days_of_week();
$week = 0;
global $wp_locale;

// Tribe hook
do_action("tribe_events_before_the_grid");

// open the calendar table
echo "<table class='tribe-events-calendar'>";

// display the day names
echo "<thead><tr>";
foreach ($days_of_week as $day) {
    echo "<th id='tribe-events-" . esc_attr(strtolower($day)) . "' title='" . esc_attr($day) . "' data-day-abbr='" . esc_attr($wp_locale->get_weekday_abbrev($day)) . "'>" . $day . "</th>";
}
echo "</tr></thead>";

// open the date grid
echo "<tbody><tr>";

// loop through each day
while (tribe_events_have_month_days()) {
    tribe_events_the_month_day();
    if ($week != tribe_events_get_current_week()) {
        $week ++;
        echo "</tr><tr>";
    }

    // Get data for this day within the loop.
    $daydata = tribe_events_get_current_month_day();

    // construct the td
    echo "<td class='";
    tribe_events_the_month_day_classes();
    echo "'";
    echo " data-day=\"" . esc_attr(isset($daydata["daynum"]) ? $daydata["date"] : "") . "\"";
    echo " data-tribejson=\"" . tribe_events_template_data(null, array("date_name" => tribe_format_date($daydata["date"], false))) . "\"";
    echo ">";

    // get the single month template
    tribe_get_template_part("month/single", "day");

    // close the td
    echo "</td>";
}

// close the date grid
echo "</tr></tbody>";

// close the calendar table
echo "</table>";

// Tribe hook
do_action("tribe_events_after_the_grid");
?>
