<!--removeIf(tribe_html)--><?php get_header(); ?>
<?php include(locate_template("partials/block-hero.php")); ?>
<div class="content-block -fullbleed">
    <div class="content_inner">
        <div class="content_post">
            <?php do_action("new_site_before_content"); ?>

            <?php
            $GLOBALS["tribe_hooked_template"] = true;

            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    the_content();
                }
            }

            $GLOBALS["tribe_hooked_template"] = false;
            ?>

            <?php do_action("new_site_after_content"); ?>
        </div><!--/.content_post-->
    </div><!--/.content_inner-->
</div><!--/.content-block.-fullbleed-->
<?php get_footer(); ?><!--endRemoveIf(tribe_html)-->
