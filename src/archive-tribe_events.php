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

                                // display the content
                                the_content();
                            }
                        }
                        ?>
                    </div><!--/.post-->
                    <?php get_sidebar(); ?>
                </main><!--/.content-block-->
            </div><!--/.content-container-->
<?php get_footer(); ?>
