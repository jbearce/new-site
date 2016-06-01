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
                        <article class="article">
                            <header class="article_header header">
                                <h1 class="article_title title"><?php _e("404: Page Not Found", "new-site"); ?></h1>
                            </header><!--/.article_header-->
                            <div class="article_content content user-content">
                                <p><?php _e("This page could not be found. It may have been moved or deleted.", "new-site"); ?></p>
                            </div><!--/.article_content.content.user-content-->
                        </article><!--/.article-->
                    </div><!--/.post-->
                </main><!--/.content-block-->
            </div><!--/.content-container-->
<?php get_footer(); ?>
