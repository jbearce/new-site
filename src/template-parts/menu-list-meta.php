<?php
$class           = isset($this->vars["class"]) ? $this->vars["class"] : "";
$container_class = gettype($class) === "array" && key_exists("container", $class) ? " {$class["container"]}" : (gettype($class) === "string" ? " {$class}" : "");
$list_class      = gettype($class) === "array" && key_exists("list", $class) ? " {$class["list"]}" : "";
$light           = isset($this->vars["light"]) ? $this->vars["light"] : false;
$meta            = isset($this->vars["meta"]) ? $this->vars["meta"] : false;
?>

<?php if ($meta): ?>
    <nav class="menu-list__container<?php echo $container_class; ?>">
        <ul class="menu-list menu-list--meta<?php echo $list_class; ?><?php if ($light): ?> __light<?php endif; ?>">
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
                                    ?><a class="menu-list__link link<?php if ($light): ?> link--inherit<?php endif; ?>" href="<?php echo $link["url"]; ?>"<?php if ($link["target"]): ?> target="<?php echo $link["target"]; ?>"<?php endif; ?>><?php
                                }

                                if ($link["title"]) {
                                    echo $link["title"];
                                }

                                if ($link["url"]) {
                                    ?></a><?php
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
