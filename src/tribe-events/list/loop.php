<?php
if (!defined("ABSPATH")) {
	die("-1");
}

global $post;
global $more;
$more = false;

// open an article-list
echo "<ul class='article-list'>";

while (have_posts()) {
    the_post();

    // open an article-item and an article-card
    echo "<li class='article-item'><article class='article-card'>";

    // Tribe hook
    do_action("tribe_events_inside_before_loop");

    // Month / Year Headers
    tribe_events_list_the_date_headers();

    // open a content
    echo "<div class='content'>";

    // get the single list template
    tribe_get_template_part("list/single", "event");

    // close the article-card-content
    echo "</div>";

    // Tribe hook
    do_action("tribe_events_inside_after_loop");

    // close the article-card and article-item
    echo "</article></li>";
}

// close the article-list
echo "</ul>";
?>
