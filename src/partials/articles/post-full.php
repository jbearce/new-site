<?php
$post      = isset($template_args["post"]) ? $template_args["post"] : false;
$class     = isset($template_args["class"]) ? $template_args["class"] : "";
$light     = isset($template_args["light"]) ? $template_args["light"] : false;
$title     = isset($template_args["title"]) ? $template_args["title"] : ($post ? $post->post_title : "");
$meta      = isset($template_args["meta"]) ? $template_args["meta"] : ($post ? get_article_meta($post->ID) : "");
$content   = isset($template_args["content"]) ? $template_args["content"] : ($post ? apply_filters("the_content", $post->post_content) : "");
?>

<?php if ($title || $content): ?>
    <article class="article <?php echo $class; ?>">

        <?php if ($title || $meta): ?>
            <header class="article_header">
                <?php if ($title): ?>
                    <h1 class="article_title title<?php if ($light): ?> _light<?php endif; ?>">
                        <?php echo $title; ?>
                    </h1><!--/.article_title.title-->
                <?php endif; ?>

                <?php if ($meta): ?>
                    <?php __gulp_init__namespace_get_template_part("partials/modules/menu-list-meta.php", array("class" => "article_menu-list_container", "light" => $light, "meta" => $meta)); ?>
                <?php endif; ?>
            </header><!--/.article_header-->
        <?php endif; ?>

        <?php if ($content): ?>
            <div class="article_content">
                <div class="article_user-content user-content<?php if ($light): ?> -light<?php endif; ?>">
                    <?php echo $content; ?>
                </div><!--/.article_user-content.user-content-->
            </div><!--/.article_content-->
        <?php endif; ?>

    </article><!--/.article-->
<?php endif; ?>
