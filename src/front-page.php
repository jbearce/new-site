<?
if (get_option("show_on_front") != "page") {
    include(TEMPLATEPATH . "/home.php");
    return;
}
?>
<? get_header(); ?>
            <?
            if (have_rows("slideshow")) {
                echo "<div class='slideshow-wrapper'><div class='slideshow'><div class='swiper-container'><div class='swiper-wrapper'>";
                while (have_rows("slideshow")) {
                    the_row();
                    $img = get_sub_field("image");
                    if ($img) {
                        $img_alt = $img["alt"] == "" ? "" : " alt='{$image["alt"]}'";
                        $img_src = $img["sizes"]["slideshow"];
                        echo "<figure class='swiper-slide'><img{$img_alt} src='{$img_src}@@if (context.version) {?v=@@version}' /></figure>";
                    }
                }
                echo "</div></div></div></div>";
            }
            ?>
            <div class="content-wrapper">
                <main class="content">
                    <div class="post">
                        <?
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();
                                echo "<article>";
                                if (has_post_thumbnail($id)) {
                                    echo "<figure>" . get_the_post_thumbnail($id, "large") . "</figure>";
                                }
                                echo "<header><h1>". get_bloginfo("description") . "</h1></header>";
                                the_content();
                                if (comments_open() || get_comments_number() > 0) {
                                    echo "<footer>";
                                    comments_template();
                                    echo "</footer>";
                                }
                                echo "</article>";
                            }
                        }
                        ?>
                    </div><!--/.post-->
                    <? get_sidebar(); ?>
                </main><!--/.content-->
            </div><!--/.content-wrapper-->
<? get_footer(); ?>
