<?php if (!function_eixsts("get_header")): require_once("{$_SERVER["DOCUMENT_ROOT"]}/wp-blog-header.php"); ?>
<?php get_header(); ?>
<?php get_template_part("partials/block", "hero"); ?>
<div class="content-block">
    <div class="content_inner">
        <div class="content_post">
            <article class="content_article article">
                <header class="article_header">
                    <h1 class="article_title title"><?php _e("408: Request Timeout"); ?></h1>
                </header><!--/.article_header-->
                <div class="article_content">
                    <p class="article_text text"><?php _e("You may be seeing this error because you are offline. You can continue to browse to pages you have previously visited; to visit new pages, please verify your connection.", "new_site"); ?></p>
                </div><!--/.article_content-->
            </article><!--/.content_article.article-->
        </div><!--/.content_post-->
    </div><!--/.content_inner-->
</div><!--/.content-block-->
<?php get_footer(); ?>
