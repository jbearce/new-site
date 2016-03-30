<?php
// get the event data
$events_label_singular = tribe_get_event_label_singular();
$events_label_plural = tribe_get_event_label_plural();
$event_id = get_the_ID();

if (have_posts()) {
    while (have_posts()) {
        the_post();

        // display Tribe notices
    	tribe_the_notices();

        // open an article-card
        echo "<article class='article-card'>";

        // open a post-header
        echo "<header class='header'>";

        // display the title
        the_title("<h1 class='title'>", "</h1>");

        // display the event meta
        echo "<h2 class='title -sub'>";
        echo tribe_events_event_schedule_details($event_id, "", "");
        if (tribe_get_cost()) {
            echo " | ";
            echo tribe_get_cost(null, true);
        }
        echo "</h2>";

        // close the header
        echo "</header>";

        // check if a featured image exists
        if (has_post_thumbnail()) {
            // display the featured image
            echo "<figure class='image'>" . get_the_post_thumbnail($event_id, "large") . "</figure>";
        }

        // open a content
        echo "<div class='content'>";

        // Tribe hook
        do_action("tribe_events_single_event_before_the_content");

        // display the content
        echo "<div class='user-content'>";
        the_content();
        echo "</div>";

        // Tribe hook
        do_action("tribe_events_single_event_after_the_content");

        // display the comments
        if (comments_open() || get_comments_number() > 0) {
            comments_template();
        }

        // close the content
        echo "</div>";

        // Tribe hook
		do_action("tribe_events_single_event_before_the_meta");

        // display meta
        tribe_get_template_part("modules/meta");

        // Tribe hook
        do_action("tribe_events_single_event_after_the_meta");

        // close the article-card
        echo "</article>";
    }
}

// display a "return to list" button
echo "<a class='button' href='" . esc_url(tribe_get_events_link()) . "'>";
printf("<i class='fa fa-caret-left'></i> " . esc_html__("Back to All %s", "new-site"), $events_label_plural);
echo "</a>";

// display the pagination
echo "<footer class='pagination-block'><p class='pagination text'>";
tribe_the_prev_event_link("<span class='link -prev'><i class='fa fa-caret-left'></i> %title%</span>");
tribe_the_next_event_link("<span class='link -next'>%title% <i class='fa fa-caret-right'></i></span>");
echo "</p></footer>";
?>
