<?php
$post_id    = get_option("page_for_posts") ? get_post(get_option("page_for_posts")) : false;
$post_title = $post_id && get_the_title($post_id) ? get_the_title($post_id) : __("Latest Posts", "__gulp_init_namespace__");
?>
<?php get_header(); ?>
<?php
__gulp_init_namespace___get_template_part("partials/layouts/hero.php", array(
    "title" => $post_title
));
?>
<div class="content-block content-block--fullbleed">
    <div class="content__inner">
        <div class="content__post">
            <?php do_action("__gulp_init_namespace___before_content"); ?>

            <?php if ($post_title): ?>
                <article class="content__article article article--introduction">
                    <header class="article__header">
                        <h1 class="article__title title"><?php echo $post_title; ?></h1>
                    </header>
                </article>
            <?php endif; ?>

            <?php
            if (have_posts()) {
                while (have_posts()) { the_post();
                    __gulp_init_namespace___get_template_part("partials/articles/post-excerpt.php", array(
                        "post" => $post,
                        "class" => "content__article",
                    ));
                }
            } else {
                __gulp_init_namespace___get_template_part("partials/articles/post-none.php", array(
                    "class" => "content__article",
                    "error" => __gulp_init_namespace___get_no_posts_message(get_queried_object()),
                ));
            }
            ?>

            <?php __gulp_init_namespace___get_template_part("partials/modules/menu-list-pagination.php"); ?>

            <?php do_action("__gulp_init_namespace___after_content"); ?>
        </div><!--/.content__post-->
    </div><!--/.content__inner-->
</div><!--/.content-block-->
<?php get_footer(); ?>
