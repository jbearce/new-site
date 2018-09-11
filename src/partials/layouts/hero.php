<?php
$post           = isset($template_args["post"]) ? $template_args["post"] : false;
$class          = isset($template_args["class"]) ? " {$template_args["class"]}" : "";
$slideshow      = isset($template_args["slideshow"]) ? $template_args["slideshow"] : ($post ? get_field("slideshow", $post->ID) : false);
$image_size     = isset($template_args["image_size"]) ? $template_args["image_size"] : "hero";
$featured_image = isset($template_args["featured_image"]) ? $template_args["featured_image"] : ($post && has_post_thumbnail($post->ID) ? array("alt" => get_post_meta(get_post_thumbnail_id($post->ID), "_wp_attachment_image_alt", true), "small" => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "{$image_size}")[0], "medium" => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "{$image_size}_medium")[0], "large" => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "{$image_size}_large")[0]) : false);
$title          = isset($template_args["title"]) ? $template_args["title"] : false;
?>
<?php if ($slideshow || $featured_image): $i = 0; ?>
    <div class="hero-block -fullbleed<?php echo $class; ?>" role="region">
        <div class="hero_inner -fullbleed">
            <div class="hero_swiper-container swiper-container -hero -fullbleed">

                <div class="swiper-wrapper">
                    <?php if ($slideshow): ?>
                        <?php while (have_rows("slideshow")): the_row(); ?>
                            <?php
                            $image = get_sub_field("image");
                            $link  = get_sub_field("link");
                            ?>

                            <?php if ($image): $i++; ?>
                                <figure class="swiper-slide">

                                    <?php if ($link["url"]): ?>
                                        <a class="swiper-link link" href="<?php echo $link["url"]; ?>"<?php if ($link["title"]): ?> title="<?php echo $link["title"]; ?>"<?php endif; ?><?php if ($link["target"]): ?> target="<?php echo $link["target"]; ?>"<?php endif; ?>>
                                    <?php endif; ?>

                                    <?php if ($image["sizes"]["{$image_size}"]): ?>
                                        <picture class="swiper-picture">
                                            <?php if ($image["sizes"]["{$image_size}_large"]): ?>
                                                <source srcset="<?php echo $image["sizes"]["{$image_size}_large"]; ?>" media="(min-width: 64em)" />
                                            <?php endif; ?>

                                            <?php if ($image["sizes"]["{$image_size}_medium"]): ?>
                                                <source srcset="<?php echo $image["sizes"]["{$image_size}_medium"]; ?>" media="(min-width: 40em)" />
                                            <?php endif; ?>

                                            <?php if ($image["sizes"]["{$image_size}"]): ?>
                                                <img class="swiper-image" src="<?php echo $image["sizes"]["{$image_size}"]; ?>" alt="<?php echo htmlspecialchars($image["alt"]); ?>" />
                                            <?php endif; ?>
                                        </picture><!--/.swiper-picture-->
                                    <?php endif; ?>

                                    <?php if ($image["title"] || $image["caption"]): ?>
                                        <figcaption class="swiper-caption">
                                            <div class="swiper-caption-inner">
                                                <?php if ($image["title"]): ?>
                                                    <h6 class="swiper-title title<?php echo !$image["caption"] ? " _nomargin" : ""; ?>">
                                                        <?php echo $image["title"]; ?>
                                                    </h6><!--/.swiper-title.title-->
                                                <?php endif; ?>

                                                <?php if ($image["caption"]): ?>
                                                    <div class="swiper-user-content user-content -light">
                                                        <?php echo wpautop($image["caption"]); ?>
                                                    </div><!--/.swiper-user-content.user-content.-light-->
                                                <?php endif; ?>
                                            </div><!--/.swiper-caption-inner-->
                                        </figcaption><!--/.swiper-caption-->
                                    <?php endif; ?>

                                    <?php if ($link["url"]): ?>
                                        </a><!--/.swiper-link.link-->
                                    <?php endif; ?>

                                </figure><!--/.swiper-slide-->
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php elseif ($featured_image): // ($slideshow) ?>
                        <figure class="swiper-slide">

                            <picture class="swiper-picture">
                                <?php if ($featured_image["large"]): ?>
                                    <source srcset="<?php echo $featured_image["large"]; ?>" media="(min-width: 64em)" />
                                <?php endif; ?>

                                <?php if ($featured_image["medium"]): ?>
                                    <source srcset="<?php echo $featured_image["medium"]; ?>" media="(min-width: 40em)" />
                                <?php endif; ?>

                                <?php if ($featured_image["small"]): ?>
                                    <img class="swiper-image" src="<?php echo $featured_image["small"]; ?>" alt="<?php echo htmlspecialchars($featured_image["alt"]); ?>" />
                                <?php endif; ?>
                            </picture><!--/.swiper-picture-->

                            <?php if ($title): ?>
                                <header class="swiper-caption">
                                    <div class="swiper-caption-inner">
                                        <h1 class="swiper-title title _nomargin" role="heading">
                                            <?php echo $title; ?>
                                        </h1><!--/.swiper-title.title._nomargin-->
                                    </div><!--/.swiper-caption-inner-->
                                </header>
                            <?php endif; ?>

                        </figure><!--/.swiper-slide-->
                    <?php endif; ?>
                </div> <!--/.swiper-wrapper-->

                <?php if ($slideshow && $i > 1): ?>
                    <div class="swiper-pagination"></div>

                    <button class="swiper-button -prev">
                        <i class="fas fa-caret-left swiper-button-icon"></i>
                        <span class="_visuallyhidden"><?php _e("Previous Slide", "__gulp_init__namespace"); ?></span>
                    </button><!--/.swiper-button.-prev-->

                    <button class="swiper-button -next">
                        <i class="fas fa-caret-right swiper-button-icon"></i>
                        <span class="_visuallyhidden"><?php _e("Next Slide", "__gulp_init__namespace"); ?></span>
                    </button><!--/.swiper-button.-next-->
                <?php endif; ?>

            </div><!--/.hero_swiper-container.swiper-container.-hero.-fullbleed-->
        </div><!--/.hero_inner.-fullbleed-->
    </div><!--/.hero-block.-fullbleed._nopadding-->
<?php endif; ?>
