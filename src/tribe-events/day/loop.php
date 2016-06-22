<?php
if (!defined("ABSPATH")) {
	die("-1");
}

global $more, $post, $wp_query;
$more = false;
$current_timeslot = null;

while (have_posts()) {
    the_post();

    // open an article
    echo "<article class='article'>";

    // Tribe hook
    do_action("tribe_events_inside_before_loop");

    // open a content
    echo "<div class='article_content'>";

    // get the single day template
    tribe_get_template_part("day/single", "event");

    // close the content
    echo "</div>";

    // Tribe hook
    do_action("tribe_events_inside_after_loop");

    // close the article
    echo "</article>";
}

// close the article-list
echo "</ul>";
?>
