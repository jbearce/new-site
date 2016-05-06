<?php get_header(); ?>
            <div class="content-block">
                <main class="content__inner">
                    <div class="post">
                        <?php
                        // display the breadcrumbs
                        if (function_exists("yoast_breadcrumb")) {
                            yoast_breadcrumb("<nav class='breadcrumb'><p class='breadcrumb__text text'>", "</p></nav>");
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
                </main><!--/.content__inner-->
            </div><!--/.content-block-->
<?php get_footer(); ?>
