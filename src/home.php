<?php get_header(); ?>
            <div class="content-container">
                <main class="l-content-block">
                    <div class="row">
                        <div class="col">
                            <div class="content-post">
                                <?php
                                // display the breadcrumbs
                                if (function_exists("yoast_breadcrumb")) {
                                    yoast_breadcrumb("<nav class='breadcrumb-menu'><p class='breadcrumb-text text'>", "</p></nav>");
                                }
                                ?>
                                <div class="article">
                                    <?php
                                    // get the page title
                                    $home_title = get_option("show_on_front") == "page" ? (get_option("page_for_posts") ? get_the_title(get_option("page_for_posts")) : "Blog") : "Blog";

                                    // display the page title
                                    echo "<header class='article-header'><h1 class='article-title title'>{$home_title}</h1></header>";
                                    ?>
                                    <?php
                                    // check if posts exist
                                    if (have_posts()) {
                                        // open a content
                                        echo "<div class='article-content'>";

                                        // loop through each post
                                        while (have_posts()) {
                                            // iterate the post index
                                            the_post();

                                            // open an article
                                            echo "<article class='article -excerpt'>";

                                            // display the image
                                            if (has_post_thumbnail()) {
                                                echo "<figure class='article-figure'><a class='article-link link' href='" . get_permalink() . "'>" . get_the_post_thumbnail($post->ID, "medium", array("class" => "article-image")) . "</a></figure>";
                                            }

                                            // open a header
                                            echo "<header class='article-header'>";

                                            // display the title
                                            echo "<h2 class='article-title title -sub'><a class='article-link link' href='" . get_permalink() . "'>" . get_the_title() . "</a></h2>";

                                            // display the meta information
                                            if (get_post_type() == "post") {
                                                // open a menu-wrapper and menu-list
                                                echo "<nav class='article-menu-container menu-container'><ul class='article-menu-list menu-list -meta'>";

                                                // display the date posted
                                                echo "<li class='article-menu-item menu-item'><a class='article-menu-link menu-link link' href='" . get_the_permalink() . "'><i class='fa fa-clock-o'></i> " . get_the_date() . "</a></li>";

                                                // get the category list
                                                $category_list = false;
                                                ob_start();
                                                get_the_category_list(", ");
                                                $category_list = ob_get_contents();
                                                ob_end_clean();

                                                // display the category list
                                                if ($category_list) {
                                                    echo "<li class='article-menu-item menu-item'><i class='fa fa-folder'></i> " . preg_replace("/<a/im", "<a class='menu-link link'", $category_list) . "</li>";
                                                }

                                                // get the tag list
                                                $tag_list = false;
                                                ob_start();
                                                the_tags("<li class='article-menu-item menu-item'><i class='fa fa-tags'></i> ", ", ", "</li>");
                                                $tag_list = ob_get_contents();
                                                ob_end_clean();

                                                // display the tag list
                                                if ($tag_list) {
                                                    echo preg_replace("/<a/im", "<a class='menu-link link'", $tag_list);
                                                }

                                                // display the comment count
                                                if (comments_open() || get_comments_number() > 0) {
                                                    // get the comments link
                                                    $comments_link = false;
                                                    ob_start();
                                                    comments_popup_link("<i class='fa fa-comment-o'></i> No Comments", "<i class='fa fa-comment'></i> 1 Comment", "<i class='fa fa-comments'></i> % Comments");
                                                    $comments_link = ob_get_contents();
                                                    ob_end_clean();

                                                    // display the comments link
                                                    if ($comments_link) {
                                                        echo "<li class='article-menu-item menu-item'>" . preg_replace("/<a/im", "<a class='menu-link link'", $comments_link) . "</li>";
                                                    }
                                                }

                                                // close the article-menu-list and article-menu-container
                                                echo "</ul></nav>";
                                            }

                                            // close the article-header
                                            echo "</header>";

                                            // display the post excerpt
                                            $post_excerpt = $post->post_excerpt ? $post->post_excerpt : wp_trim_words($post->post_content, 55) . " [...]";
                                            echo "<div class='article-content'><p class='article-text text'>{$post_excerpt}</p></div>";

                                            // close the article
                                            echo "</article>";
                                        }

                                        // close the content
                                        echo "</div>";
                                    }
                                    ?>
                                    <?php
                                    // display the pagination links
                                    if (get_adjacent_post(false, "", false) || get_adjacent_post(false, "", true)) {
                                        echo "<footer class='pagination-menu'><p class='pagination-text text'>";
                                        if (get_adjacent_post(false, "", false)) {
                                            previous_posts_link("<i class='fa fa-caret-left'></i> Previous Page");
                                        }
                                        if (get_adjacent_post(false, "", true)) {
                                            next_posts_link("Next Page <i class='fa fa-caret-right'></i>");
                                        }
                                        echo "</p></footer>";
                                    }
                                    ?>
                                </div><!--/.article-->
                            </div><!--/.content-post-->
                        </div><!--/.col-->
                        <?php get_sidebar(); ?>
                    </div><!--/.row-->
                </main><!--/.l-content-block-->
            </div><!--/.content-container-->
<?php get_footer(); ?>
