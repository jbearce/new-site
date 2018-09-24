<?php get_header(); ?>
<div class="content-block --fullbleed">
    <div class="content__inner">
        <div class="content__post">
            <?php do_action("__gulp_init__namespace_before_content"); ?>

            <article class="content__article article">
                <header class="article__header">
                    <h1 class="article__title title"><?php _e("No Internet Connection", "__gulp_init__namespace"); ?></h1>
                </header><!--/.article__header-->
                <div class="article__content">
                    <p class="article__text text"><?php _e("This page could not be accessed because you are not connected to the internet. Please try again once you've regained a connection.", "__gulp_init__namespace"); ?></p>
                </div><!--/.article__content-->
            </article><!--/.content__article.article-->

            <?php do_action("__gulp_init__namespace_after_content"); ?>
        </div><!--/.content__post-->
    </div><!--/.content__inner-->
</div><!--/.content-block.--fullbleed-->
<?php get_footer(); ?>
