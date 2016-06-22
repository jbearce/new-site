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
                                <article class="article">
                                    <header class="article_header">
                                        <h1 class="article_title title"><?php _e("404: Page Not Found", "new_site"); ?></h1>
                                    </header><!--/.article_header-->
                                    <div class="article_content user-content">
                                        <p><?php _e("This page could not be found. It may have been moved or deleted.", "new_site"); ?></p>
                                    </div><!--/.article_content.user-content-->
                                </article><!--/.article-->
                            </div><!--/.content_post-->
                        </div><!--/.col-->
                    </div><!--/.row-->
                </main><!--/.content-block-->
            </div><!--/.content-container-->
<?php get_footer(); ?>
