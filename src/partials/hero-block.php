<?php
$queried_object = get_queried_object();
$slideshow = get_field("slideshow");
$featured_image = is_singular() ? get_the_post_thumbnail($post->ID, "hero", array("class" => "hero_image")) : (is_archive() ? get_field("featured_image", $queried_object) : false);
$title = is_singular() ? get_the_title() : (is_archive() ? get_the_archive_title() : (is_404() ? __("404: Page Not Found", "new_site") : false));

if (is_home() && !$title) {
    $posts_page = get_post(get_option("page_for_posts"));
    $title = get_the_title($posts_page->ID) ? get_the_title($posts_page->ID) : __("Latest Posts", "new_site");
}

if ($slideshow || $featured_image || $title) {
    echo "<div class='hero-block -fullbleed' role='banner'><div class='hero_inner'>";

    if ($slideshow) {
        echo "<div class='hero_swiper-container swiper-container'>";
        echo "<div class='swiper-wrapper'>";

        $i = 0;

        while (have_rows("slideshow")) {
            the_row();

            $image = get_sub_field("image");

            if ($image) {
                $i++;

                $img_alt = $image["alt"] ? " alt='{$image["alt"]}'" : "";
                $img_src_mobile = $image["sizes"]["hero"] ? $image["sizes"]["hero"]: "";
                $img_src_tablet = $image["sizes"]["hero"] ? $image["sizes"]["hero_medium"] : "";
                $img_src_desktop = $image["sizes"]["hero"] ? $image["sizes"]["hero_large"] : "";
                $img_title = $image["title"];
                $img_caption = $image["caption"];

                echo "<figure class='hero_figure swiper-slide'>";

                if ($img_src_mobile) {
                    echo "<picture class='hero_picutre swiper-picture'>";

                    if ($img_src_desktop) {
                        echo "<source srcset='{$img_src_desktop}' media='(min-width: 64em)' />";
                    }

                    if ($img_src_tablet) {
                        echo "<source srcset='{$img_src_tablet}' media='(min-width: 40em)' />";
                    }

                    echo "<img class='hero_image swiper-image'{$img_alt} src='{$img_src_mobile}' />";

                    echo "</picture>"; // .hero_picture
                }

                if ($img_title || $img_caption) {
                    echo "<figcaption class='hero_header swiper-caption'>";

                    if ($img_title) echo "<h6 class='hero_title swiper-title title'>{$img_title}</h6>";
                    if ($img_caption) echo "<div class='hero_user-content swiper-user-content user-content -dark'>" . wpautop($img_caption) . "</div>";

                    echo "</figcaption>"; // .hero_header.swiper-caption
                } // if ($img_title || $img_caption)

                // close swiper-slide
                echo "</figure>";
            } // if ($image)
        } // while (have_rows("slideshow"))

        echo "</div>"; // .swiper-wrapper

        if ($i > 1) {
            echo "<div class='swiper-pagination'></div>";
            echo "<button class='swiper-button-prev'><icon:caret-left><span class='_visuallyhidden'>" . __("Previous Slide", "new_site") . "</span></button>";
            echo "<button class='swiper-button-next'><icon:caret-right><span class='_visuallyhidden'>" . __("Next Slide", "new_site") . "</span></button>";
        }

        echo "</div>"; // .hero_swiper-container.swiper-container
    } // if ($slideshow)
    elseif ($featured_image) {
        $img_src_tablet = wp_get_attachment_image_src(get_post_thumbnail_id(), "hero_medium");

        $img_src_desktop = wp_get_attachment_image_src(get_post_thumbnail_id(), "hero_large");

        echo "<figure class='hero_figure'>";

        echo "<picture class='hero_picture'>";
        
        if ($img_src_desktop) {
            echo "<source srcset='{$img_src_desktop[0]}' media='(min-width: 64em)' />";
        }

        if ($img_src_tablet) {
            echo "<source srcset='{$img_src_tablet[0]}' media='(min-width: 40em) and (max-width: 63.9375em)' />";
        }

        echo $featured_image;

        echo "</picture>"; // .hero_picture

        if ($title) {
            echo "<header class='hero_header'><h1 class='hero_title title' role='heading'>{$title}</h1></header>";
        }

        echo "</figure>"; // .hero_figure
    } // if ($slideshow) elseif ($featured_image)
    elseif ($title) {
        echo "<header class='hero_header'><h1 class='hero_title title' role='heading'>{$title}</h1></header>";
    } // if ($slideshow) elseif ($featured_image) elseif ($title)

    echo "</div></div>"; // .hero_inner, .hero-block
} // if ($slideshow || $featured_image || $title)
?>
