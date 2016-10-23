<?php get_header(); ?>
<?php get_template_part("partials/hero", "block"); ?>
<div class="content-block">
    <div class="content_inner">
        <article class="content_article article -full">
            <header class="article_header">
                <h1 class="article_title title"><?php _e("404: Page Not Found"); ?></h1>
            </header><!--/.article_header-->
            <div class="article_content">
                <p class="article_text text"><?php _e("This page could not be found. It may have been moved or deleted.", "new_site"); ?></p>
            </div><!--/.article_content-->
        </article><!--/.content_article.article.-full-->
    </div><!--/.content_inner-->
</div><!--/.content-block-->
<?php get_footer(); ?>
