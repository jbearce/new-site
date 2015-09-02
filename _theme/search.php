<? get_header(); ?>
            <div class="content-wrapper">
                <main class="content">
                    <div class="post">
                        <form class="search-form" action="<? echo home_url(); ?>" method="get">
                            <label class="search-label" for="s">Search for:</label>
                            <input class="search-input" name="s" title="Search for:" type="search" value="<? the_search_query(); ?>" />
                            <input class="search-submit" type="submit" value="Search" />
                        </form>
                        <?
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();
                                echo "<article>";
                                echo "<header>";
                                echo "<h2><a href='" . get_permalink() . "'>" . get_the_title() . "</a></h2>";
                                echo "<ul class='meta-list'>";
                                echo "<li class='url'><a href='" . get_the_permalink() . "'>" . str_replace("http://", "", get_the_permalink()) . "</a></li>";
                                echo "</ul>";
                                echo "</header>";
                                the_excerpt();
                                echo "</article>";
                            }
                        } else {
                            echo "<p>No results found for <strong>" . get_search_query() . "</strong>.</p>";
                        }
                        ?>
                        <?
                        if (get_adjacent_post(false, "", false) || get_adjacent_post(false, "", true)) {
                            echo "<footer><p style='overflow:hidden;'>";
                            if (get_adjacent_post(false, "", false)) {
                                previous_posts_link("<span style='float:left;'>&larr; Previous Page</span>");
                            }
                            if (get_adjacent_post(false, "", true)) {
                                next_posts_link("<span style='float:right;'>Next Page &rarr;</span>");
                            }
                            echo "</p></footer>";
                        }
                        ?>
                    </div><!--/.post-->
				    <? get_sidebar(); ?>
                </main><!--/.content-->
            </div><!--/.content-wrapper-->
<? get_footer(); ?>
