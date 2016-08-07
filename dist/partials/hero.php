<?php
$queried_object = get_queried_object();
$slideshow = get_field("slideshow");
$featured_image = is_singular() ? get_the_post_thumbnail($post->ID, "hero", array("class" => "hero_image")) : (is_archive() ? get_field("featured_image", $queried_object) : false);
$title = is_singular() ? get_the_title() : (is_archive() ? get_the_archive_title() : (is_404() ? __("404: Page Not Found", "new_site") : false));

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

                    if ($img_src_tablet) {
                        echo "<source srcset='{$img_src_tablet}' media='(min-width: 40em)' />";
                    }

                    if ($img_src_desktop) {
                        echo "<source srcset='{$img_src_desktop}' media='(min-width: 64em)' />";
                    }

                    echo "<img class='hero_image swiper-image'{$img_alt} src='{$img_src_mobile}' />";

                    // close hero_picture
                    echo "</picture>";
                }

                if ($img_title || $img_caption) {
                    echo "<figcaption class='hero_header swiper-caption'>";

                    if ($img_title) echo "<h6 class='hero_title swiper-title title'>{$img_title}</h6>";
                    if ($img_caption) echo "<div class='swiper-user-content user-content'>" . wpautop($img_caption) . "</div>";

                    // close hero_header swiper-caption
                    echo "</figcaption>";
                }

                // close swiper-slide
                echo "</figure>";
            }
        }

        // close swiper-wrapper
        echo "</div>";

        if ($i > 1) {
            echo "<div class='swiper-pagination'></div>";
            echo "<div class='swiper-button-prev'><i class='fa fa-caret-left'></i><span class='_visuallyhidden'>" . __("Previous Slide", "new_site") . "</span></div>";
            echo "<div class='swiper-button-next'><i class='fa fa-caret-right'></i><span class='_visuallyhidden'>" . __("Next Slide", "new_site") . "</span></div>";
        }

        // close hero_swiper-container swiper-container
        echo "</div>";
    } elseif ($featured_image) {
        $img_src_tablet = wp_get_attachment_image_src(get_post_thumbnail_id(), "hero_medium");

        $img_src_desktop = wp_get_attachment_image_src(get_post_thumbnail_id(), "hero_large");

        echo "<figure class='hero_figure'>";

        echo "<picture class='hero_picture'>";

        if ($img_src_tablet) {
            echo "<source srcset='{$img_src_tablet[0]}' media='(min-width: 40em) and (max-width: 63.9375em)' />";
        }

        if ($img_src_desktop) {
            echo "<source srcset='{$img_src_desktop[0]}' media='(min-width: 64em)' />";
        }

        echo $featured_image;

        // close hero_picture
        echo "</picture>";

        if ($title) {
            echo "<header class='hero_header'><h1 class='hero_title title' role='heading'>{$title}</h1></header>";
        }

        // close hero_figure
        echo "</figure>";
    } elseif ($title) {
        echo "<header class='hero_header'><h1 class='hero_title title' role='heading'>{$title}</h1></header>";
    }

    // close hero_inner, hero-block
    echo "</div></div>";
}
?>
