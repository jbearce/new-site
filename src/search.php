<?php get_header(); ?>
<div class="content-block --fullbleed">
    <div class="content__inner">
        <div class="content__post">
            <?php do_action("__gulp_init_namespace___before_content"); ?>

            <div class="content_search-form__container search-form__container" id="mobile-search">
                <?php get_search_form(); ?>
            </div><!--/.content_search-form__container.search-form__container-->

            <?php
            if (have_posts()) {
                while (have_posts()) { the_post();
                    __gulp_init_namespace___get_template_part("partials/articles/post-excerpt.php", array("post" => $post, "class" => "content__article"));
                }
            } else {
                __gulp_init_namespace___get_template_part("partials/articles/post-none.php", array("class" => "content__article", "error" => __gulp_init_namespace___get_no_posts_message(get_queried_object())));
            }
            ?>

            <?php __gulp_init_namespace___get_template_part("partials/modules/menu-list-pagination.php"); ?>

            <?php do_action("__gulp_init_namespace___after_content"); ?>
        </div><!--/.content__post-->
    </div><!--/.content__inner-->
</div><!--/.content-block.--fullbleed-->
<?php get_footer(); ?>
