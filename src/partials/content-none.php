<?php
$term          = get_queried_object();
$post_type     = get_post_type() ? get_post_type() : __("post", "new_site");
$error_message = is_archive() ? __("Sorry, no {$post_type}s could be found in this {$term->taxonomy}.", "new_site") : (is_search() ? (get_search_query() ? __("Sorry, no {$post_type} could be found for the search phrase &ldquo;" . get_search_query() . "&rdquo;.", "new_site") : __("No search query was entered.", "new_site")) : __("Sorry, no {$post_type}s could be found matching this criteria.", "new_site"));
?>

<article class="content_article article -full">
    <div class="article_content">
        <p class="article_text text"><?php echo $error_message; ?></p>
    </div><!--/.article_content-->
</article><!--/.content_article.article.-full-->
