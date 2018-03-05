<?php get_header(); ?>
<?php include(locate_template("partials/block-hero.php")); ?>
<div class="content-block -fullbleed">
    <div class="content_inner">
        <div class="content_row row -padded">
            <div class="col-12 col-xs-8">
                <div class="content_post">
                    <?php do_action("__gulp_init__namespace_before_content"); ?>

                    <?php if (have_posts()): ?>
                        <?php while (have_posts()): the_post(); ?>
                            <?php $post_variant = "content_article"; ?>
                            <?php include(locate_template("partials/content-full.php")); ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <?php $post_variant = "content_article"; ?>
                        <?php include(locate_template("partials/content-none.php")); ?>
                    <?php endif; ?>

                    <?php do_action("__gulp_init__namespace_after_content"); ?>
                </div><!--/.content_post-->
            </div><!--/.col-12.col-xs-8-->
            <?php get_sidebar(); ?>
        </div><!--/.content_row.row.-padded-->
    </div><!--/.content_inner-->
</div><!--/.content-block.-fullbleed-->
<?php get_footer(); ?>
