<div class="comments" id="comments">
    <?php
    if (post_password_required()) {
        echo "<p class='text'>" . __("This post is password protected. Enter the password to view comments.", "new-site") . "</p>";
        return;
    }
    ?>
    <?php
    if (have_comments()) {
        // get comment pagination
        $comment_nav = false;
        ob_start();
        paginate_comments_links(array(
            "prev_text" => "",
            "next_text" => "",
        ));
        $comment_nav = ob_get_contents();
        ob_end_clean();

        // display the comment list title
        echo "<h3 class='title'>" . __("Comments", "new-site") . "</h3>";

        // display the comment list
        echo "<ol class='comment-list'>";
        wp_list_comments(array("avatar_size" => 0));
        echo "</ol>";

        // display the comment navigation
        if ($comment_nav) {
            echo "<nav class='comments-nav'>";
            echo $comment_nav;
            echo "</nav>";
        }
    } elseif (!comments_open()) {
        // inform the user the comments are closed
        echo "<p class='text'>" . __("Comments are closed.", "new-site") . "</p>";
    }
    ?>
    <?php
    if (comments_open()) {
        // open the comment form wrapper
        echo "<div class='comment-form-wrapper' id='respond'>";

        // display the comment form title
        echo "<h3 class='title -sub'>";
        comment_form_title(__("Leave a Comment", "new-site"), __("Leave a Reply to %s", "new-site"));
        echo "</h3>";

        // display the cancel link
        echo "<div class='comment-cancel'>";
        cancel_comment_reply_link();
        echo "</div>";

        if (get_option("comment_registration") && !is_user_logged_in()) {
            // inform the user they must be logged in
            echo "<p class='text'>" . __("You must be", "new-site") . " <a href='" . wp_login_url(get_permalink()) . "'>" . __("logged in", "new-site") . "</a> " . __("to post a comment.", "new-site") . "</p>";
        } else {
            // open the comment form
            echo "<form class='comment-form' action='" . site_url() . "/wp-comments-post.php' method='post' id='commentform'>";

            if (is_user_logged_in()) {
                // show the logged in username
                echo "<p class='text'>" . __("Logged in as", "new-site") . " <a class='link' href='" . get_edit_user_link() . "'>{$user_identity}</a>. <a class='link' href='" . wp_logout_url(get_permalink()) . "' title='" . __("Log out of this account", "new-site") . "'>" . __("Log out", "new-site") . " &raquo;</a></p>";
            } else {
                // open a row
                echo "<div class='row'>";

                // display the name field
                $required = $req ? " (required)" : "";
                $required_aria = $req ? " aria-required='true'" : "";
                echo "<div class='col -half -padded'>";
                echo "<label class='_visuallyhidden' for='author'>" . __("Your Name", "new-site") . "{$required}</label>";
                echo "<input class='input -text' id='author' name='author' placeholder='" . __("Your Name", "new-site") . "' tabindex='1' type='text' value='{$comment_author}'{$required_aria} />";
                echo "</div>";

                // display the email field
                $required = $req ? " " . __("required", "new-site") : "";
                $required_aria = $req ? " aria-required='true'" : "";
                echo "<div class='col -half -padded'>";
                echo "<label class='_visuallyhidden' for='email'>" . __("Email Address (will not be published)", "new-site") . "{$required}</label>";
                echo "<input class='input -email' id='email' name='email' placeholder='" . __("Email Address", "new-site") . "' tabindex='2' type='email' value='{$comment_author_email}'{$required_aria} />";
                echo "</div>";

                // close the row
                echo "</div>";
            }

            // display comment field
            echo "<textarea class='input -textarea' id='comment' name='comment' placeholder='" . __("Comment", "new-site") . "' tabindex='4'></textarea>";

            // display submit button
            echo"<input class='comment-submit button' name='submit' type='submit' id='submit' tabindex='5' value='" . __("Submit", "new-site") . "' />";

            // display hidden fields
            comment_id_fields();

            // do the comment action
            do_action("comment_form", $post->ID);

            // close the comment form
            echo "</form>";
        }

        // close the comment form wrapper
        echo "</div>";
    }
    ?>
</div><!--/.comments-->
