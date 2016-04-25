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
                        <?php
                        // check if posts exist
                        if (have_posts()) {
                            // loop through each post
                            while (have_posts()) {
                                // iterate the post index
                                    // iterate the post index
                                the_post();

                                // open an article card
                                echo "<article class='article-card'>";

                                // open a header
                                echo "<header class='header'>";

                                // display the title
                                the_title("<h1 class='title'>", "</h1>");

                                // display the meta information
                                if (get_post_type() == "post") {
                                    // open a menu-wrapper and menu-list
                                    echo "<nav class='menu-wrapper -icons'><ul class='menu-list'>";

                                    // display the date posted
                                    echo "<li class='menu-item'><a href='" . get_the_permalink() . "'><i class='fa fa-clock-o'></i> " . get_the_date() . "</a></li>";

                                    // display the category list
                                    if (get_the_category_list()) {
                                        echo "<li class='menu-item'><i class='fa fa-folder'></i> " . get_the_category_list(", ") . "</li>";
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

                                // display the featured image
                                if (has_post_thumbnail()) {
                                    echo "<figure class='image'>" . get_the_post_thumbnail($post->ID, "large") . "</figure>";
                                }

                                // display the content
                                echo "<div class='content'><div class='user-content'>";
                                the_content();
                                echo "</div></div>";

                                // display the comemnts
                                if (comments_open() || get_comments_number() > 0) {
                                    comments_template();
                                }

                                // close the article card
                                echo "</article>";
                            }
                        }
                        ?>
                    </div><!--/.post-->
                    <?php get_sidebar(); ?>
                </main><!--/.content-block-->
            </div><!--/.content-wrapper-->
<?php get_footer(); ?>
