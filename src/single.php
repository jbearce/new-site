<?php get_header(); ?>
<?php __gulp_init__namespace_get_template_part("partials/layouts/hero.php"); ?>
<div class="content-block -fullbleed">
    <div class="content_inner">
        <div class="content_row row -padded">
            <div class="col-12 col-xs-8">
                <div class="content_post">
                    <?php do_action("__gulp_init__namespace_before_content"); ?>

                    <?php
                    if (have_posts()) {
                        while (have_posts()) { the_post();
                            __gulp_init__namespace_get_template_part("partials/articles/post-full.php", array("post" => $post, "class" => "content_article"));
                        }
                    }
                    ?>

                    <?php do_action("__gulp_init__namespace_after_content"); ?>
                </div><!--/.content_post-->
            </div><!--/.col-12.col-xs-8-->
            <?php get_sidebar(); ?>
        </div><!--/.content_row.row.-padded-->
    </div><!--/.content_inner-->
</div><!--/.content-block.-fullbleed-->
<?php get_footer(); ?>
