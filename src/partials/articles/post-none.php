<?php
$class = isset($template_args["class"]) ? " {$template_args["class"]}" : "";
$light = isset($template_args["light"]) ? $template_args["light"] : false;
$error = isset($template_args["error"]) ? $template_args["error"] : false;
?>

<?php if ($error): ?>
    <article class="article<?php echo $class; ?>">
        <div class="article__content">
            <p class="article__text text<?php if ($light): ?> __light<?php endif; ?>"><?php echo $error; ?></p>
        </div>
    </article>
<?php endif; ?>
