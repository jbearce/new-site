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
                echo "<div class='slideshow-wrapper'><div class='slideshow-block'><div class='swiper-container'><div class='swiper-wrapper'>";

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
            <div class="content-wrapper">
                <main class="content-block">
                    <div class="post">
                        <article class="article-card">
                            <?php
                            // check if posts exist
                            if (have_posts()) {
                                // loop through each post
                                while (have_posts()) {
                                    // iterate the post index
                                    the_post();

                                    // display the title
                                    $tagline = get_bloginfo("description") ? get_bloginfo("description") : $post->post_title;
                                    echo "<header class='header'><h1 class='title'>{$tagline}</h1></header>";

                                    // display the featured image
                                    if (has_post_thumbnail()) {
                                        echo "<figure class='image'>" . get_the_post_thumbnail($post->ID, "large") . "</figure>";
                                    }

                                    // display the content
                                    echo "<div class='content'><div class='user-content'>";
                                    the_content();
                                    echo "</div></div>";

                                    // display the comemnts
                                    if (comments_open() || get_comments_number() > 0) {
                                        comments_template();
                                    }
                                }
                            }
                            ?>
                        </aricle><!--/.article-card-->
                    </div><!--/.post-->
                    <?php get_sidebar(); ?>
                </main><!--/.content-block-->
            </div><!--/.content-wrapper-->
<?php get_footer(); ?>
