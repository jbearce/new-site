<?php require_once("{$_SERVER["DOCUMENT_ROOT"]}/wp-blog-header.php"); ?>
<?php http_response_code(200); ?>
<?php
add_filter("wpseo_title", function($title) {
    return $title = sprintf(__("408: Request Timeout - %s", "__gulp_init__namespace"), get_bloginfo("name"));
});
?>
<?php get_header(); ?>
<div class="content-block --fullbleed">
    <div class="content__inner">
        <div class="content__post">
            <?php do_action("__gulp_init__namespace_before_content"); ?>

            <article class="content__article article">
                <header class="article__header">
                    <h1 class="article__title title"><?php _e("408: Request Timeout", "__gulp_init__namespace"); ?></h1>
                </header><!--/.article__header-->
                <div class="article__content">
                    <p class="article__text text"><?php _e("You may be seeing this error because you are offline. You can continue to browse to pages you have previously visited, but some features may not work as expected; to visit new pages, please verify your connection.", "__gulp_init__namespace"); ?></p>
                </div><!--/.article__content-->
            </article><!--/.content__article.article-->

            <?php do_action("__gulp_init__namespace_after_content"); ?>
        </div><!--/.content__post-->
    </div><!--/.content__inner-->
</div><!--/.content-block.--fullbleed-->
<?php get_footer(); ?>
