<?php
$post              = isset($template_args["post"]) ? $template_args["post"] : false;
$article_class     = isset($template_args["article_class"]) ? $template_args["article_class"] : "";
$article_permalink = isset($template_args["article_permalink"]) ? $template_args["article_permalink"] : ($post ? get_the_permalink($post->ID) : "");
$article_image     = isset($template_args["article_image"]) ? $template_args["article_image"] : ($post && has_post_thumbnail($post) ? array("alt" => get_post_meta(get_post_thumbnail_id($post->ID), "_wp_attachment_image_alt", true), "url" => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), "medium")[0]) : "");
$article_title     = isset($template_args["article_title"]) ? $template_args["article_title"] : ($post ? $post->post_title : "");
$article_meta      = get_article_meta(isset($template_args["article_meta"]) ? $template_args["meta"] : array());
$article_excerpt   = isset($template_args["article_excerpt"]) ? $template_args["article_excerpt"] : ($post ? get_better_excerpt($post->ID, 55, "&hellip; " . $article_permalink ? "<a class='article_link link' href='" . apply_filters("the_permalink", $article_permalink) . "'>" . __("Read More", "__gulp_init__namespace") . "</a>" : "") : "");
?>

<?php if ($article_title || $article_permalink): ?>
    <article class="article -excerpt <?php echo $article_class; ?>">

        <?php if ($article_title || $article_meta): ?>
            <header class="article_header">
                <?php if ($article_title): ?>
                    <h3 class="article_title title">
                        <?php if ($article_permalink): ?>
                            <a class="article_link link" href="<?php echo $article_permalink; ?>">
                        <?php endif; ?>

                        <?php echo $article_title; ?>

                        <?php if ($article_permalink): ?>
                            </a><!--/.article_link.link-->
                        <?php endif; ?>
                    </h3><!--/.article_title.title-->
                <?php endif; ?>

                <?php if ($article_meta): ?>
                    <?php __gulp_init__namespace_get_template_part("partials/lists/meta.php", array("list_class" => "article_menu-list_container", "list_meta" => $article_meta)); ?>
                <?php endif; ?>
            </header><!--/.article_header-->
        <?php endif; ?>

        <?php if ($article_excerpt): ?>
            <div class="article_content">
                <div class="article_user-content user-content">
                    <p class="article_text text"><?php echo $article_excerpt; ?></p>
                </div><!--/.article_user-content.user-content-->
            </div><!--/.article_content-->
        <?php endif; ?>

    </article><!--/.article-->
<?php endif; ?>
