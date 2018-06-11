<?php
$article_class = isset($template_args["article_class"]) ? $template_args["article_class"] : "";
$article_error = isset($template_args["article_error"]) ? $template_args["article_error"] : false;
?>

<?php if ($article_error): ?>
    <article class="article <?php echo $article_class; ?>">
        <div class="article_content">
            <p class="article_text text"><?php echo $article_error; ?></p>
        </div><!--/.article_content-->
    </article><!--/.article-->
<?php endif; ?>
