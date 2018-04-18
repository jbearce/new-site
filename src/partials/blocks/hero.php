<?php
$post                 = isset($template_args["post"]) ? $template_args["post"] : false;
$block_class          = isset($template_args["block_class"]) ? $template_args["block_class"] : "";
$block_slideshow      = isset($template_args["block_slideshow"]) ? $template_args["block_slideshow"] : ($post ? get_field("slideshow", $post->ID) : false);
$block_image_size     = isset($template_args["block_image_size"]) ? $template_args["block_image_size"] : "hero";
$block_featured_image = isset($template_args["block_featured_image"]) ? $template_args["block_featured_image"] : ($post && has_post_thumbnail($post->ID) ? array("alt" => get_post_meta(get_post_thumbnail_id($post->ID), "_wp_attachment_image_alt", true), "small" => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "{$block_image_size}")[0], "medium" => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "{$block_image_size}_medium")[0], "large" => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "{$block_image_size}_large")[0]) : false);
$block_title          = isset($template_args["block_title"]) ? $template_args["block_title"] : false;
?>
<?php if ($block_slideshow || $block_featured_image): $i = 0; ?>
    <div class="hero-block -fullbleed <?php echo $block_class; ?>" role="region">
        <div class="hero_inner -fullbleed">
            <div class="hero_swiper-container swiper-container -hero -fullbleed">

                <div class="swiper-wrapper">
                    <?php if ($block_slideshow): ?>
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

                                    <?php if ($image["sizes"]["hero"]): ?>
                                        <picture class="swiper-picture">
                                            <?php if ($image["sizes"]["hero_large"]): ?>
                                                <source srcset="<?php echo $image["sizes"]["{$block_image_size}_large"]; ?>" media="(min-width: 64em)" />
                                            <?php endif; ?>

                                            <?php if ($image["sizes"]["hero_medium"]): ?>
                                                <source srcset="<?php echo $image["sizes"]["{$block_image_size}_medium"]; ?>" media="(min-width: 40em)" />
                                            <?php endif; ?>

                                            <img class="swiper-image" src="<?php echo $image["sizes"]["{$block_image_size}"]; ?>" alt="<?php echo htmlspecialchars($image["alt"]); ?>" />
                                        </picture><!--/.swiper-picture-->
                                    <?php endif; // if ($image["sizes"]["hero"]) ?>

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
                                    <?php endif; // if ($image["title"] || $image["caption"]) ?>

                                    <?php if ($link["url"]): ?>
                                        </a><!--/.swiper-link.link-->
                                    <?php endif; ?>

                                </figure><!--/.swiper-slide-->
                            <?php endif; // if ($image) ?>
                        <?php endwhile; // while (have_rows("slideshow")) ?>
                    <?php elseif ($block_featured_image): // ($block_slideshow) ?>
                        <figure class="swiper-slide">

                            <picture class="swiper-picture">
                                <?php if ($block_featured_image["large"]): ?>
                                    <source srcset="<?php echo $block_featured_image["large"]; ?>" media="(min-width: 64em)" />
                                <?php endif; ?>

                                <?php if ($block_featured_image["medium"]): ?>
                                    <source srcset="<?php echo $block_featured_image["medium"]; ?>" media="(min-width: 40em)" />
                                <?php endif; ?>

                                <?php if ($block_featured_image["small"]): ?>
                                    <img class="swiper-image" src="<?php echo $block_featured_image["small"]; ?>" alt="<?php echo htmlspecialchars($block_featured_image["alt"]); ?>" />
                                <?php endif; ?>
                            </picture><!--/.swiper-picture-->

                            <?php if ($block_title): ?>
                                <header class="swiper-caption">
                                    <div class="swiper-caption-inner">
                                        <h1 class="swiper-title title _nomargin" role="heading">
                                            <?php echo $block_title; ?>
                                        </h1><!--/.swiper-title.title._nomargin-->
                                    </div><!--/.swiper-caption-inner-->
                                </header>
                            <?php endif; // if ($block_title) ?>

                        </figure><!--/.swiper-slide-->
                    <?php endif; // ($block_featured_image) ?>
                </div> <!--/.swiper-wrapper-->

                <?php if ($block_slideshow && $i > 1): ?>
                    <div class="swiper-pagination"></div>

                    <button class="swiper-button -prev">
                        <icon use="caret-left" class="swiper-button-icon" />
                        <span class="_visuallyhidden"><?php _e("Previous Slide", "__gulp_init__namespace"); ?></span>
                    </button><!--/.swiper-button.-prev-->

                    <button class="swiper-button -next">
                        <icon use="caret-right" class="swiper-button-icon" />
                        <span class="_visuallyhidden"><?php _e("Next Slide", "__gulp_init__namespace"); ?></span>
                    </button><!--/.swiper-button.-next-->
                <?php endif; ?>

            </div><!--/.hero_swiper-container.swiper-container.-hero.-fullbleed-->
        </div><!--/.hero_inner.-fullbleed-->
    </div><!--/.hero-block.-fullbleed._nopadding-->
<?php endif; // if ($block_slideshow || $block_featured_image) ?>
