<p>Sorry, it looks like something went wrong.</p>
<?
if ($_GET["error"]) {
    echo "<p>" . $_GET["error"] . "</p>";
}
if ($_GET["extra"]) {
    echo "<p>" . $_GET["extra"] . "</p>";
}
?>