<?php get_header(); ?>
            <div class="content-container">
                <main class="content-block">
                    <div class="row">
                        <div class="col">
                            <div class="content_post">
                                <?php
                                // display breadcrumbs
                                if (function_exists("yoast_breadcrumb")) {
                                    yoast_breadcrumb("<nav class='breadcrumb-menu'><p class='breadcrumb-text text'>", "</p></nav>");
                                }
                                ?>
                                <?php
                                // check if posts exist
                                if (have_posts()) {
                                    // loop through each post
                                    while (have_posts()) {
                                        // iterate the post index
                                            // iterate the post index
                                        the_post();

                                        // open an article
                                        echo "<article class='article'>";

                                        // open a header
                                        echo "<header class='article_header'>";

                                        // display the title
                                        the_title("<h1 class='article_title title'>", "</h1>");

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

                                        // close the header
                                        echo "</header>";

                                        // display the featured image
                                        if (has_post_thumbnail()) {
                                            echo "<figure class='article_figure'>" . get_the_post_thumbnail($post->ID, "large", array("class" => "article_image")) . "</figure>";
                                        }

                                        // display the content
                                        echo "<div class='article_content user-content'>";
                                        the_content();
                                        echo "</div>";

                                        // display the comments
                                        if (comments_open() || get_comments_number() > 0) {
                                            comments_template();
                                        }

                                        // close the article
                                        echo "</article>";
                                    }
                                }
                                ?>
                            </div><!--/.content_post-->
                        </div><!--/.col-->
                        <?php get_sidebar(); ?>
                    </div><!--/.row-->
                </main><!--/.content-block-->
            </div><!--/.content-container-->
<?php get_footer(); ?>
