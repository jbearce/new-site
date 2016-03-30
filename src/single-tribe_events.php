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

                                // display the content
                                the_content();
                            }
                        }
                        ?>
                    </div><!--/.post-->
                    <?php get_sidebar(); ?>
                </main><!--/.content-block-->
            </div><!--/.content-wrapper-->
<?php get_footer(); ?>
