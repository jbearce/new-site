<?php get_header(); ?>
            <div class="content-container">
                <main class="content-block">
                    <div class="post">
                        <?php
                        // display breadcrumbs
                        if (function_exists("yoast_breadcrumb")) {
                            yoast_breadcrumb("<nav class='breadcrumb'><p class='breadcrumb_text text'>", "</p></nav>");
                        }
                        ?>
                        <div class="article">
                            <?php
                            // get the term
                            $term = get_queried_object();

                            // get the term title
                            if (is_category()) {
                                $term_title = single_cat_title("", false);
                            } elseif (is_tag()) {
                                $term_title = single_tag_title("", false);
                            } elseif (is_tax() && $term->name) {
                                $term_title = $term->name;
                            } else {
                                $term_title = get_the_time("F, Y") . " " . __("Archive", "new-site");
                            }

                            // display the term title
                            echo "<header class='article_header header'><h1 class='article_title title'>{$term_title}</h1></header>";
                            ?>
                            <?php
                            if ($term->description || have_posts()) {
                                // open a content
                                echo "<div class='article_content'>";

                                // display the terms description
                                if ($term->description) {
                                    echo "<div class='article_user-content user-content'>" . wpautop($term->description) . "</div>";
                                }

                                // check if posts exist
                                if (have_posts()) {
                                    // loop through each post
                                    while (have_posts()) {
                                        // iterate the post index
                                        the_post();

                                        // open an article
                                        echo "<article class='article -excerpt'>";

                                        // display the image
                                        if (has_post_thumbnail()) {
                                            echo "<figure class='article_figure figure'><a class='article_link link' href='" . get_permalink() . "'>" . get_the_post_thumbnail($post->ID, "medium", array("class" => "article_image image")) . "</a></figure>";
                                        }

                                        // open a header
                                        echo "<header class='article_header header'>";

                                        // display the title
                                        echo "<h2 class='article_title title -sub'><a class='article_link link' href='" . get_permalink() . "'>" . get_the_title() . "</a></h2>";

                                        // display the meta information
                                        if (get_post_type() == "post") {
                                            // open a menu-wrapper and menu-list
                                            echo "<nav class='article_menu-container menu-container'><ul class='article_menu-list menu-list -meta'>";

                                            // display the date posted
                                            echo "<li class='article_menu-list_item menu-list_item'><a class='menu-list_link link' href='" . get_the_permalink() . "'><i class='fa fa-clock-o'></i> " . get_the_date() . "</a></li>";

                                            // get the category list
                                            $category_list = false;
                                            ob_start();
                                            get_the_category_list(", ");
                                            $category_list = ob_get_contents();
                                            ob_end_clean();

                                            // display the category list
                                            if ($category_list) {
                                                echo "<li class='article_menu-list_item menu-list_item'><i class='fa fa-folder'></i> " . preg_replace("/<a/im", "<a class='menu-list_link link'", $category_list) . "</li>";
                                            }

                                            // get the tag list
                                            $tag_list = false;
                                            ob_start();
                                            the_tags("<li class='article_menu-list_item menu-list_item'><i class='fa fa-tags'></i> ", ", ", "</li>");
                                            $tag_list = ob_get_contents();
                                            ob_end_clean();

                                            // display the tag list
                                            if ($tag_list) {
                                                echo preg_replace("/<a/im", "<a class='menu-list_link link'", $tag_list);
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
                                                    echo "<li class='article_menu-list_item menu-list_item'>" . preg_replace("/<a/im", "<a class='menu-list_link link'", $comments_link) . "</li>";
                                                }
                                            }

                                            // close the article_menu-list and article_menu-container
                                            echo "</ul></nav>";
                                        }

                                        // close the article_header
                                        echo "</header>";

                                        // display the post excerpt
                                        $post_excerpt = $post->post_excerpt ? $post->post_excerpt : wp_trim_words($post->post_content, 55) . " [...]";
                                        echo "<div class='article_content content'><p class='article_text text'>{$post_excerpt}</p></div>";

                                        // close the article
                                        echo "</article>";
                                    }
                                }

                                // close the aticle-content
                                echo "</div>";
                            }
                            ?>
                            <?php
                            // display the pagination links
                            if (get_adjacent_post(false, "", false) || get_adjacent_post(false, "", true)) {
                                echo "<footer class='pagination-container'><p class='pagination_text text'>";
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
                    </div><!--/.post-->
                    <?php get_sidebar(); ?>
                </main><!--/.content-block-->
            </div><!--/.content-container-->
<?php get_footer(); ?>
