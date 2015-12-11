<?
if (post_password_required()) {
    echo "<p class='txt txtp nocomments'>";
    _e("This post is password protected. Enter the password to view comments.");
    echo "</p>";
    return;
}
?>
<? if (have_comments()): ?>
    <h3 id="comments"><? printf(_n('One Response to %2$s', '%1$s Responses to %2$s', get_comments_number()), number_format_i18n(get_comments_number()), '&#8220;' . get_the_title() . '&#8221;'); ?></h3>
    <div class="navigation">
        <div class="alignleft"><? previous_comments_link(); ?></div>
        <div class="alignright"><? next_comments_link(); ?></div>
    </div>
    <ol class="commentlist">
        <? wp_list_comments();?>
    </ol>
    <div class="navigation">
        <div class="alignleft"><? previous_comments_link(); ?></div>
        <div class="alignright"><? next_comments_link(); ?></div>
    </div>
<? elseif (!comments_open()): ?>
    <p class="txt txtp nocomments"><? _e("Comments are closed."); ?></p>
<? endif; ?>
<? if (comments_open()): ?>
<div id="respond">
    <h3><? comment_form_title(__('Leave a Reply'), __('Leave a Reply to %s')); ?></h3>
    <div id="cancel-comment-reply">
        <small><? cancel_comment_reply_link(); ?></small>
    </div>
    <? if (get_option('comment_registration') && !is_user_logged_in()): ?>
    <p class="txt txtp"><? printf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(get_permalink())); ?></p>
    <? else: ?>
    <form action="<? echo site_url(); ?>/wp-comments-post.php" method="post" id="commentform">
        <? if (is_user_logged_in()): ?>
        <p class="txt txtp"><? printf(__('Logged in as <a href="%1$s">%2$s</a>.'), get_edit_user_link(), $user_identity); ?> <a href="<? echo wp_logout_url(get_permalink()); ?>" title="<? esc_attr_e('Log out of this account'); ?>"><? _e('Log out &raquo;'); ?></a></p>
        <? else: ?>
        <p class="txt txtp"><input class="inpt inpttext" type="text" name="author" id="author" value="<? echo esc_attr($comment_author); ?>" size="22" tabindex="1" <? if ($req) echo "aria-required='true'"; ?> />
        <label class="txt txtlabel" for="author"><small><? _e('Name'); ?> <? if ($req) _e('(required)'); ?></small></label></p>
        <p class="txt txtp"><input class="inpt inpttext" type="text" name="email" id="email" value="<? echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <? if ($req) echo "aria-required='true'"; ?> />
        <label class="txt txtlabel" for="email"><small><? _e('Mail (will not be published)'); ?> <? if ($req) _e('(required)'); ?></small></label></p>
        <p class="txt txtp"><input class="inpt inpttext" type="text" name="url" id="url" value="<? echo  esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
        <label class="txt txtlabel" for="url"><small><? _e('Website'); ?></small></label></p>
        <? endif; ?>
        <!--<p><small><? printf(__('<strong>XHTML:</strong> You can use these tags: <code>%s</code>'), allowed_tags()); ?></small></p>-->
        <p class="txt txtp"><textarea class="inpt inpttextarea" name="comment" id="comment" cols="58" rows="10" tabindex="4"></textarea></p>
        <p class="txt txtp">
        <input class="inpt inptsubmit" name="submit" type="submit" id="submit" tabindex="5" value="<? esc_attr_e('Submit Comment'); ?>" />
        <? comment_id_fields(); ?>
        </p>
        <? do_action('comment_form', $post->ID); ?>
    </form>
    <? endif; ?>
</div>
<? endif; ?>
