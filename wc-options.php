<?php
include_once 'wc-options-serialize.php';
include_once 'includes/wc-db-helper.php';

class WC_Options {

    public $wc_options_serialized;
    public $wc_db_helper;
    private $wc_post_types;

    public function __construct() {
        $this->wc_db_helper = new WC_DB_Helper();
        $this->wc_options_serialized = new WC_Options_Serialize($this->wc_db_helper);
    }

    /**
     * Builds options page
     */
    public function main_options_form() {

        $default_post_types = get_post_types('', 'names');
        foreach ($default_post_types as $post_type) {
            if ($post_type != 'attachment' && $post_type != 'revision' && $post_type != 'nav_menu_item') {
                $this->wc_post_types[] = $post_type;
            }
        }

        if (isset($_POST['wc_submit_options'])) {

            if (function_exists('current_user_can') && !current_user_can('manage_options')) {
                die(_e('Hacker?', 'wpdiscuz'));
            }

            if (function_exists('check_admin_referer')) {
                check_admin_referer('wc_options_form');
            }

            $this->wc_options_serialized->wc_post_types = isset($_POST['wc_post_types']) ? $_POST['wc_post_types'] : array();
            $this->wc_options_serialized->wc_voting_buttons_show_hide = isset($_POST['wc_voting_buttons_show_hide']) ? $_POST['wc_voting_buttons_show_hide'] : 0;
            $this->wc_options_serialized->wc_share_buttons_show_hide = isset($_POST['wc_share_buttons_show_hide']) ? $_POST['wc_share_buttons_show_hide'] : 0;
            $this->wc_options_serialized->wc_captcha_show_hide = isset($_POST['wc_captcha_show_hide']) ? $_POST['wc_captcha_show_hide'] : 0;
            $this->wc_options_serialized->wc_user_must_be_registered = isset($_POST['wc_user_must_be_registered']) ? $_POST['wc_user_must_be_registered'] : 0;
            $this->wc_options_serialized->wc_held_comment_to_moderate = isset($_POST['wc_held_comment_to_moderate']) ? $_POST['wc_held_comment_to_moderate'] : 0;
            $this->wc_options_serialized->wc_reply_button_guests_show_hide = isset($_POST['wc_reply_button_guests_show_hide']) ? $_POST['wc_reply_button_guests_show_hide'] : 0;
            $this->wc_options_serialized->wc_reply_button_members_show_hide = isset($_POST['wc_reply_button_members_show_hide']) ? $_POST['wc_reply_button_members_show_hide'] : 0;
            $this->wc_options_serialized->wc_author_titles_show_hide = isset($_POST['wc_author_titles_show_hide']) ? $_POST['wc_author_titles_show_hide'] : 0;
            $this->wc_options_serialized->wc_comment_count = isset($_POST['wc_comment_count']) ? $_POST['wc_comment_count'] : 10;
            $this->wc_options_serialized->wc_notify_moderator = isset($_POST['wc_notify_moderator']) ? $_POST['wc_notify_moderator'] : 0;
            $this->wc_options_serialized->wc_notify_comment_author = isset($_POST['wc_notify_comment_author']) ? $_POST['wc_notify_comment_author'] : 0;
            $this->wc_options_serialized->wc_comment_bg_color = isset($_POST['wc_comment_bg_color']) ? $_POST['wc_comment_bg_color'] : '#fefefe';
            $this->wc_options_serialized->wc_reply_bg_color = isset($_POST['wc_reply_bg_color']) ? $_POST['wc_reply_bg_color'] : '#f8f8f8';
            $this->wc_options_serialized->wc_comment_text_color = isset($_POST['wc_comment_text_color']) ? $_POST['wc_comment_text_color'] : '#555';
            $this->wc_options_serialized->wc_author_title_color = isset($_POST['wc_author_title_color']) ? $_POST['wc_author_title_color'] : '#00B38F';
            $this->wc_options_serialized->wc_vote_reply_color = isset($_POST['wc_vote_reply_color']) ? $_POST['wc_vote_reply_color'] : '#666666';
            $this->wc_options_serialized->wc_custom_css = isset($_POST['wc_custom_css']) ? $_POST['wc_custom_css'] : '.comments-area{width: 100%;margin: 0 auto;}';

            $this->wc_options_serialized->update_options();
        }
        ?>

        <div class="wrap wpdiscuz_options_page">

            <div style="float:left; width:50px; height:55px; margin:10px 10px 20px 0px;">
                <img src="<?php echo plugins_url(WC_Core::$PLUGIN_DIRECTORY . '/files/img/plugin-icon/plugin-icon-48.png'); ?>" style="height:43px;"/>
            </div>
            <h2 style="padding-bottom:20px; padding-top:15px;"><?php _e('WpDiscuz General Settings', 'wpdiscuz'); ?></h2>
            <br style="clear:both" />
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=wpdiscuz_options_page&updated=true" method="post" name="wpdiscuz_options_page" class="wc-main-settings-form wc-form">
                <?php
                if (function_exists('wp_nonce_field')) {
                    wp_nonce_field('wc_options_form');
                }
                ?>
                <table cellspacing="0" class="wp-list-table widefat plugins">
                    <thead>
                        <tr>
                            <th colspan="4" scope="col">&nbsp;</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="4">&nbsp;</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php include 'options-templates/options-template-main.php'; ?>
                        <tr valign="top">
                            <td colspan="4">
                                <p class="submit">
                                    <input type="submit" class="button button-primary" name="wc_submit_options" value="<?php _e('Save Changes', 'wpdiscuz'); ?>" />
                                </p>
                            </td>
                        </tr>

                    <input type="hidden" name="action" value="update" />
                    </tbody>
                </table>
            </form>            
        </div>

        <?php
    }

    public function phrases_options_form() {

        if (isset($_POST['wc_submit_phrases'])) {

            if (function_exists('current_user_can') && !current_user_can('manage_options')) {
                die(_e('Hacker?', 'wpdiscuz'));
            }

            if (function_exists('check_admin_referer')) {
                check_admin_referer('wc_phrases_form');
            }

            $this->wc_options_serialized->wc_phrases['wc_leave_a_reply_text'] = isset($_POST['wc_leave_a_reply_text']) ? $_POST['wc_leave_a_reply_text'] : 'Leave a Reply';
            $this->wc_options_serialized->wc_phrases['wc_be_the_first_text'] = isset($_POST['wc_be_the_first_text']) ? $_POST['wc_be_the_first_text'] : 'Be the First to Comment!';            
            $this->wc_options_serialized->wc_phrases['wc_header_text'] = isset($_POST['wc_header_text']) ? $_POST['wc_header_text'] : 'Comment';
            $this->wc_options_serialized->wc_phrases['wc_header_on_text'] = isset($_POST['wc_header_on_text']) ? $_POST['wc_header_on_text'] : 'on';
            $this->wc_options_serialized->wc_phrases['wc_comment_start_text'] = isset($_POST['wc_comment_start_text']) ? $_POST['wc_comment_start_text'] : 'Start the discussion';
            $this->wc_options_serialized->wc_phrases['wc_comment_join_text'] = isset($_POST['wc_comment_join_text']) ? $_POST['wc_comment_join_text'] : 'Join the discussion';
            $this->wc_options_serialized->wc_phrases['wc_email_text'] = isset($_POST['wc_email_text']) ? $_POST['wc_email_text'] : 'Email';
            $this->wc_options_serialized->wc_phrases['wc_name_text'] = isset($_POST['wc_name_text']) ? $_POST['wc_name_text'] : 'Name';
            $this->wc_options_serialized->wc_phrases['wc_captcha_text'] = isset($_POST['wc_captcha_text']) ? $_POST['wc_captcha_text'] : 'Please insert the code above to comment';
            $this->wc_options_serialized->wc_phrases['wc_submit_text'] = isset($_POST['wc_submit_text']) ? $_POST['wc_submit_text'] : 'Post Comment';
            $this->wc_options_serialized->wc_phrases['wc_load_more_submit_text'] = isset($_POST['wc_load_more_submit_text']) ? $_POST['wc_load_more_submit_text'] : 'Load More';
            $this->wc_options_serialized->wc_phrases['wc_reply_text'] = isset($_POST['wc_reply_text']) ? $_POST['wc_reply_text'] : 'Reply';
            $this->wc_options_serialized->wc_phrases['wc_share_text'] = isset($_POST['wc_share_text']) ? $_POST['wc_share_text'] : 'Share';
            $this->wc_options_serialized->wc_phrases['wc_share_facebook'] = isset($_POST['wc_share_facebook']) ? $_POST['wc_share_facebook'] : 'Share On Facebook';
            $this->wc_options_serialized->wc_phrases['wc_share_twitter'] = isset($_POST['wc_share_twitter']) ? $_POST['wc_share_twitter'] : 'Share On Twitter';
            $this->wc_options_serialized->wc_phrases['wc_share_google'] = isset($_POST['wc_share_google']) ? $_POST['wc_share_google'] : 'Share On Google';
            $this->wc_options_serialized->wc_phrases['wc_hide_replies_text'] = isset($_POST['wc_hide_replies_text']) ? $_POST['wc_hide_replies_text'] : 'Hide Replies';
            $this->wc_options_serialized->wc_phrases['wc_show_replies_text'] = isset($_POST['wc_show_replies_text']) ? $_POST['wc_show_replies_text'] : 'Show Replies';
            $this->wc_options_serialized->wc_phrases['wc_user_title_guest_text'] = isset($_POST['wc_user_title_guest_text']) ? $_POST['wc_user_title_guest_text'] : 'Guest';
            $this->wc_options_serialized->wc_phrases['wc_user_title_member_text'] = isset($_POST['wc_user_title_member_text']) ? $_POST['wc_user_title_member_text'] : 'Member';
            $this->wc_options_serialized->wc_phrases['wc_user_title_author_text'] = isset($_POST['wc_user_title_author_text']) ? $_POST['wc_user_title_author_text'] : 'Author';
            $this->wc_options_serialized->wc_phrases['wc_user_title_admin_text'] = isset($_POST['wc_user_title_admin_text']) ? $_POST['wc_user_title_admin_text'] : 'Admin';
            $this->wc_options_serialized->wc_phrases['wc_email_subject'] = isset($_POST['wc_email_subject']) ? $_POST['wc_email_subject'] : 'New Comment';
            $this->wc_options_serialized->wc_phrases['wc_email_message'] = isset($_POST['wc_email_message']) ? $_POST['wc_email_message'] : 'New comment on the product discussion section you\'ve been interested in';
            $this->wc_options_serialized->wc_phrases['wc_error_empty_text'] = isset($_POST['wc_error_empty_text']) ? $_POST['wc_error_empty_text'] : 'please fill out this field to comment';
            $this->wc_options_serialized->wc_phrases['wc_error_email_text'] = isset($_POST['wc_error_email_text']) ? $_POST['wc_error_email_text'] : 'email address is invalid';

            $this->wc_options_serialized->wc_phrases['wc_year_text']['datetime'][0] = isset($_POST['wc_year_text']) ? $_POST['wc_year_text'] : 'year';
            $this->wc_options_serialized->wc_phrases['wc_month_text']['datetime'][0] = isset($_POST['wc_month_text']) ? $_POST['wc_month_text'] : 'month';
            $this->wc_options_serialized->wc_phrases['wc_day_text']['datetime'][0] = isset($_POST['wc_day_text']) ? $_POST['wc_day_text'] : 'day';
            $this->wc_options_serialized->wc_phrases['wc_hour_text']['datetime'][0] = isset($_POST['wc_hour_text']) ? $_POST['wc_hour_text'] : 'hour';
            $this->wc_options_serialized->wc_phrases['wc_minute_text']['datetime'][0] = isset($_POST['wc_minute_text']) ? $_POST['wc_minute_text'] : 'minute';
            $this->wc_options_serialized->wc_phrases['wc_second_text']['datetime'][0] = isset($_POST['wc_second_text']) ? $_POST['wc_second_text'] : 'second';
            $this->wc_options_serialized->wc_phrases['wc_plural_text'] = isset($_POST['wc_plural_text']) ? $_POST['wc_plural_text'] : 's';
            $this->wc_options_serialized->wc_phrases['wc_right_now_text'] = isset($_POST['wc_right_now_text']) ? $_POST['wc_right_now_text'] : 'right now';
            $this->wc_options_serialized->wc_phrases['wc_ago_text'] = isset($_POST['wc_ago_text']) ? $_POST['wc_ago_text'] : 'ago';

            $this->wc_options_serialized->wc_phrases['wc_you_must_be_text'] = isset($_POST['wc_you_must_be_text']) ? $_POST['wc_you_must_be_text'] : 'You must be';
            $this->wc_options_serialized->wc_phrases['wc_logged_in_text'] = isset($_POST['wc_logged_in_text']) ? $_POST['wc_logged_in_text'] : 'logged in';
            $this->wc_options_serialized->wc_phrases['wc_to_post_comment_text'] = isset($_POST['wc_to_post_comment_text']) ? $_POST['wc_to_post_comment_text'] : 'to post a comment';
            $this->wc_options_serialized->wc_phrases['wc_vote_counted'] = isset($_POST['wc_vote_counted']) ? $_POST['wc_vote_counted'] : 'Vote Counted';
            $this->wc_options_serialized->wc_phrases['wc_vote_up'] = isset($_POST['wc_vote_up']) ? $_POST['wc_vote_up'] : 'Vote Up';
            $this->wc_options_serialized->wc_phrases['wc_vote_down'] = isset($_POST['wc_vote_down']) ? $_POST['wc_vote_down'] : 'Vote Down';
            $this->wc_options_serialized->wc_phrases['wc_held_for_moderate'] = isset($_POST['wc_held_for_moderate']) ? $_POST['wc_held_for_moderate'] : 'Your Comment waiting moderation';
            $this->wc_options_serialized->wc_phrases['wc_vote_only_one_time'] = isset($_POST['wc_vote_only_one_time']) ? $_POST['wc_vote_only_one_time'] : 'You\'ve already voted for this comment';
            $this->wc_options_serialized->wc_phrases['wc_voting_error'] = isset($_POST['wc_voting_error']) ? $_POST['wc_voting_error'] : 'Voting Error';
            $this->wc_options_serialized->wc_phrases['wc_self_vote'] = isset($_POST['wc_self_vote']) ? $_POST['wc_self_vote'] : 'You cannot vote for your comment';
            $this->wc_options_serialized->wc_phrases['wc_login_to_vote'] = isset($_POST['wc_login_to_vote']) ? $_POST['wc_login_to_vote'] : 'You Must Be Logged In To Vote';
            $this->wc_options_serialized->wc_phrases['wc_invalid_captcha'] = isset($_POST['wc_invalid_captcha']) ? $_POST['wc_invalid_captcha'] : 'Invalid Captcha Code';
            $this->wc_options_serialized->wc_phrases['wc_invalid_field'] = isset($_POST['wc_invalid_field']) ? $_POST['wc_invalid_field'] : 'Some of field value is invalid';

            $this->wc_db_helper->update_phrases($this->wc_options_serialized->wc_phrases);
        }
        if ($this->wc_db_helper->is_phrase_exists('wc_leave_a_reply_text')) {
            $this->wc_options_serialized->wc_phrases = $this->wc_db_helper->get_phrases();
        }
        ?>
        <div class="wrap wpdiscuz_options_page">

            <div style="float:left; width:50px; height:55px; margin:10px 10px 20px 0px;">
                <img src="<?php echo plugins_url(WC_Core::$PLUGIN_DIRECTORY . '/files/img/plugin-icon/plugin-icon-48.png'); ?>" style="height:43px;"/>
            </div>
            <h2 style="padding-bottom:20px; padding-top:15px;"><?php _e('WpDiscuz Front-end Phrases', 'wpdiscuz'); ?></h2>
            <br style="clear:both" />
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=wpdiscuz_phrases_page&updated=true" method="post" name="wpdiscuz_phrases_page" class="wc-phrases-settings-form wc-form">
                <?php
                if (function_exists('wp_nonce_field')) {
                    wp_nonce_field('wc_phrases_form');
                }
                ?>
                <table cellspacing="0" class="wp-list-table widefat plugins">
                    <thead>
                        <tr>
                            <th colspan="4" scope="col">&nbsp;</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="4">&nbsp;</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php include 'options-templates/options-template-phrases.php'; ?>

                        <tr valign="top">
                            <td colspan="4">
                                <p class="submit">
                                    <input type="submit" class="button button-primary" name="wc_submit_phrases" value="<?php _e('Save Changes', 'wpdiscuz'); ?>" />
                                </p>
                            </td>
                        </tr>

                    <input type="hidden" name="action" value="update" />
                    </tbody>
                </table>
            </form>

        </div>
        <?php
    }

}
?>