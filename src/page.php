<?php get_header(); ?>
            <div class="content-wrapper">
                <main class="content-block">
                    <div class="post">
                        <?php
                        // display the breadcrumbs
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
                                the_post();

                                // open an article card
                                echo "<article class='article-card'>";

                                // display the title
                                the_title("<header class='header'><h1 class='title'>", "</h1></header>");

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
