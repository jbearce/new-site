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

            echo "<nav class='menu-list_container'><ul class='menu-list -meta'>";

            echo "<li class='menu-list_item'><a class='menu-list_link link' href='" . get_permalink() . "'><icon:clock-o> <time datetime='" . get_the_date("c") . "'>" . get_the_date() . "</time></a></li>";

            if ($categories) {
                echo "<li class='menu-list_item'>";

                echo "<icon:folder> ";

                $i = 0;

                foreach ($categories as $category) {
                    $i++;

                    echo "<a class='menu-list_link link' href='" . get_term_link($category) . "'>{$category->name}</a>";

                    if ($i < count($categories)) {
                        echo ", ";
                    }
                } // foreach ($categories as $category)

                echo "</li>"; // .menu-list_item
            } // if ($categories)

            if ($tags) {
                echo "<li class='menu-list_item'>";

                echo "<icon:tag> ";

                $i = 0;

                foreach ($tags as $tag) {
                    $i++;

                    echo "<a class='menu-list_link link' href='" . get_term_link($tag) . "'>{$tag->name}</a>";

                    if ($i < count($tags)) {
                        echo ", ";
                    }
                } // foreach ($tags as $tag)

                echo "</li>"; // .menu-list_item
            } // if ($tags)

            if ($comments) {
                echo "<li class='menu-list_item'><a class='menu-list_link link' href='#comments'><icon:comment> {$comments} " . __("Comments", "new_site") . "</a></li>";
            }

            echo "</ul></nav>"; // .menu-list.-meta, .menu-list_container
        } // if (get_post_type() === "post")

        echo "</header>"; // .article_header

        echo "<div class='article_content'><div class='article_user-content user-content'>";

        the_content();

        echo "</div></div>"; // .article_user-content.user-content, .article_content

        echo "</article>"; // .content_article.article.-full
    } // while (have_posts())
} // if (have_posts())
else {
    $post_type = get_post_type() ? get_post_type() : __("post", "new_site");

    echo "<article class='content_article article -full'>";

    echo "<div class='article_content'>";

    echo "<p class='article_text text'>" . __("Sorry, no {$post_type} could be found matching this criteria.", "new_site") . "</p>";

    echo "</div>"; // .article_content

    echo "</article>"; // .content_article.article.-full
} // if (have_posts()) else
?>
