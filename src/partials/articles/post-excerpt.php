<?php
$post      = isset($template_args["post"]) ? $template_args["post"] : false;
$class     = isset($template_args["class"]) ? " {$template_args["class"]}" : "";
$light     = isset($template_args["light"]) ? $template_args["light"] : false;
$permalink = isset($template_args["permalink"]) ? $template_args["permalink"] : ($post ? get_the_permalink($post->ID) : false);
$title     = isset($template_args["title"]) ? $template_args["title"] : ($post ? $post->post_title : false);
$meta      = isset($template_args["meta"]) ? $template_args["meta"] : ($post ? __gulp_init__namespace_get_article_meta($post->ID, array("date", "author", "comments", "taxonomies" => array(array("icon" => "fa-folder", "name" => "category"), array("icon" => "fa-tag", "name" => "post_tag")))) : false);
$excerpt   = isset($template_args["excerpt"]) ? $template_args["excerpt"] : ($post ? __gulp_init__namespace_get_the_excerpt($post->ID, 55, "&hellip;") : false);
?>

<?php if ($title || $permalink): ?>
    <article class="article --excerpt<?php echo $class; ?>">

        <?php if ($title || $meta): ?>
            <header class="article__header">
                <?php if ($title): ?>
                    <h3 class="article__title title<?php if ($light): ?> __light<?php endif; ?>">
                        <?php if ($permalink): ?>
                            <a class="text__link link<?php if ($light): ?> --inherit<?php endif; ?>" href="<?php echo $permalink; ?>">
                        <?php endif; ?>

                        <?php echo $title; ?>

                        <?php if ($permalink): ?>
                            </a><!--/.article__link.link-->
                        <?php endif; ?>
                    </h3><!--/.article__title.title-->
                <?php endif; ?>

                <?php if ($meta): ?>
                    <?php __gulp_init__namespace_get_template_part("partials/modules/menu-list-meta.php", array("class" => "article__menu-list__container", "light" => $light, "meta" => $meta)); ?>
                <?php endif; ?>
            </header><!--/.article_header-->
        <?php endif; ?>

        <?php if ($excerpt): ?>
            <div class="article__content">
                <div class="article__user-content user-content">
                    <p class="article__text text<?php if ($light): ?> __light<?php endif; ?>">
                        <?php echo $excerpt; ?>
                        <?php if ($permalink): ?>
                            <a class="text__link link<?php if ($light): ?> --inherit<?php endif; ?>">
                                <?php _e("Read More", "__gulp_init__namespace"); ?>
                            </a><!--/.text__link.link-->
                        <?php endif; ?>
                    </p><!--/.article__text.text-->
                </div><!--/.article__user-content.user-content-->
            </div><!--/.article__content-->
        <?php endif; ?>

    </article><!--/.article.-excerpt-->
<?php endif; ?>
