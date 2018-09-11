<?php
$class = isset($template_args["class"]) ? " {$template_args["class"]}" : "";
$light = isset($template_args["light"]) ? $template_args["light"] : false;
$error = isset($template_args["error"]) ? $template_args["error"] : false;
?>

<?php if ($error): ?>
    <article class="article<?php echo $class; ?>">
        <div class="article_content">
            <p class="article_text text<?php if ($light): ?> _light<?php endif; ?>"><?php echo $error; ?></p>
        </div><!--/.article_content-->
    </article><!--/.article-->
<?php endif; ?>
