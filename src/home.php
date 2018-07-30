<?php
$post_id    = get_option("page_for_posts") ? get_post(get_option("page_for_posts")) : false;
$post_title = $post_id && get_the_title($post_id) ? get_the_title($post_id) : __("Latest Posts", "__gulp_init__namespace");
?>
<?php get_header(); ?>
<?php __gulp_init__namespace_get_template_part("partials/layouts/hero.php", array("title" => $post_title)); ?>
<div class="content-block -fullbleed">
    <div class="content_inner">
        <div class="content_post">
            <?php do_action("__gulp_init__namespace_before_content"); ?>

            <?php if ($post_title): ?>
                <article class="content_article article -introduction">
                    <header class="article_header">
                        <h1 class="article_title title"><?php echo $post_title; ?></h1>
                    </header><!--/.article_header-->
                </article><!--/.content_article.article.-introduction-->
            <?php endif; ?>

            <?php
            if (have_posts()) {
                while (have_posts()) { the_post();
                    __gulp_init__namespace_get_template_part("partials/articles/post-excerpt.php", array("post" => $post, "class" => "content_article"));
                }
            } else {
                __gulp_init__namespace_get_template_part("partials/articles/post-none.php", array("class" => "content_article", "error" => get_no_posts_message(get_queried_object())));
            }
            ?>

            <?php __gulp_init__namespace_get_template_part("partials/modules/menu-list-pagination.php"); ?>

            <?php do_action("__gulp_init__namespace_after_content"); ?>
        </div><!--/.content_post-->
    </div><!--/.content_inner-->
</div><!--/.content-block.-fullbleed-->
<?php get_footer(); ?>
