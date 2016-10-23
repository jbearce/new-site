<?php get_header(); ?>
<div class="content-block">
    <div class="content_inner">
        <?php get_search_form(); ?>
        <?php get_template_part("partials/content", "excerpt"); ?>
        <?php get_template_part("partials/pagination", "list"); ?>
    </div><!--/.content_inner-->
</div><!--/.content-block-->
<?php get_footer(); ?>
