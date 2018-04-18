<?php
$list_class = isset($template_args["list_class"]) ? $template_args["list_class"] : "";
$list_meta  = isset($template_args["list_meta"]) ? $template_args["list_meta"] : false;
?>

<?php if ($list_meta): ?>
    <nav class="menu-list_container <?php echo $list_class; ?>">
        <ul class="menu-list -meta">
            <?php foreach ($list_meta as $key => $meta): ?>

                <?php if ($meta): ?>
                    <li class="menu-list_item">

                        <?php if (isset($meta["icon"]) && $meta["icon"]): ?>
                            <i class="far <?php echo $meta["icon"]; ?> menu-list_icon"></i>
                        <?php endif; ?>

                        <?php if ($key === "date" && isset($meta["datetime"]) && $meta["datetime"]): ?>
                            <time class="menu-list_time" datetime="<?php echo $meta["datetime"]; ?>">
                        <?php endif; ?>

                        <?php
                        if (isset($meta["links"]) && $meta["links"]) {
                            foreach ($meta["links"] as $index => $link) {
                                if ($link["url"]) {
                                    ?><a class="menu-list_link link" href="<?php echo $link["url"]; ?>"<?php if ($link["target"]): ?> target="<?php echo $link["target"]; ?>"<?php endif; ?>><?php
                                }

                                if ($link["title"]) {
                                    echo $link["title"];
                                }

                                if ($link["url"]) {
                                    ?></a><!--/.menu-list_link.link--><?php
                                }

                                if ($index + 1 < count($meta["links"])) {
                                    ?>, <?php

                                    if ($index + 2 === count($meta["links"])) {
                                        echo __("and", "__gulp_init__namespace") . " ";
                                    }
                                }
                            }
                        }
                        ?>

                        <?php if ($key === "date" && isset($meta["datetime"]) && $meta["datetime"]): ?>
                            </time><!--/.menu-list_time-->
                        <?php endif; ?>

                    </li><!--/.menu-list_item-->

                <?php endif; // ($meta) ?>
            <?php endforeach; ?>
        </ul><!--/.menu-list.-meta-->
    </nav><!--/.article_menu-list_container.menu-list_container-->
<?php endif; ?>
