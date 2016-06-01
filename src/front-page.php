<?php
// redirect to the home templmate if no front page is set
if (get_option("show_on_front") != "page") {
    include(TEMPLATEPATH . "/home.php");
    return;
}
?>
<?php get_header(); ?>
            <?php
            if (have_rows("slideshow")) {
                // open the slideshow and swiper wrappers
                echo "<div class='slideshow-container'><div class='slideshow-block'><div class='swiper-container'><div class='swiper-wrapper'>";

                // display the slides
                while (have_rows("slideshow")) {
                    the_row();
                    $img = get_sub_field("image");
                    if ($img) {
                        $img_alt = $img["alt"] == "" ? "" : " alt='{$img["alt"]}'";
                        $img_src = $img["sizes"]["slideshow"];
                        echo "<figure class='swiper-slide'><img{$img_alt} src='{$img_src}@@if (context.version) {?v=@@version}' /></figure>";
                    }
                }

                // close the slideshow and swiper wrappers
                echo "</div></div></div></div>";
            }
            ?>
            <div class="content-container">
                <main class="content-block">
                    <div class="post">
                        <article class="article">
                            <?php
                            // check if posts exist
                            if (have_posts()) {
                                // loop through each post
                                while (have_posts()) {
                                    // iterate the post index
                                    the_post();

                                    // display the title
                                    $tagline = get_bloginfo("description") ? get_bloginfo("description") : $post->post_title;
                                    echo "<header class='article_header header'><h1 class='article_title title'>{$tagline}</h1></header>";

                                    // display the featured image
                                    if (has_post_thumbnail()) {
                                        echo "<figure class='article_figure figure'>" . get_the_post_thumbnail($post->ID, "large", array("class" => "article_image image")) . "</figure>";
                                    }

                                    // display the content
                                    echo "<div class='article_content content user-content'>";
                                    the_content();
                                    echo "</div>";

                                    // display the comments
                                    if (comments_open() || get_comments_number() > 0) {
                                        comments_template();
                                    }
                                }
                            }
                            ?>
                        </aricle><!--/.article-->
                    </div><!--/.post-->
                    <?php get_sidebar(); ?>
                </main><!--/.content-block-->
            </div><!--/.content-container-->
<?php get_footer(); ?>
