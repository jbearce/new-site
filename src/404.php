<?php get_header(); ?>
            <div class="content-block">
                <main class="content__inner">
                    <div class="post">
                        <?php
                        // display breadcrumbs
                        if (function_exists("yoast_breadcrumb")) {
                            yoast_breadcrumb("<nav class='breadcrumb'><p class='breadcrumb__text text'>", "</p></nav>");
                        }
                        ?>
                        <article class="article">
                            <header class="article__header header">
                                <h1 class="article__title title"><?php _e("404: Page Not Found", "new-site"); ?></h1>
                            </header><!--/.article__header-->
                            <div class="article__content content user-content">
                                <p><?php _e("This page could not be found. It may have been moved or deleted.", "new-site"); ?></p>
                            </div><!--/.article__content.content.user-content-->
                        </article><!--/.article-->
                    </div><!--/.post-->
                </main><!--/.content__inner-->
            </div><!--/.content-block-->
<?php get_footer(); ?>
