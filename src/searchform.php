<?php $search_query = is_search() ? get_search_query() : ""; ?>
<form class="search-form" action="<?php echo home_url(); ?>" method="get">
    <label class="search-label content" for="s"><? _e("Search for:", "new-site"); ?></label>
    <input class="search-input input" name="s" title="<? _e("Search for:", "new-site"); ?>" type="search" value="<?php echo $search_query; ?>" />
    <button class="search-submit button" type="submit"><? _e("Search", "new-site"); ?></button>
</form><!--/.search-form-->
