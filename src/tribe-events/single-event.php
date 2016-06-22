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

        // open an article
        echo "<article class='article'>";

        // open a header
        echo "<header class='article_header'>";

        // display the title
        the_title("<h1 class='article_title title'>", "</h1>");

        // display the event meta
        echo "<h2 class='article_title title -sub'>";
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
            echo "<figure class='article_figure'>" . get_the_post_thumbnail($event_id, "large", array("class" => "article_image")) . "</figure>";
        }

        // open a content
        echo "<div class='article_content'>";

        // Tribe hook
        do_action("tribe_events_single_event_before_the_content");

        // display the content
        echo "<div class='article_user-content user-content'>";
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

        // close the article
        echo "</article>";
    }
}

// display a "return to list" button
echo "<a class='button' href='" . esc_url(tribe_get_events_link()) . "'>";
printf("<i class='fa fa-caret-left'></i> " . esc_html__("Back to All %s", "new_site"), $events_label_plural);
echo "</a>";

// display the pagination
echo "<footer class='pagination-menu'><p class='pagination-text text'>";
tribe_the_prev_event_link("<span class='link -prev'><i class='fa fa-caret-left'></i> %title%</span>");
tribe_the_next_event_link("<span class='link -next'>%title% <i class='fa fa-caret-right'></i></span>");
echo "</p></footer>";
?>
