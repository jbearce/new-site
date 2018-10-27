<?php get_header(); ?>
<?php
__gulp_init_namespace___get_template_part("partials/layouts/hero.php", array(
    "post" => $post,
    "class" => array(
        "block" => "hero-block--fullbleed __nopadding",
        "inner" => "hero__inner--fullbleed"
    )
));
?>
<div class="content-block content-block--fullbleed">
    <div class="content__inner">
        <div class="content_row row row--padded">
            <div class="col-12 col-xs-8">
                <div class="content__post">
                    <?php do_action("__gulp_init_namespace___before_content"); ?>

                    <?php
                    if (have_posts()) {
                        while (have_posts()) { the_post();
                            __gulp_init_namespace___get_template_part("partials/articles/post-full.php", array(
                                "post" => $post,
                                "class" => "content__article",
                            ));
                        }
                    }
                    ?>

                    <?php do_action("__gulp_init_namespace___after_content"); ?>
                </div><!--/.content__post-->
            </div><!--/.col-12.col-xs-8-->
            <?php get_sidebar(); ?>
        </div><!--/.content_row.row-->
    </div><!--/.content__inner-->
</div><!--/.content-block-->
<?php get_footer(); ?>
