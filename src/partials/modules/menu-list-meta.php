<?php
$class = isset($template_args["class"]) ? " {$template_args["class"]}" : "";
$light = isset($template_args["light"]) ? $template_args["light"] : false;
$meta  = isset($template_args["meta"]) ? $template_args["meta"] : false;
?>

<?php if ($meta): ?>
    <nav class="menu-list__container<?php echo $class; ?>">
        <ul class="menu-list --meta<?php if ($light): ?> __light<?php endif; ?>">
            <?php foreach ($meta as $key => $data): ?>

                <?php if ($data): ?>
                    <li class="menu-list__item">

                        <?php if (isset($data["icon"]) && $data["icon"]): ?>
                            <i class="menu-list__icon far <?php echo $data["icon"]; ?>"></i>
                        <?php endif; ?>

                        <?php if ($key === "date" && isset($data["datetime"]) && $data["datetime"]): ?>
                            <time class="menu-list__time" datetime="<?php echo $data["datetime"]; ?>">
                        <?php endif; ?>

                        <?php
                        if (isset($data["links"]) && $data["links"]) {
                            foreach ($data["links"] as $index => $link) {
                                if ($link["url"]) {
                                    ?><a class="menu-list__link link<?php if ($light): ?> --inherit<?php endif; ?>" href="<?php echo $link["url"]; ?>"<?php if ($link["target"]): ?> target="<?php echo $link["target"]; ?>"<?php endif; ?>><?php
                                }

                                if ($link["title"]) {
                                    echo $link["title"];
                                }

                                if ($link["url"]) {
                                    ?></a><!--/.menu-list__link.link--><?php
                                }

                                if ($index + 1 < count($data["links"])) {
                                    ?>, <?php

                                    if ($index + 2 === count($data["links"])) {
                                        echo __("and", "__gulp_init_namespace__") . " ";
                                    }
                                }
                            }
                        }
                        ?>

                        <?php if ($key === "date" && isset($data["datetime"]) && $data["datetime"]): ?>
                            </time><!--/.menu-list__time-->
                        <?php endif; ?>

                    </li><!--/.menu-list__item-->

                <?php endif; ?>
            <?php endforeach; ?>
        </ul><!--/.menu-list-->
    </nav><!--/.article__menu-list__container.menu-list__container-->
<?php endif; ?>
