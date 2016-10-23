<?php get_header(); ?>
<?php get_template_part("partials/hero", "block"); ?>
<div class="content-block">
    <div class="content_inner">
        <?php
        $term = get_queried_object();

        if (get_the_archive_title() || $term->description) {
            echo "<article class='content_article article -introduction'>";

            if (get_the_archive_title()) {
                echo "<header class='article_header'><h1 class='article_title title'>" . get_the_archive_title() . "</h1></header>";
            }

            if ($term->description) {
                echo "<div class='article_content'><div class='article_user-content user-content'>" . wpautop($term->description) . "</div></div>";
            }

            // close content_article article.-introduction
            echo "</article>";
        }
        ?>
        <?php get_template_part("partials/content", "excerpt"); ?>
        <?php get_template_part("partials/pagination", "list"); ?>
    </div><!--/.content_inner-->
</div><!--/.content-block-->
<?php get_footer(); ?>
