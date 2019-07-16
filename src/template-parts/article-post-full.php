<?php
$post    = isset($this->vars["post"]) ? $this->vars["post"] : false;
$class   = isset($this->vars["class"]) ? " {$this->vars["class"]}" : "";
$light   = isset($this->vars["light"]) ? $this->vars["light"] : false;
$title   = isset($this->vars["title"]) ? $this->vars["title"] : ($post ? $post->post_title : "");
$meta    = isset($this->vars["meta"]) ? $this->vars["meta"] : ($post ? __gulp_init_namespace___get_article_meta($post->ID, array("date", "author", "comments", "taxonomies" => array(array("icon" => "fa-folder", "name" => "category"), array("icon" => "fa-tag", "name" => "post_tag")))) : false);
$content = isset($this->vars["content"]) ? $this->vars["content"] : ($post ? apply_filters("the_content", $post->post_content) : "");
?>

<?php if ($title || $content): ?>
    <article class="article<?php echo $class; ?>">

        <?php if ($title || $meta): ?>
            <header class="article__header">
                <?php if ($title): ?>
                    <h1 class="article__title title<?php if ($light): ?> __light<?php endif; ?>">
                        <?php echo $title; ?>
                    </h1>
                <?php endif; ?>

                <?php if ($meta): ?>
                    <?php
                    get_extended_template_part("menu-list", "meta", array(
                        "class" => "article__menu-list__container",
                        "light" => $light,
                        "meta"  => $meta
                    ));
                    ?>
                <?php endif; ?>
            </header><!--/.article_header-->
        <?php endif; ?>

        <?php if ($content): ?>
            <div class="article__content">
                <div class="article__user-content user-content<?php if ($light): ?> user-content--light<?php endif; ?>">
                    <?php echo $content; ?>
                </div>
            </div>
        <?php endif; ?>

    </article><!--/.article-->
<?php endif; ?>
