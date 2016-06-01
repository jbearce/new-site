<div class="comments" id="comments">
    <?php
    if (post_password_required()) {
        echo "<p class='comments-text text'>" . __("This post is password protected. Enter the password to view comments.", "new-site") . "</p>";
        return;
    }
    ?>
    <?php
    if (have_comments()) {
        // display the comment list title
        echo "<h3 class='comments-title title -sub'>" . __("Comments", "new-site") . "</h3>";

        // display the comment list
        echo "<ol class='comments-list'>";
        wp_list_comments(array("avatar_size" => 0));
        echo "</ol>";

        // get the comment pagination
        $comment_nav = false;
        ob_start();
        paginate_comments_links(array(
            "prev_text" => "",
            "next_text" => "",
        ));
        $comment_nav = ob_get_contents();
        ob_end_clean();

        // display the comment pagination
        if ($comment_nav) {
            echo "<nav class='comments-pagination-container pagination-container'>{$comment_nav}</nav>";
        }
    } elseif (!comments_open()) {
        // inform the user the comments are closed
        echo "<p class='comments-text text'>" . __("Comments are closed.", "new-site") . "</p>";
    }
    ?>
    <?php
    if (comments_open()) {
        // open the comment form container
        echo "<div class='comments-container' id='respond'>";

        // display the comment form title
        echo "<h3 class='comments-title title -sub'>";
        comment_form_title(__("Leave a Comment", "new-site"), __("Leave a Reply to %s", "new-site"));
        echo "</h3>";

        // get the cancel link
        $cancel_comment_reply_link = false;
        ob_start();
        cancel_comment_reply_link();
        $cancel_comment_reply_link = ob_get_contents();
        ob_end_clean();

        // display the cancel link
        if ($cancel_comment_reply_link) {
            echo "<p class='comments-text text'>" . preg_replace("/<\/small>/i", "", preg_replace("/<small>/i", "", preg_replace("/<a/i", "<a class='link'", $cancel_comment_reply_link))) . "</p>";
        }

        if (get_option("comment_registration") && !is_user_logged_in()) {
            // inform the user they must be logged in
            echo "<p class='comments-text text'>" . __("You must be", "new-site") . " <a class='comments-link link' href='" . wp_login_url(get_permalink()) . "'>" . __("logged in", "new-site") . "</a> " . __("to post a comment.", "new-site") . "</p>";
        } else {
            // open the comment form
            echo "<form class='comments-form form' action='" . site_url() . "/wp-comments-post.php' method='post' id='commentform'>";

            if (is_user_logged_in()) {
                // show the logged in username
                echo "<p class='comments-text text'>" . __("Logged in as", "new-site") . " <a class='comments-link link' href='" . get_edit_user_link() . "'>{$user_identity}</a>. <a class='comments-link link' href='" . wp_logout_url(get_permalink()) . "' title='" . __("Log out of this account", "new-site") . "'>" . __("Log out", "new-site") . " &raquo;</a></p>";
            } else {
                // open a row
                echo "<div class='comments-row row -padded'>";

                // display the name field
                $required = $req ? " (required)" : "";
                $required_aria = $req ? " aria-required='true'" : "";
                echo "<div class='comments-col col -half'>";
                echo "<label class='_visuallyhidden' for='author'>" . __("Your Name", "new-site") . "{$required}</label>";
                echo "<input class='comments-input input' id='author' name='author' placeholder='" . __("Your Name", "new-site") . "' tabindex='1' type='text' value='{$comment_author}'{$required_aria} />";
                echo "</div>";

                // display the email field
                $required = $req ? " " . __("required", "new-site") : "";
                $required_aria = $req ? " aria-required='true'" : "";
                echo "<div class='comments-col col -half'>";
                echo "<label class='_visuallyhidden' for='email'>" . __("Email Address (will not be published)", "new-site") . "{$required}</label>";
                echo "<input class='comments-input input -email' id='email' name='email' placeholder='" . __("Email Address", "new-site") . "' tabindex='2' type='email' value='{$comment_author_email}'{$required_aria} />";
                echo "</div>";

                // close the row
                echo "</div>";
            }

            // display comment field
            echo "<textarea class='comments-input input -textarea' id='comment' name='comment' placeholder='" . __("Comment", "new-site") . "' tabindex='4'></textarea>";

            // display submit button
            echo"<input class='comments-button button -submit' name='submit' type='submit' id='submit' tabindex='5' value='" . __("Submit", "new-site") . "' />";

            // display hidden fields
            comment_id_fields();

            // do the comment action
            do_action("comment_form", $post->ID);

            // close the comment form
            echo "</form>";
        }

        // close the comment form container
        echo "</div>";
    }
    ?>
</div><!--/.comments-->
