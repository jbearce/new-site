<div class="comments-block" id="comments">
    <?
    if (post_password_required()) {
        echo "<p class='password-protected text'>";
        _e("This post is password protected. Enter the password to view comments.");
        echo "</p>";
        return;
    }
    ?>
    <?
    if (have_comments()) {
        // get comment pagination
        $comment_nav = false;
        ob_start();
        paginate_comments_links(array(
            "prev_text" => "<i class='fa fa-caret-left'></i> <span class='visually-hidden'>Previous Page</span>",
            "next_text" => "<span class='visually-hidden'>Next Page</span> <i class='fa fa-caret-right'></i>",
        ));
        $comment_nav = ob_get_contents();
        ob_end_clean();

        // display the comment list title
        echo "<h3 class='title'>Comments</h3>";

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
        echo "<p class='comments-closed text'>Comments are closed.</p>";
    }
    ?>
    <?
    if (comments_open()) {
        // open the comment form wrapper
        echo "<div class='comment-form-wrapper' id='respond'>";

        // display the comment form title
        echo "<h3 class='title'>";
        comment_form_title(__("Leave a Comment"), __("Leave a Reply to %s"));
        echo "</h3>";

        // display the cancel link
        echo "<div class='comment-cancel'>";
        cancel_comment_reply_link();
        echo "</div>";

        if (get_option("comment_registration") && !is_user_logged_in()) {
            // inform the user they must be logged in
            echo "<p class='must-be-logged-in text'>You must be <a href='" . wp_login_url(get_permalink()) . "'>logged in</a> to post a comment.</p>";
        } else {
            // open the comment form
            echo "<form class='comment-form' action='" . site_url() . "/wp-comments-post.php' method='post' id='commentform'>";

            if (is_user_logged_in()) {
                // show the logged in username
                echo "<p class='comment-logged-in text'>Logged in as <a href='" . get_edit_user_link() . "'>{$user_identity}</a>. <a href='" . wp_logout_url(get_permalink()) . "' title='Log out of this account'>Log out &raquo;</a></p>";
            } else {
                // open a row
                echo "<div class='row'>";

                // display the name field
                $required = $req ? " (required)" : "";
                $required_aria = $req ? " aria-required='true'" : "";
                echo "<div class='col-half -padded-right'>";
                echo "<label class='visually-hidden' for='author'>Your Name{required}</label>";
                echo "<input class='comment-input input' id='author' name='author' placeholder='Your Name' tabindex='1' type='text' value='{$comment_author}'{$required_aria} />";
                echo "</div>";

                // display the email field
                $required = $req ? " (required)" : "";
                $required_aria = $req ? " aria-required='true'" : "";
                echo "<div class='col-half -padded-left'>";
                echo "<label class='visually-hidden' for='email'>Email Address (will not be published){$required}</label>";
                echo "<input class='comment-input input' id='email' name='email' placeholder='Email Address' tabindex='2' type='email' value='{$comment_author_email}'{$required_aria} />";
                echo "</div>";

                // close the row
                echo "</div>";
            }

            // display comment field
            echo "<textarea class='comment-input input -textarea' id='comment' name='comment' placeholder='Comment' tabindex='4'></textarea>";

            // display submit button
            echo"<input class='comment-submit button' name='submit' type='submit' id='submit' tabindex='5' value='Submit' />";

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
</div><!--/.comments-block-->
