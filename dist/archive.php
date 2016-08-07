<?php get_header(); ?>
<?php get_template_part("partials/hero", "hero"); ?>
<div class="content-block">
    <div class="content_inner">
        <?php
        $term = get_queried_object();
        if ($term->description) {
            echo "<article class='content_article article -introduction'><div class='article_content'><div class='article_user-content user-content'>" . wpautop($term->description) . "</div></div></article>";
        }
        ?>
        <?php get_template_part("partials/content", "excerpt"); ?>
    </div><!--/.content_inner-->
</div><!--/.content-block-->
<?php get_footer(); ?>
