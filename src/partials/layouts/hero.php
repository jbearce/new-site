<?php
$post           = isset($template_args["post"]) ? $template_args["post"] : false;
$class          = isset($template_args["class"]) ? $template_args["class"] : "";
$block_class    = gettype($class) === "array" && key_exists("block", $class) ? " {$class["block"]}" : (gettype($class) === "string" ? " {$class}" : "");
$inner_class    = gettype($class) === "array" && key_exists("inner", $class) ? " {$class["inner"]}" : "";
$swiper_class   = gettype($class) === "array" && key_exists("swiper", $class) ? " {$class["swiper"]}" : "";
$navigation     = isset($template_args["navigation"]) ? $template_args["navigation"] : false;
$pagination     = isset($template_args["pagination"]) ? $template_args["pagination"] : false;
$slideshow      = isset($template_args["slideshow"]) ? $template_args["slideshow"] : ($post ? get_field("slideshow", $post->ID) : false);
$image_size     = isset($template_args["image_size"]) ? $template_args["image_size"] : "hero";
$featured_image = isset($template_args["featured_image"]) ? $template_args["featured_image"] : ($post && has_post_thumbnail($post->ID) ? array("alt" => get_post_meta(get_post_thumbnail_id($post->ID), "_wp_attachment_image_alt", true), "small" => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "{$image_size}")[0], "medium" => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "{$image_size}_medium")[0], "large" => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "{$image_size}_large")[0]) : false);
$title          = isset($template_args["title"]) ? $template_args["title"] : false;
?>
<?php if ($slideshow || $featured_image): $i = 0; ?>
    <div class="hero-block<?php echo $block_class; ?>" role="region">
        <div class="hero__inner<?php echo $inner_class; ?>">
            <div class="hero__swiper-container swiper-container --hero<?php echo $swiper_class; ?>" data-slideout-ignore="true">

                <div class="swiper-wrapper">
                    <?php if ($slideshow): ?>
                        <?php foreach ($slideshow as $slide): ?>
                            <?php
                            $image = isset($slide["image"]) ? $slide["image"] : false;
                            $link  = isset($slide["link"]) ? $slide["link"] : false;
                            ?>

                            <?php if ($image): $i++; ?>
                                <figure class="swiper-slide">

                                    <?php if ($link && $link["url"]): ?>
                                        <a class="swiper-link link" href="<?php echo $link["url"]; ?>"<?php if ($link["title"]): ?> title="<?php echo $link["title"]; ?>"<?php endif; ?><?php if ($link["target"]): ?> target="<?php echo $link["target"]; ?>"<?php endif; ?>>
                                    <?php endif; ?>

                                    <?php if ($image["sizes"]["{$image_size}"]): ?>
                                        <picture class="swiper-picture">
                                            <?php if ($image["sizes"]["{$image_size}_large"]): ?>
                                                <?php echo __gulp_init_namespace___img($image["sizes"]["{$image_size}_large"], array("media" => "(min-width: 64em)"), false, "source"); ?>
                                            <?php endif; ?>

                                            <?php if ($image["sizes"]["{$image_size}_medium"]): ?>
                                                <?php echo __gulp_init_namespace___img($image["sizes"]["{$image_size}_medium"], array("media" => "(min-width: 40em)"), false, "source"); ?>
                                            <?php endif; ?>

                                            <?php if ($image["sizes"]["{$image_size}"]): ?>
                                                <?php echo __gulp_init_namespace___img($image["sizes"]["{$image_size}"], array("alt" => $image["alt"], "class" => "swiper-image")); ?>
                                            <?php endif; ?>
                                        </picture><!--/.swiper-picture-->
                                    <?php endif; ?>

                                    <?php if ($image["title"] || $image["caption"]): ?>
                                        <figcaption class="swiper-caption">
                                            <div class="swiper-caption-inner">
                                                <?php if ($image["title"]): ?>
                                                    <h6 class="swiper-title title<?php echo !$image["caption"] ? " __nomargin" : ""; ?>">
                                                        <?php echo $image["title"]; ?>
                                                    </h6><!--/.swiper-title.title-->
                                                <?php endif; ?>

                                                <?php if ($image["caption"]): ?>
                                                    <div class="swiper-user-content user-content --light">
                                                        <?php echo apply_filters("the_content", $image["caption"]); ?>
                                                    </div><!--/.swiper-user-content.user-content.--light-->
                                                <?php endif; ?>
                                            </div><!--/.swiper-caption-inner-->
                                        </figcaption><!--/.swiper-caption-->
                                    <?php endif; ?>

                                    <?php if ($link && $link["url"]): ?>
                                        </a><!--/.swiper-link.link-->
                                    <?php endif; ?>

                                </figure><!--/.swiper-slide-->
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php elseif ($featured_image): // ($slideshow) ?>
                        <figure class="swiper-slide">

                            <picture class="swiper-picture">
                                <?php if ($featured_image["large"]): ?>
                                    <?php echo __gulp_init_namespace___img($featured_image["large"], array("media" => "(min-width: 64em)"), false, "source"); ?>
                                <?php endif; ?>

                                <?php if ($featured_image["medium"]): ?>
                                    <?php echo __gulp_init_namespace___img($featured_image["medium"], array("media" => "(min-width: 40em)"), false, "source"); ?>
                                <?php endif; ?>

                                <?php if ($featured_image["small"]): ?>
                                    <?php echo __gulp_init_namespace___img($featured_image["small"], array("alt" => $featured_image["alt"], "class" => "swiper-image")); ?>
                                <?php endif; ?>
                            </picture><!--/.swiper-picture-->

                            <?php if ($title): ?>
                                <header class="swiper-caption">
                                    <div class="swiper-caption-inner">
                                        <h1 class="swiper-title title __nomargin" role="heading">
                                            <?php echo $title; ?>
                                        </h1><!--/.swiper-title.title.__nomargin-->
                                    </div><!--/.swiper-caption-inner-->
                                </header>
                            <?php endif; ?>

                        </figure><!--/.swiper-slide-->
                    <?php endif; ?>
                </div> <!--/.swiper-wrapper-->

                <?php if ($slideshow && ($navigation || $pagination) && $i > 1): ?>
                    <?php if ($navigation): ?>
                        <div class="swiper-pagination"></div>
                    <?php endif; ?>

                    <?php if ($pagination): ?>
                        <button class="swiper-button --prev">
                            <i class="swiper-button-icon fas fa-caret-left"></i>
                            <span class="__visuallyhidden"><?php _e("Previous Slide", "__gulp_init_namespace__"); ?></span>
                        </button><!--/.swiper-button.--prev-->

                        <button class="swiper-button --next">
                            <i class="swiper-button-icon fas fa-caret-right"></i>
                            <span class="__visuallyhidden"><?php _e("Next Slide", "__gulp_init_namespace__"); ?></span>
                        </button><!--/.swiper-button.--next-->
                    <?php endif; ?>
                <?php endif; ?>

            </div><!--/.hero__swiper-container.swiper-container.--hero-->
        </div><!--/.hero__inner-->
    </div><!--/.hero-block-->
<?php endif; ?>
