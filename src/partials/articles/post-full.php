<?php
$post    = isset($template_args["post"]) ? $template_args["post"] : false;
$class   = isset($template_args["class"]) ? " {$template_args["class"]}" : "";
$light   = isset($template_args["light"]) ? $template_args["light"] : false;
$title   = isset($template_args["title"]) ? $template_args["title"] : ($post ? $post->post_title : "");
$meta    = isset($template_args["meta"]) ? $template_args["meta"] : ($post ? __gulp_init_namespace___get_article_meta($post->ID, array("date", "author", "comments", "taxonomies" => array(array("icon" => "fa-folder", "name" => "category"), array("icon" => "fa-tag", "name" => "post_tag")))) : false);
$content = isset($template_args["content"]) ? $template_args["content"] : ($post ? apply_filters("the_content", $post->post_content) : "");
?>

<?php if ($title || $content): ?>
    <article class="article<?php echo $class; ?>">

        <?php if ($title || $meta): ?>
            <header class="article__header">
                <?php if ($title): ?>
                    <h1 class="article__title title<?php if ($light): ?> __light<?php endif; ?>">
                        <?php echo $title; ?>
                    </h1><!--/.article__title.title-->
                <?php endif; ?>

                <?php if ($meta): ?>
                    <?php __gulp_init_namespace___get_template_part("partials/modules/menu-list-meta.php", array("class" => "article__menu-list__container", "light" => $light, "meta" => $meta)); ?>
                <?php endif; ?>
            </header><!--/.article_header-->
        <?php endif; ?>

        <?php if ($content): ?>
            <div class="article__content">
                <div class="article__user-content user-content<?php if ($light): ?> --light<?php endif; ?>">
                    <?php echo $content; ?>
                </div><!--/.article__user-content.user-content-->
            </div><!--/.article__content-->
        <?php endif; ?>

    </article><!--/.article-->
<?php endif; ?>
