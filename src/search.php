<?php get_header(); ?>
            <div class="content-wrapper">
                <main class="content-block">
                    <div class="post">
                        <?php
                        // display breadcrumbs
                        if (function_exists("yoast_breadcrumb")) {
                            yoast_breadcrumb("<nav class='breadcrumb-list'><p class='text'>", "</p></nav>");
                        }
                        ?>
                        <div class="article-card">
                            <header class="header">
                                <?php get_search_form(); ?>
                            </header><!--/.header-->
                            <div class="content">
                                <?php
                                // display the posts
                                if (have_posts()) {
                                    while (have_posts()) {
                                        the_post();

                                        // open an article card
                                        echo "<article class='article-card -excerpt'>";

                                        // display the image
                                        if (has_post_thumbnail()) {
                                            echo "<figure class='image'><a href='" . get_permalink() . "'>" . get_the_post_thumbnail($post->ID, "medium") . "</a></figure>";
                                        }

                                        // open a header
                                        echo "<header class='header'>";

                                        // display the title
                                        echo "<h2 class='title'><a href='" . get_permalink() . "'>" . get_the_title() . "</a></h2>";

                                        // display the meta information
                                        if (get_post_type() == "post") {
                                            // open a menu-wrapper and menu-list
                                            echo "<nav class='menu-wrapper -icons'><ul class='menu-list'>";

                                            // display the date posted
                                            echo "<li class='menu-item'><a href='" . get_the_permalink() . "'><i class='fa fa-clock-o'></i> " . get_the_date() . "</a></li>";

                                            // display the category list
                                            if (get_the_category_list()) {
                                                echo "<li class='menu-item'><i class='fa fa-fodler'></i> " . get_the_category_list(", ") . "</li>";
                                            }

                                            // display the tag list
                                            the_tags("<li class='menu-item'><i class='fa fa-tags'></i> ", ", ", "</li>");

                                            // display the comment count
                                            if (comments_open() || get_comments_number() > 0) {
                                                echo "<li class='menu-item'>";
                                                comments_popup_link("<i class='fa fa-comment-o'></i> No Comments", "<i class='fa fa-comment'></i> 1 Comment", "<i class='fa fa-comments'></i> % Comments");
                                                echo "</li>";
                                            }

                                            // close the menu-list and menu-wrapper
                                            echo "</ul></nav>";
                                        }

                                        // close the header
                                        echo "</header>";

                                        // display the post excerpt
                                        $post_excerpt = $post->post_excerpt ? $post->post_excerpt : wp_trim_words($post->post_content, 55) . " [...]";
                                        echo "<div class='content'><p class='excerpt text'>{$post_excerpt}</p></div>";

                                        // close the article card
                                        echo "</article>";
                                    }

                                } else {
                                    echo "<p class='text'>" . __("No results found for ", "new-site") . "<strong>" . get_search_query() . "</strong>.</p>";
                                }
                                ?>
                            </div><!--/.content-->
                            <?php
                            // display the pagination links
                            if (get_adjacent_post(false, "", false) || get_adjacent_post(false, "", true)) {
                                echo "<footer class='pagination-block'><p class='pagination text'>";
                                if (get_adjacent_post(false, "", false)) {
                                    previous_posts_link("<i class='fa fa-caret-left'></i> Previous Page");
                                }
                                if (get_adjacent_post(false, "", true)) {
                                    next_posts_link("Next Page <i class='fa fa-caret-right'></i>");
                                }
                                echo "</p></footer>";
                            }
                            ?>
                        </div><!--/.article-card-->
                    </div><!--/.post-->
                    <?php get_sidebar(); ?>
                </main><!--/.content-block-->
            </div><!--/.content-wrapper-->
<?php get_footer(); ?>
