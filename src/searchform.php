<? $search_query = is_search() ? get_search_query() : ""; ?>
<form class="search-form" action="<? echo home_url(); ?>" method="get">
    <label class="search-label content" for="s">Search for:</label>
    <input class="search-input input" name="s" title="Search for:" type="search" value="<? echo $search_query; ?>" />
    <button class="search-submit button" type="submit">Search</button>
</form><!--/.search-form-->
