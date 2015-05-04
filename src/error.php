<!doctype html>
<html lang="en-US">
	<head>
        @@include("./includes/head.htm")
        <!-- SEO -->
        <meta content="156 characters with spaces" name="description" />
        <title>Error</title>
        <!-- end SEO -->
	</head>
	<body>
        <section id="pageWrapper">
            @@include("./includes/header.htm")
            <section id="mainWrapper">
                <main>
                    <section id="post">
                        <p>Sorry, it looks like something went wrong.</p>
                        <?
                        if ($_GET["error"]) {
                            echo "<p>" . $_GET["error"] . "</p>";
                        }
                        if ($_GET["extra"]) {
                            echo "<p>" . $_GET["extra"] . "</p>";
                        }
                        ?>
                    </section><!--/#post-->
                    <section id="sidebar">
                        @@include("./includes/sidebar.htm")
                    </section><!--/#sidebar-->
                </main>
            </section><!--/#mainWrapper-->
            @@include("./includes/footer.htm")
        </section><!--/#pageWrapper-->
        @@include("./includes/nav-mobile.htm")
        @@include("./includes/foot.htm")
	</body>
</html>
