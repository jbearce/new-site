<?php
// WIP ?>
<?php
// redirect to the home templmate if no front page is set
if (get_option("show_on_front") != "page") {
    include(TEMPLATEPATH . "/home.php");
    return;
}
?>
<?php get_header(); ?>
<?php get_template_part("partials/hero", "hero"); ?>
<?php get_footer(); ?>
