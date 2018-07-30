<?php
$class = isset($template_args["class"]) ? " {$template_args["class"]}" : "";
$meta  = isset($template_args["meta"]) ? $template_args["meta"] : false;
?>

<?php if ($meta): ?>
    <nav class="menu-list_container<?php echo $class; ?>">
        <ul class="menu-list -meta">
            <?php foreach ($meta as $key => $data): ?>

                <?php if ($data): ?>
                    <li class="menu-list_item">

                        <?php if (isset($data["icon"]) && $data["icon"]): ?>
                            <i class="far <?php echo $data["icon"]; ?> menu-list_icon"></i>
                        <?php endif; ?>

                        <?php if ($key === "date" && isset($data["datetime"]) && $data["datetime"]): ?>
                            <time class="menu-list_time" datetime="<?php echo $data["datetime"]; ?>">
                        <?php endif; ?>

                        <?php
                        if (isset($data["links"]) && $data["links"]) {
                            foreach ($data["links"] as $index => $link) {
                                if ($link["url"]) {
                                    ?><a class="menu-list_link link" href="<?php echo $link["url"]; ?>"<?php if ($link["target"]): ?> target="<?php echo $link["target"]; ?>"<?php endif; ?>><?php
                                }

                                if ($link["title"]) {
                                    echo $link["title"];
                                }

                                if ($link["url"]) {
                                    ?></a><!--/.menu-list_link.link--><?php
                                }

                                if ($index + 1 < count($data["links"])) {
                                    ?>, <?php

                                    if ($index + 2 === count($data["links"])) {
                                        echo __("and", "__gulp_init__namespace") . " ";
                                    }
                                }
                            }
                        }
                        ?>

                        <?php if ($key === "date" && isset($data["datetime"]) && $data["datetime"]): ?>
                            </time><!--/.menu-list_time-->
                        <?php endif; ?>

                    </li><!--/.menu-list_item-->

                <?php endif; ?>
            <?php endforeach; ?>
        </ul><!--/.menu-list.-meta-->
    </nav><!--/.article_menu-list_container.menu-list_container-->
<?php endif; ?>
