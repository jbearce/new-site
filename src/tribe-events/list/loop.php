<?php
if (!defined("ABSPATH")) {
	die("-1");
}

global $post;
global $more;
$more = false;

while (have_posts()) {
    the_post();

    // open an article
    echo "<article class='article -excerpt'>";

    // Tribe hook
    do_action("tribe_events_inside_before_loop");

    // Month / Year Headers
    tribe_events_list_the_date_headers();

    // open a content
    echo "<div class='article_content'>";

    // get the single list template
    tribe_get_template_part("list/single", "event");

    // close the article_content
    echo "</div>";

    // Tribe hook
    do_action("tribe_events_inside_after_loop");

    // close the article
    echo "</article>";
}
?>
