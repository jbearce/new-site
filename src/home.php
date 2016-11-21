<?php get_header(); ?>
<?php get_template_part("partials/hero", "block"); ?>
<div class="content-block">
    <div class="content_inner">
        <div class="content_post">
            <?php
            $posts_page = get_post(get_option("page_for_posts"));
            $title = get_the_title($posts_page->ID) ? get_the_title($posts_page->ID) : __("Latest Posts", "new_site");

            if ($title) {
                echo "<article class='content_article article -introduction'><header class='article_header'><h1 class='article_title title'>{$title}</h1></header></article>";
            }
            ?>
            <?php get_template_part("partials/content", "excerpt"); ?>
            <?php get_template_part("partials/pagination", "list"); ?>
        </div><!--/.content_post-->
    </div><!--/.content_inner-->
</div><!--/.content-block-->
<?php get_footer(); ?>
