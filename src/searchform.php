<?php $search_query = is_search() ? get_search_query() : ""; ?>
<form class="search-form" action="<?php echo home_url(); ?>" method="get">
    <label class="search-form__label __visuallyhidden" for="s"><?php _e("Search for:", "__gulp_init_namespace__"); ?></label>
    <input class="search-form__input input" name="s" title="<?php _e("Search for:", "__gulp_init_namespace__"); ?>" type="search" value="<?php echo $search_query; ?>" />
    <button class="search-form__button button" type="submit"><i class="button__icon far fa-search"></i><span class="__visuallyhidden"><?php _e("Search", "__gulp_init_namespace__"); ?></span></button>
</form><!--/.search-form-->
