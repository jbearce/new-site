<?php
$title      = isset($title) ? $title : (isset($post) ? $post->post_title : "");
$permalink  = isset($permalink) ? $permalink : (isset($post) ? get_the_permalink($post->ID) : "");
$categories = isset($categories) ? $categories : (isset($post) ? get_the_terms($post->ID, "category") : "");
$tags       = isset($tags) ? $tags : (isset($post) ? get_the_terms($post->ID, "post_tag") : "");
$comments   = isset($comments) ? $comments : (isset($post) ? get_comments_number($post->ID) : "");
$content    = isset($content) ? $content : (isset($post) ? $post->post_content : "");
?>

<article class="content_article article">

    <header class="article_header">
        <?php if ($title): ?>
            <h1 class="article_title title">
                <?php echo apply_filters("the_title", $title); ?>
            </h1><!--/.article_title.title-->
        <?php endif; ?>

        <?php if (!(isset($show_meta) && $show_meta === false) && (get_post_type() === "post" || (isset($show_meta) && $show_meta === true)) && ($permalink || $categories || $tags || $comments)): ?>
            <nav class="menu-list_container">
                <ul class="menu-list -meta">

                    <?php if ($permalink): ?>
                        <li class="menu-list_item">
                            <a class="menu-list_link link" href="<?php echo apply_filters("the_permalink", $permalink); ?>"><icon use="clock" /> <time datetime="<?php echo get_the_date("c"); ?>"><?php the_date(); ?></time></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($categories): ?>
                        <?php $i = 0; ?>
                        <li class="menu-list_item">
                            <icon use="folder" />

                            <?php foreach ($categories as $category): ?>
                                <?php $i++; ?>

                                <a class="menu-list_link link" href="<?php echo get_term_link($category); ?>"><?php echo $category->name; ?></a>

                                <?php if ($i < count($categories)): ?>, <?php endif;?>
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
                                <icon use="comment" /> <?php echo $comments; ?> <?php _e("Comments", "deerfield"); ?>
                            </a><!--/.menu-list_link.link-->
                        </li><!--/.menu-list_item-->
                    <?php endif; ?>

                </ul><!--/.menu-list.-meta-->
            </nav><!--/.menu-list_container-->
        <?php endif; // if (!(isset($show_meta) && $show_meta === false) && (get_post_type() === "post" || (isset($show_meta) && $show_meta === true)) && ($permalink || $categories || $tags || $comments)) ?>

    </header><!--/.article_header-->

    <?php if ($content): ?>
        <div class="article_content">
            <div class="article_user-content user-content">
                <?php echo apply_filters("the_content", $content); ?>
            </div><!--/.article_user-content.user-content-->
        </div><!--/.article_content-->
    <?php endif; ?>

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
