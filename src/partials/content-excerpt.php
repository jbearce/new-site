<?php
if (have_posts()) {
    while (have_posts()) {
        the_post();

        echo "<article class='content_article article -excerpt'>";

        echo "<header class='article_header'>";

        echo "<h2 class='article_title title'><a class='article_link link' href='" . get_permalink() . "'>" . get_the_title() . "</a></h2>";

        if (get_post_type() === "post") {
            $categories = get_the_terms($post->ID, "category");
            $tags = get_the_terms($post->ID, "post_tag");
            $comments = get_comments_number();

            echo "<nav class='menu-list_container'><ul class='menu-list -meta'>";

            echo "<li class='menu-list_item'><a class='menu-list_link link' href='" . get_permalink() . "'><icon:clock-o> <time datetime='" . get_the_date("c") . "'>" . get_the_date() . "</time></a></li>";

            if ($categories) {
                echo "<li class='menu-list_item'>";

                echo "<icon:folder> ";

                $i = 0;

                foreach ($categories as $category) {
                    $i++;

                    echo "<a class='menu-list_link link' href='" . get_term_link($category) . "'>{$category->name}</a>";

                    if ($i < count($categories)) echo ", ";
                }

                echo "</li>"; // .menu-list_item
            } // if ($categories)

            if ($tags) {
                echo "<li class='menu-list_item'>";

                echo "<icon:tag> ";

                $i = 0;

                foreach ($tags as $tag) {
                    $i++;

                    echo "<a class='menu-list_link link' href='" . get_term_link($tag) . "'>{$tag->name}</a>";

                    if ($i < count($tags)) echo ", ";
                }

                echo "</li>"; // .menu-list_item
            } // if ($tags)

            if ($comments) {
                echo "<li class='menu-list_item'><a class='menu-list_link link' href='#comments'><icon:comment> {$comments} " . __("Comments", "new_site") . "</a></li>";
            }

            echo "</ul></nav>"; // .menu-list.-meta, .menu-list_container
        } // if (get_post_type() === "post")

        echo "</header>"; // .article_header

        echo "<div class='article_content'>";
        echo "<p class='article_text text'>" . get_better_excerpt($post->ID, 55, "&hellip; <a class='article_link link' href='" . get_permalink() . "'>" . __("Read More", "new_site") . "</a>") . "</p>";
        echo "</div>"; // .article_content

        echo "</article>"; // .content_article.article.-full
    } // while (have_posts())

} // if (have_posts())
else {
    $term = get_queried_object();
    $post_type = get_post_type() ? get_post_type() : __("post", "new_site");
    $error_message = is_archive() ? __("Sorry, no {$post_type}s could be found in this {$term->taxonomy}.", "new_site") : (is_search() ? (get_search_query() ? __("Sorry, no {$post_type} could be found for the search phrase &ldquo;" . get_search_query() . "&rdquo;.", "new_site") : __("No search query was entered.", "new_site")) : __("Sorry, no {$post_type}s could be found matching this criteria.", "new_site"));

    echo "<article class='content_article article -full'>";
    echo "<div class='article_content'>";
    echo "<p class='article_text text'>{$error_message}</p>";
    echo "</div>"; // .article_content
    echo "</article>"; // .content_article.article.-full
} // if (have_posts()) else
?>
