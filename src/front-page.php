<?php get_header(); ?>
<?php
get_extended_template_part("layout", "hero", array(
    "post"  => $post,
    "class" => array(
        "block" => "hero-block--fullbleed __nopadding",
        "inner" => "hero__inner--fullbleed"
    )
));
?>
<div class="content-block content-block--fullbleed">
    <div class="content__inner">
        <div class="content__post">
            <?php do_action("__gulp_init_namespace___before_content"); ?>

            <?php
            if (have_posts()) {
                while (have_posts()) { the_post();
                    get_extended_template_part("article", "post-full", array(
                        "post"  => $post,
                        "class" => "content__article",
                        "title" => "",
                        "meta"  => false,
                    ));
                }
            }
            ?>

            <?php do_action("__gulp_init_namespace___after_content"); ?>
        </div><!--/.content__post-->
    </div><!--/.content__inner-->
</div><!--/.content-block-->
<?php get_footer(); ?>
