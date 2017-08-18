<?php
$title      = isset($title) && $title ? $title : (isset($post) ? $post->post_title : "");
$permalink  = isset($permalink) && $permalink ? $permalink : (isset($post) ? get_the_permalink($post->ID) : "");
$categories = isset($categories) && $categories ? $categories : (isset($post) ? get_the_terms($post->ID, "category") : "");
$tags       = isset($tags) && $tags ? $tags : (isset($post) ? get_the_terms($post->ID, "post_tag") : "");
$comments   = isset($comments) && $comments ? $comments : (isset($post) ? get_comments_number($post->ID) : "");
$excerpt    = isset($excerpt) && $excerpt ? $excerpt : (isset($post) ? get_better_excerpt($post->ID, 55, "&hellip; " . $permalink ? "<a class='article_link link' href='" . apply_filters("the_permalink", $permalink) . "'>" . __("Read More", "new_site") . "</a>" : "") : "");
?>

<article class="content_article article -excerpt">

    <header class="article_header">
        <h2 class="article_title title">
            <?php if ($permalink): ?>
                <a class="article_link link" href="<?php echo apply_filters("the_permalink", $permalink); ?>">
            <?php endif; ?>

            <?php echo apply_filters("the_title", $title); ?>

            <?php if ($permalink): ?>
                </a><!--/.article_link.link-->
            <?php endif; ?>
        </h2><!--/.article_title.title-->

        <?php if (!(isset($show_meta) && $show_meta === false) && (get_post_type() === "post" || (isset($show_meta) && $show_meta === true)) && ($permalink || $categories || $tags || $comments)): ?>
            <nav class="menu-list_container">
                <ul class="menu-list -meta">

                    <li class="menu-list_item">
                        <a class="menu-list_link link" href="<?php the_permalink(); ?>"><icon use="clock" /> <time datetime="<?php echo get_the_date("c"); ?>"><?php the_date(); ?></time></a>
                    </li>

                    <?php if ($categories): ?>
                        <?php $i = 0; ?>
                        <li class="menu-list_item">
                            <icon use="folder" />

                            <?php foreach ($categories as $category): ?>
                                <?php $i++; ?>

                                <a class="menu-list_link link" href="<?php echo get_term_link($category); ?>"><?php echo $category->name; ?></a>

                                <?php if ($i < count($categories)): ?>, <?php endif; ?>
                            <?php endforeach; // foreach ($categories as $category) ?>

                        </li><!--/.menu-list_item-->
                    <?php endif; // if ($categories) ?>

                    <?php if ($tags): ?>
                        <?php $i = 0; ?>
                        <li class="menu-list_item">
                            <icon use="tag" />

                            <?php foreach ($tags as $tag): ?>
                                <?php $i++; ?>

                                <a class="menu-list_link link" href="<?php echo get_term_link($tag); ?>"><?php echo $tag->name; ?></a>

                                <?php if ($i < count($tags)): ?>, <?php endif; ?>
                            <?php endforeach; // foreach ($tags as $tag) ?>

                        </li><!--/.menu-list_item-->
                    <?php endif; // if ($tags) ?>

                    <?php if ($comments): ?>
                        <li class="menu-list_item">
                            <a class="menu-list_link link" href="#comments">
                                <icon use="comment" /> <?php echo $comments; ?> <?php _e("Comments", "new_site"); ?>
                            </a><!--/.menu-list_link.link-->
                        </li><!--/.menu-list_item-->
                    <?php endif; ?>

                </ul><!--/.menu-list.-meta-->
            </nav><!--/.menu-list_container-->
        <?php endif; // (!(isset($show_meta) && $show_meta === false) && (get_post_type() === "post" || (isset($show_meta) && $show_meta === true)) && ($permalink || $categories || $tags || $comments)) ?>

    </header><!--/.article_header-->

    <div class="article_content">
        <div class="article_user-content user-content">
            <p class="article_text text"><?php echo $excerpt; ?></p>
        </div><!--/.article_user-content.user-content-->
    </div><!--/.article_content-->

</article><!--/.content_article.article.-full-->

<?php
unset($title);
unset($permalink);
unset($categories);
unset($tags);
unset($comments);
unset($content);
unset($show_meta);
?>
