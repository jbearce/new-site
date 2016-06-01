<?php get_header(); ?>
            <div class="content-container">
                <main class="l-content-block">
                    <div class="row">
                        <div class="col">
                            <div class="content-post">
                                <?php
                                // display breadcrumbs
                                if (function_exists("yoast_breadcrumb")) {
                                    yoast_breadcrumb("<nav class='breadcrumb-menu'><p class='breadcrumb-text text'>", "</p></nav>");
                                }
                                ?>
                                <article class="article">
                                    <header class="article-header">
                                        <h1 class="article-title title"><?php _e("404: Page Not Found", "new-site"); ?></h1>
                                    </header><!--/.article-header-->
                                    <div class="article-content user-content">
                                        <p><?php _e("This page could not be found. It may have been moved or deleted.", "new-site"); ?></p>
                                    </div><!--/.article-content.user-content-->
                                </article><!--/.article-->
                            </div><!--/.content-post-->
                        </div><!--/.col-->
                    </div><!--/.row-->
                </main><!--/.l-content-block-->
            </div><!--/.content-container-->
<?php get_footer(); ?>
