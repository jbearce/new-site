<?php get_header(); ?>
<?php include(locate_template("partials/block-hero.php")); ?>
<div class="content-block -fullbleed">
    <div class="content_inner">
        <div class="content_post">
            <?php do_action("__gulp_init__namespace_before_content"); ?>

            <?php
            $queried_object  = get_queried_object();
            $archive_title   = is_post_type_archive() && isset($queried_object->label) && $queried_object->label ? $queried_object->label : (single_term_title("", false) ? single_term_title("", false) : get_the_archive_title());
            $archive_content = isset($queried_object->description) && $queried_object->description ? $queried_object->description : get_the_archive_description();
            ?>

            <?php if ($archive_title): ?>
                <header class="content_header">
                    <h1 class="content_title title">
                        <?php echo apply_filters("the_title", $archive_title); ?>
                    </h1><!--/.content_title.title-->
                </header><!--/.content_header-->
            <?php endif; // if (get_the_archive_title()) ?>

            <?php if ($archive_content): ?>
                <div class="content_content">
                    <div class="content_user-content user-content">
                        <?php echo apply_filters("the_content", $archive_content); ?>
                    </div><!--/.content_user-content.user-content-->
                </dv><!--/.content_content-->
            <?php endif; ?>

            <?php if (have_posts()): ?>
                <?php while (have_posts()): the_post(); ?>
                    <?php $post_variant = "content_article"; ?>
                    <?php include(locate_template("partials/content-excerpt.php")); ?>
                <?php endwhile; ?>
            <?php else: ?>
                <?php $post_variant = "content_article"; ?>
                <?php include(locate_template("partials/content-none.php")); ?>
            <?php endif; ?>

            <?php include(locate_template("partials/list-pagination.php")); ?>

            <?php do_action("__gulp_init__namespace_after_content"); ?>
        </div><!--/.content_post-->
    </div><!--/.content_inner-->
</div><!--/.content-block.-fullbleed-->
<?php get_footer(); ?>
