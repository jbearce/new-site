<?php
$post_variant = isset($post_vairant) ? " {$post_variant}" : "";
$post_object  = isset($post_object) ? $post_object : get_queried_object();
$post_type    = isset($post_type) ? $post_type : (get_post_type() ? get_post_type() : __("post", "new_site"));
$post_error   = isset($post_error) ? $post_error : (is_archive() ? __("Sorry, no {$post_type}s could be found in this {$queried_object->taxonomy}.", "new_site") : (is_search() ? (get_search_query() ? __("Sorry, no {$post_type} could be found for the search phrase &ldquo;" . get_search_query() . "&rdquo;.", "new_site") : __("No search query was entered.", "new_site")) : __("Sorry, no {$post_type}s could be found matching this criteria.", "new_site")));
?>
<?php if ($post_error): ?>
    <article class="article<?php echo $post_variant; ?>">
        <div class="article_content">
            <p class="article_text text"><?php echo $post_error; ?></p>
        </div><!--/.article_content-->
    </article><!--/.article-->
<?php endif; ?>
