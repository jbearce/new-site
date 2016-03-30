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
                        <article class="article-card">
                            <header class="header">
                                <h1 class="title"><?php _e("404: Page Not Found", "new-site"); ?></h1>
                            </header><!--/.header-->
                            <div class="content">
                                <div class="user-content">
                                    <p><?php _e("This page could not be found. It may have been moved or deleted.", "new-site"); ?></p>
                                </div><!--/.user-content-->
                            </div><!--/.content-->
                        </article><!--/.article-card-->
                    </div><!--/.post-->
                </main><!--/.content-block-->
            </div><!--/.content-wrapper-->
<?php get_footer(); ?>
