<?php
$post            = isset($this->vars["post"]) ? $this->vars["post"] : false;
$class           = isset($this->vars["class"]) ? $this->vars["class"] : "";
$container_class = gettype($class) === "array" && key_exists("container", $class) ? " {$class["container"]}" : (gettype($class) === "string" ? " {$class}" : "");
$list_class      = gettype($class) === "array" && key_exists("list", $class) ? " {$class["list"]}" : "";
$light           = isset($this->vars["light"]) ? $this->vars["light"] : false;
$permalink       = isset($this->vars["permalink"]) ? $this->vars["permalink"] : ($post ? get_permalink($post->ID) : false);
$datetime        = isset($this->vars["datetime"]) ? new DateTime($this->vars["datetime"]) : ($post ? new DateTime(get_the_time("c", $post->ID)) : false);
$author_id       = isset($this->vars["author_id"]) ? $this->vars["author_id"] : ($post ? get_post_field("post_author", $post->ID) : false);
$comments        = isset($this->vars["comments"]) ? $this->vars["comments"] : ($post ? ["count" => get_comments_number($post->ID), "url" => get_comments_link($post->ID)] : false);
$taxonomies      = isset($this->vars["taxonomies"]) ? $this->vars["taxonomies"] : [["icon" => "fas fa-folder", "name" => "category", "prefix" => __("Posted in:", "__gulp_init_namespace__")], ["icon" => "fas fa-tag", "name" => "post_tag", "prefix" => __("Tagged with:", "__gulp_init_namespace__")]];
?>

<?php if ($post): ?>
    <nav class="menu-list__container<?php echo $container_class; ?>">
        <ul class="menu-list menu-list--meta<?php echo $list_class; ?><?php if ($light): ?> __light<?php endif; ?>">

            <?php if ($datetime): ?>
                <li class="menu-list__item">

                    <i class="menu-list__icon fas fa-clock"></i>

                    <?php if ($permalink): ?>
                        <a class="menu-list__link link" href="<?php echo esc_url($permalink); ?>">
                    <?php endif; ?>

                    <time class="menu-list__time" datetime="<?php echo esc_attr($datetime->format("c")); ?>">

                        <span class="__visuallyhidden">
                            <?php _e("Posted on", "__gulp_init_namespace__"); ?>
                        </span>

                        <?php echo $datetime->format(get_option("date_format")); ?>

                    </time>

                    <?php if ($permalink): ?>
                        </a><!--/.menu-list__link-->
                    <?php endif; ?>

                </li><!--/.menu-list__item-->
            <?php endif; // ($datetime) ?>

            <?php if ($author_id): ?>
                <li class="menu-list__item">
                    <i class="menu-list__icon fas fa-user-circle"></i>

                    <a class="menu-list__link link" href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">

                        <span class="__visuallyhidden">
                            <?php _e("Written by", "__gulp_init_namespace__"); ?>
                        </span>

                        <?php echo get_the_author_meta("display_name", $author_id); ?>

                    </a>

                </li><!--/.menu-list__item-->
            <?php endif; // ($author_id) ?>

            <?php if ($comments && $comments["count"]): ?>
                <li class="menu-list__item">

                    <i class="menu-list__icon fas fa-comment"></i>

                    <?php if ($comments["url"]): ?>
                        <a class="menu-list__link link" href="<?php echo esc_url($comments["url"]); ?>">
                    <?php endif; ?>

                    <?php printf(_n("%s comment", "%s comments", $comments["count"], "__gulp_init_namespace__"), number_format_i18n($comments["count"])); ?>

                    <?php if ($comments["url"]): ?>
                        </a>
                    <?php endif; ?>

                </li><!--/.menu-list__item-->
            <?php endif; // ($comments && $comments["count"]) ?>

            <?php if ($post && $taxonomies): ?>
                <?php foreach ($taxonomies as $taxonomy): ?>
                    <?php if ($terms = get_the_terms($post->ID, $taxonomy["name"])): $i = 0; ?>

                        <?php $term_count = count($terms); ?>

                        <li class="menu-list__item">

                            <i class="menu-list__icon <?php echo esc_attr($taxonomy["icon"]); ?>"></i>

                            <span class="__visuallyhidden">
                                <?php echo $taxonomy["prefix"]; ?>
                            </span>

                            <?php foreach ($terms as $term): $i++; ?>
                                <a class="menu-list__link link" href="<?php echo esc_url(get_term_link($term->term_id, $term->taxonomy)); ?>"><?php echo $term->name; ?></a><?php if ($i < $term_count): ?>, <?php endif; ?>
                            <?php endforeach; ?>

                        </li>

                    <?php endif; // ($terms = get_the_terms($post->ID, $taxonomy["name"])) ?>
                <?php endforeach; // ($taxonomies as $taxonomy) ?>
            <?php endif; // ($post && $taxonomies) ?>

        </ul><!--/.menu-list-->
    </nav><!--/.article__menu-list__container-->
<?php endif; ?>
