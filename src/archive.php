<?php get_header(); ?>
<?php __gulp_init__namespace_get_template_part("partials/layouts/hero.php"); ?>
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

            <?php
            if (have_posts()) {
                while (have_posts()) { the_post();
                    __gulp_init__namespace_get_template_part("partials/articles/post-excerpt.php", array("post" => $post, "article_class" => "content_article"));
                }
            } else {
                __gulp_init__namespace_get_template_part("partials/articles/post-none.php", array("article_class" => "content_article", "article_error" => get_no_posts_message(get_queried_object())));
            }
            ?>

            <?php __gulp_init__namespace_get_template_part("partials/modules/menu-list-pagination.php"); ?>

            <?php do_action("__gulp_init__namespace_after_content"); ?>
        </div><!--/.content_post-->
    </div><!--/.content_inner-->
</div><!--/.content-block.-fullbleed-->
<?php get_footer(); ?>
