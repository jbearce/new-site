<?php get_header(); ?>
            <div class="content-container">
                <main class="content-block">
                    <div class="post">
                        <?php
                        // display the breadcrumbs
                        if (function_exists("yoast_breadcrumb")) {
                            yoast_breadcrumb("<nav class='breadcrumb'><p class='breadcrumb_text text'>", "</p></nav>");
                        }
                        ?>
                        <?php
                        // check if posts exist
                        if (have_posts()) {
                            // loop through each post
                            while (have_posts()) {
                                // iterate the post index
                                the_post();

                                // open an article
                                echo "<article class='article'>";

                                // display the title
                                the_title("<header class='article_header header'><h1 class='article_title title'>", "</h1></header>");

                                // display the featured image
                                if (has_post_thumbnail()) {
                                    echo "<figure class='article_figure figure'>" . get_the_post_thumbnail($post->ID, "large", array("class" => "article_image image")) . "</figure>";
                                }

                                // display the content
                                echo "<div class='article_content content user-content'>";
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
                    </div><!--/.post-->
                </main><!--/.content-block-->
            </div><!--/.content-container-->
<?php get_footer(); ?>
