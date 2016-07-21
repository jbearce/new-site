<?php
if (have_posts()) {
    while (have_posts()) {
        the_post();

        echo "<article class='content_article article'>";

        echo "<header class='article_header'>";

        the_title("<h1 class='article_title title'>", "</h1>");

        if (get_post_type() === "post") {
            $categories = get_the_terms($post->ID, "category");
            $tags = get_the_terms($post->ID, "post_tag");
            $comments = get_comments_number();

            echo "<nav class='menu-container'><ul class='menu-list -meta'>";

            echo "<li class='menu-list_item'><a class='menu-list_link link' href='" . get_permalink() . "'><i class='fa fa-clock-o'></i> <time datetime='" . get_the_date("c") . "'>" . get_the_date() . "</time></a></li>";

            if ($categories) {
                echo "<li class='menu-list_item'>";

                echo "<i class='fa fa-folder'></i> ";

                $i = 0;

                foreach ($categories as $category) {
                    $i++;

                    echo "<a class='menu-list_link link' href='" . get_term_link($category) . "'>{$category->name}</a>";

                    if ($i < count($categories)) {
                        echo ", ";
                    }
                }

                echo "</li>";
            }

            if ($tags) {
                echo "<li class='menu-list_item'>";

                echo "<i class='fa fa-tag'></i> ";

                $i = 0;

                foreach ($tags as $tag) {
                    $i++;

                    echo "<a class='menu-list_link link' href='" . get_term_link($tag) . "'>{$tag->name}</a>";

                    if ($i < count($tags)) {
                        echo ", ";
                    }
                }

                echo "</li>";
            }

            if ($comments) {
                echo "<li class='menu-list_item'><a class='menu-list_link link' href='#comments'><i class='fa fa-comment'></i> {$comments} " . __("Comments", "new_site") . "</a></li>";
            }

            // close menu-list -meta, menu-container
            echo "</ul></nav>";
        }

        // close article_header
        echo "</header>";

        echo "<div class='article_content'><div class='article_user-content user-content'>";

        the_content();

        // close article_user-content user-content, article_content
        echo "</div></div>";

        // close content_article article -full
        echo "</article>";
    }
} else {
    $post_type = get_post_type() ? get_post_type() : __("post", "new_site");

    echo "<article class='content_article article -full'>";

    echo "<div class='article_content'>";

    echo "<p class='article_text text'>" . __("Sorry, no {$post_type} could be found matching this criteria.", "new_site") . "</p>";

    // close article_content
    echo "</div>";

    // close content_article article -full
    echo "</article>";
}
?>
