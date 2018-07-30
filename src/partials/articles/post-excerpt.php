<?php
$post      = isset($template_args["post"]) ? $template_args["post"] : false;
$class     = isset($template_args["class"]) ? $template_args["class"] : "";
$permalink = isset($template_args["permalink"]) ? $template_args["permalink"] : ($post ? get_the_permalink($post->ID) : "");
$title     = isset($template_args["title"]) ? $template_args["title"] : ($post ? $post->post_title : "");
$meta      = isset($template_args["meta"]) ? $template_args["meta"] : ($post ? get_article_meta($post->ID) : "");
$excerpt   = isset($template_args["excerpt"]) ? $template_args["excerpt"] : ($post ? get_better_excerpt($post->ID, 55, "&hellip; " . $permalink ? "<a class='article_link link' href='" . apply_filters("the_permalink", $permalink) . "'>" . __("Read More", "__gulp_init__namespace") . "</a>" : "") : "");
?>

<?php if ($title || $permalink): ?>
    <article class="article -excerpt <?php echo $class; ?>">

        <?php if ($title || $meta): ?>
            <header class="article_header">
                <?php if ($title): ?>
                    <h3 class="article_title title">
                        <?php if ($permalink): ?>
                            <a class="article_link link" href="<?php echo $permalink; ?>">
                        <?php endif; ?>

                        <?php echo $title; ?>

                        <?php if ($permalink): ?>
                            </a><!--/.article_link.link-->
                        <?php endif; ?>
                    </h3><!--/.article_title.title-->
                <?php endif; ?>

                <?php if ($meta): ?>
                    <?php __gulp_init__namespace_get_template_part("partials/modules/menu-list-meta.php", array("class" => "article_menu-list_container", "meta" => $meta)); ?>
                <?php endif; ?>
            </header><!--/.article_header-->
        <?php endif; ?>

        <?php if ($excerpt): ?>
            <div class="article_content">
                <div class="article_user-content user-content">
                    <p class="article_text text"><?php echo $excerpt; ?></p>
                </div><!--/.article_user-content.user-content-->
            </div><!--/.article_content-->
        <?php endif; ?>

    </article><!--/.article-->
<?php endif; ?>
