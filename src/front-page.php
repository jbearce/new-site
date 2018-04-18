<?php get_header(); ?>
<?php __gulp_init__namespace_get_template_part("partials/blocks/hero.php"); ?>
<div class="content-block -fullbleed">
    <div class="content_inner">
        <div class="content_post">
            <?php do_action("__gulp_init__namespace_before_content"); ?>

            <?php
            if (have_posts()) {
                while (have_posts()) { the_post();
                    __gulp_init__namespace_get_template_part("partials/content/post-full.php", array("post" => $post, "article_class" => "content_article", "article_title" => ""));
                }
            }
            ?>

            <?php do_action("__gulp_init__namespace_after_content"); ?>
        </div><!--/.content_post-->
    </div><!--/.content_inner-->
</div><!--/.content-block.-fullbleed-->
<?php get_footer(); ?>
