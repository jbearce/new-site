<?php get_header(); ?>
<div class="content-block -fullbleed">
    <div class="content_inner">
        <div class="content_post">
            <?php do_action("α__init_namespace_before_content"); ?>

            <article class="content_article article">
                <header class="article_header">
                    <h1 class="article_title title"><?php _e("404: Page Not Found", "α__init_namespace"); ?></h1>
                </header><!--/.article_header-->
                <div class="article_content">
                    <p class="article_text text"><?php _e("This page could not be found. It may have been moved or deleted.", "α__init_namespace"); ?></p>
                </div><!--/.article_content-->
            </article><!--/.content_article.article-->

            <?php do_action("α__init_namespace_after_content"); ?>
        </div><!--/.content_post-->
    </div><!--/.content_inner-->
</div><!--/.content-block.-fullbleed-->
<?php get_footer(); ?>
