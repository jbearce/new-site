<?php
// force redirect 'cause tribe is stupid
if (is_post_type_archive("tribe_events")) {
    include(TEMPLATEPATH . "/archive-tribe_events.php");
    return;
} elseif (get_post_type() == "tribe_events") {
    include(TEMPLATEPATH . "/single-tribe_events.php");
    return;
}
?>
<?php get_header(); ?>
<?php get_template_part("partials/hero", "block"); ?>
<div class="content-block">
    <div class="content_inner">
        <div class="content_post">
            <?php get_template_part("partials/content", "full"); ?>
        </div><!--/.content_post-->
    </div><!--/.content_inner-->
</div><!--/.content-block-->
<?php get_footer(); ?>
