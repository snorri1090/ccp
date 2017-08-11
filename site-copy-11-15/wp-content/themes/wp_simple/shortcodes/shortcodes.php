<?php

/* * *************************************************************************************************** */
// Shortcode Style
/* * *************************************************************************************************** */

add_action('wp_print_styles', 'nimbus_shortcode_styles');

function nimbus_shortcode_styles() {

    if (!is_admin()) {

        wp_register_style("shortcode_css", get_template_directory_uri() . "/shortcodes/shortcodes.css", array(), "1.0", "all");
        wp_enqueue_style('shortcode_css');
    }
}

/* * *************************************************************************************************** */
// Shortcode JS
/* * *************************************************************************************************** */

add_action('wp_enqueue_scripts', 'nimbus_shortcode_scripts');

function nimbus_shortcode_scripts() {

    if (!is_admin()) {

        wp_register_script('shortcode_js', get_template_directory_uri() . '/shortcodes/shortcodes.js', array('jquery'), '1.0');
        wp_enqueue_script('shortcode_js');
    }
}

/* * *************************************************************************************************** */

// Generate Random Strings
/* * *************************************************************************************************** */

function nimbus_rand_string($length) {
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";

    $size = strlen($chars);
    $q = 1;
    for ($i = 0; $i < $length; $i++) {
        $q .= $chars[rand(0, $size - 1)];
    }
    $str = 'nimbus_' . $q;

    return $str;
}

/* * *************************************************************************************************** */
// 
/* * *************************************************************************************************** */

// @ini_set('pcre.backtrack_limit', 500000);

/* * *************************************************************************************************** */

// Remove Wrapping Tags
/* * *************************************************************************************************** */

function nimbus_formatter($content) {
    $new_content = '';

    /* Matches the contents and the open and closing tags */
    $pattern_full = '{(\[raw\].*?\[/raw\])}is';

    /* Matches just the contents */
    $pattern_contents = '{\[raw\](.*?)\[/raw\]}is';

    /* Divide content into pieces */
    $pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

    /* Loop over pieces */
    foreach ($pieces as $piece) {
        /* Look for presence of the shortcode */
        if (preg_match($pattern_contents, $piece, $matches)) {

            /* Append to content (no formatting) */
            $new_content .= $matches[1];
        } else {

            /* Format and append to content */
            $new_content .= wptexturize(wpautop($piece));
        }
    }

    return $new_content;
}

// Remove the 2 main auto-formatters
remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');

// Before displaying for viewing, apply this function
add_filter('the_content', 'nimbus_formatter', 99);
add_filter('widget_text', 'nimbus_formatter', 99);


/* * *************************************************************************************************** */

// Strip wpautop
/* * *************************************************************************************************** */

function nimbus_strip_wpautop($content) {
    $content = do_shortcode(shortcode_unautop($content));
    return $content;
}

/* * *************************************************************************************************** */

// Shortcode Rat Kill
/* * *************************************************************************************************** */

function nimbus_shortcode_rat_kill($content) {
    $content = preg_replace('#<p><script#', '<script', $content);
    $content = preg_replace('#<p[^>]*>(\s|&nbsp;?)*<script#', '<script', $content);
    $content = preg_replace('#<p><a name="fb_share"#', '<a name="fb_share"', $content);
    $content = preg_replace('#<p><a href="https://twitter.com/share"#', '<a href="https://twitter.com/share"', $content);
    $content = preg_replace('#<p><a class="twitter-follow-button"#', '<a class="twitter-follow-button"', $content);
    $content = preg_replace('#<p><su:badge#', '<su:badge', $content);
    $content = preg_replace('#<p><center#', '<center', $content);
    $content = preg_replace('#<p><g#', '<g', $content);
    $content = preg_replace('#\/script><\/p>#', '/script>', $content);
    $content = preg_replace('#\/script><br \/>#', '/script>', $content);

    return $content;
}

add_filter('the_content', 'nimbus_shortcode_rat_kill', 99);
add_filter('widget_text', 'nimbus_shortcode_rat_kill', 99);

/* * *************************************************************************************************** */
// Add Shortcode Support to Sidebars
/* * *************************************************************************************************** */

add_filter('widget_text', 'do_shortcode');

/* * *************************************************************************************************** */
// Don't Execute Shortcode based on Show Shortcodes Plugin by Pat Friedl
/* * *************************************************************************************************** */

add_shortcode('nss', 'nss');

function nss($atts, $content = null) {
    $brackets = array();
    $brackets[0] = "/\[/";
    $brackets[1] = "/\]/";
    $replace_with = array();
    $replace_with[0] = "&#91;";
    $replace_with[1] = "&#93;";
    $content = preg_replace($brackets, $replace_with, trim($content));
    $i = "<code>" . $content . "</code>";

    return $i;
}

/* * *************************************************************************************************** */
// Responsive Shortcodes
/* * *************************************************************************************************** */


// Visible Phone

add_shortcode('nimbus_visible_phone', 'nimbus_visible_phone');

function nimbus_visible_phone($atts, $content = null) {
    return '<div class="visible-xs">' . nimbus_strip_wpautop($content) . '</div>';
}

// Visible Tablets

add_shortcode('nimbus_visible_tablet', 'nimbus_visible_tablet');

function nimbus_visible_tablet($atts, $content = null) {
    return '<div class="visible-sm">' . nimbus_strip_wpautop($content) . '</div>';
}

// Visible Desktop

add_shortcode('nimbus_visible_desktop', 'nimbus_visible_desktop');

function nimbus_visible_desktop($atts, $content = null) {
    return '<div class="visible-md">' . nimbus_strip_wpautop($content) . '</div>';
}

// Visible Desktop Large

add_shortcode('nimbus_visible_desktop_large', 'nimbus_visible_desktop_large');

function nimbus_visible_desktop_large($atts, $content = null) {
    return '<div class="visible-lg">' . nimbus_strip_wpautop($content) . '</div>';
}

// Hidden Phone

add_shortcode('nimbus_hidden_phone', 'nimbus_hidden_phone');

function nimbus_hidden_phone($atts, $content = null) {
    return '<div class="hidden-xs">' . nimbus_strip_wpautop($content) . '</div>';
}

// Hidden Tablet

add_shortcode('nimbus_hidden_tablet', 'nimbus_hidden_tablet');

function nimbus_hidden_tablet($atts, $content = null) {
    return '<div class="hidden-sm">' . nimbus_strip_wpautop($content) . '</div>';
}

// Hidden Desktop

add_shortcode('nimbus_hidden_desktop', 'nimbus_hidden_desktop');

function nimbus_hidden_desktop($atts, $content = null) {
    return '<div class="hidden-md">' . nimbus_strip_wpautop($content) . '</div>';
}

// Hidden Desktop Large

add_shortcode('nimbus_hidden_desktop_large', 'nimbus_hidden_desktop_large');

function nimbus_hidden_desktop_large($atts, $content = null) {
    return '<div class="hidden-lg">' . nimbus_strip_wpautop($content) . '</div>';
}


/* * *************************************************************************************************** */
// Date and Time Shortcodes
/* * *************************************************************************************************** */

// Current Year

add_shortcode('nimbus_current_year', 'nimbus_current_year');

function nimbus_current_year() {
    return date('Y', time());
}

// Current Date Short

add_shortcode('nimbus_current_date_short', 'nimbus_current_date_short');

function nimbus_current_date_short() {
    return date('F j, Y', time());
}

// Current Date Long

add_shortcode('nimbus_current_date_long', 'nimbus_current_date_long');

function nimbus_current_date_long() {
    return date('l, F jS, Y', time());
}

// Current Time 24

add_shortcode('nimbus_current_time_24', 'nimbus_current_time_24');

function nimbus_current_time_24() {

    $i = '<script type="text/javascript">' . "\n";
    $i .= 'var currentTime = new Date()' . "\n";
    $i .= 'var hours = currentTime.getHours()' . "\n";
    $i .= 'var minutes = currentTime.getMinutes()' . "\n";
    $i .= 'if (minutes < 10)' . "\n";
    $i .= 'minutes = "0" + minutes' . "\n";
    $i .= 'document.write("<span>" + hours + ":" + minutes + " " + "</span>")' . "\n";
    $i .= '</script>' . "\n";

    return $i;
}

// Current Time 12

add_shortcode('nimbus_current_time_12', 'nimbus_current_time_12');

function nimbus_current_time_12() {

    $i = '<script type="text/javascript">' . "\n";
    $i .= 'var currentTime = new Date()' . "\n";
    $i .= 'var hours = currentTime.getHours()' . "\n";
    $i .= 'var minutes = currentTime.getMinutes()' . "\n";
    $i .= 'var suffix = "AM";' . "\n";
    $i .= 'if (hours >= 12) {' . "\n";
    $i .= 'suffix = "PM";' . "\n";
    $i .= 'hours = hours - 12;' . "\n";
    $i .= '}' . "\n";
    $i .= 'if (hours == 0) {' . "\n";
    $i .= 'hours = 12;' . "\n";
    $i .= '}' . "\n";
    $i .= 'if (minutes < 10)' . "\n";
    $i .= 'minutes = "0" + minutes' . "\n";
    $i .= 'document.write("<span>" + hours + ":" + minutes + " " + suffix + "</span>")' . "\n";
    $i .= '</script>' . "\n";

    return $i;
}

// Current Day of Week

add_shortcode('nimbus_current_day_of_week', 'nimbus_current_day_of_week');

function nimbus_current_day_of_week() {
    return date('l', time());
}

/* * *************************************************************************************************** */
// CSS Buttons
/* * *************************************************************************************************** */

// Rounded Button

add_shortcode('nimbus_rounded_button', 'nimbus_rounded_button');

function nimbus_rounded_button($atts, $content = null) {

    extract(shortcode_atts(array(
                'text' => 'Submit',
                'font_color' => 'ffffff',
                'top' => '0074f6',
                'bottom' => '093b74',
                'ie' => 'blue',
                'text_shadow' => '062950',
                'url' => '#',
                'border' => '0859b4',
                'size' => '14px',
                'weight' => 'normal',
                'align' => 'left'
                    ), $atts));

    $unique_id = nimbus_rand_string(12);

    $i = '<p style="text-align:' . $align . ';"><a class="button_rounded button_' . $ie . ' button_' . $unique_id . ' nimbus_button" style="font-size: ' . $size . ' ; font-weight:' . $weight . '; color: #' . $font_color . '; text-shadow: -1px -1px 1px #' . $text_shadow . '; border: solid 1px #' . $border . ';  background: #' . $top . ' url(' . get_template_directory_uri() . '/shortcodes/images/button_' . $ie . '.svg) 0 0 no-repeat; background: -moz-linear-gradient(top,  #' . $top . ' 1%, #' . $bottom . ' 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,#' . $top . '), color-stop(100%,#' . $bottom . ')); background: -webkit-linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); background: -o-linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); background: -ms-linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); background: linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%);" href="' . $url . '" ><span>' . $text . '</span></a></p>';

    return $i;
}

// Radii Button

add_shortcode('nimbus_radii_button', 'nimbus_radii_button');

function nimbus_radii_button($atts, $content = null) {
    extract(shortcode_atts(array(
                'text' => 'Submit',
                'font_color' => 'ffffff',
                'top' => '0074f6',
                'bottom' => '093b74',
                'ie' => 'blue',
                'text_shadow' => '062950',
                'url' => '#',
                'border' => '0859b4',
                'size' => '18px',
                'weight' => 'normal',
                'align' => 'left'
                    ), $atts));

    $unique_id = nimbus_rand_string(12);

    $i = '<p style="text-align:' . $align . ';"><a class="button_radii button_' . $unique_id . ' nimbus_button" style="font-size: ' . $size . ' ; font-weight:' . $weight . '; color: #' . $font_color . '; text-shadow: -1px -1px 1px #' . $text_shadow . '; border: solid 1px #' . $border . ';  background: #' . $top . ' url(' . get_template_directory_uri() . '/shortcodes/images/button_' . $ie . '.svg) 0 0 no-repeat; background: -moz-linear-gradient(top,  #' . $top . ' 1%, #' . $bottom . ' 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,#' . $top . '), color-stop(100%,#' . $bottom . ')); background: -webkit-linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); background: -o-linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); background: -ms-linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); background: linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%);" href="' . $url . '" ><span>' . $text . '</span></a></p>';

    return $i;
}

// Square Buttons

add_shortcode('nimbus_square_button', 'nimbus_square_button');

function nimbus_square_button($atts, $content = null) {
    extract(shortcode_atts(array(
                'text' => 'Submit',
                'font_color' => 'ffffff',
                'top' => '0074f6',
                'bottom' => '093b74',
                'ie' => 'blue',
                'text_shadow' => '062950',
                'url' => '#',
                'border' => '0859b4',
                'size' => '18px',
                'weight' => 'normal',
                'align' => 'left'
                    ), $atts));

    $unique_id = nimbus_rand_string(12);

    $i = '<p style="text-align:' . $align . ';"><a class="button_square button_' . $unique_id . ' nimbus_button" style="font-size: ' . $size . ' ; font-weight:' . $weight . '; color: #' . $font_color . '; text-shadow: -1px -1px 1px #' . $text_shadow . '; border: solid 1px #' . $border . ';  background: #' . $top . ' url(' . get_template_directory_uri() . '/shortcodes/images/button_' . $ie . '.svg) 0 0 no-repeat; background: -moz-linear-gradient(top,  #' . $top . ' 1%, #' . $bottom . ' 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,#' . $top . '), color-stop(100%,#' . $bottom . ')); background: -webkit-linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); background: -o-linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); background: -ms-linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); background: linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); " href="' . $url . '" ><span>' . $text . '</span></a></p>';

    return $i;
}

// Sidebar Buttons

add_shortcode('nimbus_widget_button', 'nimbus_widget_button');

function nimbus_widget_button($atts, $content = null) {
    extract(shortcode_atts(array(
                'text' => 'Submit',
                'font_color' => 'ffffff',
                'top' => '0074f6',
                'bottom' => '093b74',
                'ie' => 'blue',
                'text_shadow' => '062950',
                'url' => '#',
                'border' => '0859b4',
                'size' => '18px',
                'weight' => 'normal',
                'align' => 'left'
                    ), $atts));

    $unique_id = nimbus_rand_string(12);

    $i = '<p style="text-align:' . $align . ';"><a class="button_widget button_' . $unique_id . ' nimbus_button" style="font-size: ' . $size . ' ; font-weight:' . $weight . '; color: #' . $font_color . '; text-shadow: -1px -1px 1px #' . $text_shadow . '; border: solid 1px #' . $border . ';  background: #' . $top . ' url(' . get_template_directory_uri() . '/shortcodes/images/button_' . $ie . '.svg) 0 0 no-repeat; background: -moz-linear-gradient(top,  #' . $top . ' 1%, #' . $bottom . ' 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,#' . $top . '), color-stop(100%,#' . $bottom . ')); background: -webkit-linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); background: -o-linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); background: -ms-linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); background: linear-gradient(top,  #' . $top . ' 1%,#' . $bottom . ' 100%); " href="' . $url . '" ><span>' . $text . '</span></a></p>';

    return $i;
}


// Flat Buttons

add_shortcode('nimbus_flat_button', 'nimbus_flat_button');

function nimbus_flat_button($atts, $content = null) {
    extract(shortcode_atts(array(
                'text' => 'Submit',
                'font_color' => '000000',
                'font_color_hover' => 'ffffff',
                'bg_color' => 'ffffff',
                'bg_color_hover' => '000000',
                'url' => '#',
                'border_color' => '000000',
                'border_color_hover' => 'ffffff',
                'size' => '18px',
                'weight' => 'normal',
                'align' => 'left'
                    ), $atts));

    $unique_id = nimbus_rand_string(12);

    $i = '
    
    <style type="text/css">
    .button_' . $unique_id . ' { color:#' . $font_color . '; background:#' . $bg_color . '; border:1px solid #' . $border_color . '; font-weight:' . $weight . '; font-size:' . $size . '; }
    .button_' . $unique_id . ':hover { color:#' . $font_color_hover . '; background:#' . $bg_color_hover . '; border:1px solid #' . $border_color_hover . '; }
    </style>
    
    
    <p style="text-align:' . $align . ';"><a class="button_flat button_' . $unique_id . ' nimbus_button" href="' . $url . '" ><span>' . $text . '</span></a><p>';

    return $i;
}





/* * *************************************************************************************************** */
// Columns Bootstrap
/* * *************************************************************************************************** */

// Column

add_shortcode('nimbus_column', 'nimbus_column');

function nimbus_column($atts, $content = null) {
    extract(shortcode_atts(array(
                'column_number' => '1'), $atts));
    global $NIMBUS_COLUMN_ARR;
    $NIMBUS_COLUMN_ARR[] = array('column_number' => $column_number, 'content' => $content);
}

// Row

add_shortcode('nimbus_row', 'nimbus_row');

function nimbus_row($atts, $content = null) {
    global $NIMBUS_COLUMN_ARR;
    $NIMBUS_COLUMN_ARR = array();
    do_shortcode(trim($content));
    $i = '<div class="row">';
    foreach ($NIMBUS_COLUMN_ARR as $key => $col) {
        
        $i .= '<div class="col-md-' . $col['column_number'] . '">' . do_shortcode(trim($col['content'])) . '</div>';
    }
    $i .= '</div>';

    return $i;
}






/* * *************************************************************************************************** */
// Quotes and Pullquotes
/* * *************************************************************************************************** */

// Quote

add_shortcode('nimbus_quote', 'nimbus_quote');

function nimbus_quote($atts, $content = null) {
    extract(shortcode_atts(array(
                'attribution' => '',
                'size' => '18px',
                'color' => '454545'
                    ), $atts));

    $i = '<div class="quote_wrap"><div class="quote" >' . do_shortcode(wptexturize(wpautop($content))) . '<p class="quote_attribution">&#126; ' . $attribution . '</p></div></div>';
    return $i;
}

// Pullquote Left

add_shortcode('nimbus_pullquote_left', 'nimbus_pullquote_left');

function nimbus_pullquote_left($atts, $content = null) {
    extract(shortcode_atts(array(
                'size' => '22px',
                'color' => '0078ff'
                    ), $atts));
    $i = '<div class="pullquote_left">' . do_shortcode(wptexturize(wpautop($content))) . '</div>';
    return $i;
}

// Pullquote Right

add_shortcode('nimbus_pullquote_right', 'nimbus_pullquote_right');

function nimbus_pullquote_right($atts, $content = null) {
    extract(shortcode_atts(array(
                'size' => '22px',
                'color' => '0078ff'
                    ), $atts));
    $i = '<div class="pullquote_right">' . do_shortcode(wptexturize(wpautop($content))) . '</div>';
    return $i;
}

/* * *************************************************************************************************** */
// Post Related
/* * *************************************************************************************************** */

// Publication Date

add_shortcode('nimbus_pub_date', 'nimbus_pub_date');

function nimbus_pub_date() {
    global $wpdb, $id;
    $i = get_the_time('F d, Y');
    return $i;
}

// Last Modified Date

add_shortcode('nimbus_modified_date', 'nimbus_modified_date');

function nimbus_modified_date() {
    global $wpdb, $id;
    $i = get_the_modified_time('F d, Y');
    return $i;
}

// The Author

add_shortcode('nimbus_author', 'nimbus_author');

function nimbus_author() {
    global $wpdb, $id;
    $i = get_the_author();
    return $i;
}

/* * *************************************************************************************************** */
// Content Tools
/* * *************************************************************************************************** */

// Simple Spacer

add_shortcode('nimbus_spacer', 'nimbus_spacer');

function nimbus_spacer($atts, $content = null) {
    extract(shortcode_atts(array(
                'height' => '0px'
                    ), $atts));

    return '<div class="nimbus_spacer" style="height:' . $height . '"></div>';
}

// Simple Clear

add_shortcode('nimbus_clear', 'nimbus_clear');

function nimbus_clear($atts, $content = null) {
    return '<div class="clear"></div>';
}

/* * *************************************************************************************************** */
// Twitter Related
/* * *************************************************************************************************** */

// Tweetme Button

add_shortcode('nimbus_tweetmeme', 'nimbus_tweetmeme');

function nimbus_tweetmeme($atts, $content = null) {
    global $post;
    $post_url = get_permalink($post->ID);
    $i = '<script type="text/javascript">
			tweetmeme_url = "' . $post_url . '";
			</script>
			<script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>';
    return $i;
}

// Twitter Share a Link Button

add_shortcode('nimbus_share_button', 'nimbus_share_button');

function nimbus_share_button($atts, $content = null) {
    global $post;
    $post_url = get_permalink($post->ID);
    $post_title = get_the_title($post->ID);
    $i = '<a href="https://twitter.com/share" class="twitter-share-button" data-url="' . $post_url . '" data-text="' . $post_title . '">Tweet</a>
			<script type="text/javascript">
			!function(d,s,id){
				var js,fjs=d.getElementsByTagName(s)[0];
				if(!d.getElementById(id)){
					js=d.createElement(s);
					js.id=id;js.src="//platform.twitter.com/widgets.js";
					fjs.parentNode.insertBefore(js,fjs);
				}
			}(document,"script","twitter-wjs");
			</script>';
    return $i;
}

// Twitter Follow Button

add_shortcode('nimbus_follow_button', 'nimbus_follow_button');

function nimbus_follow_button($atts, $content = null) {
    extract(shortcode_atts(array(
                'username' => 'nimbusthemes'
                    ), $atts));
    $i = '<a class="twitter-follow-button" href="https://twitter.com/' . $username . '"  data-show-count="false" data-show-screen-name="false">Follow @' . $username . '</a>
			<script type="text/javascript">
			!function(d,s,id){
				var js,fjs=d.getElementsByTagName(s)[0];
				if(!d.getElementById(id)){
					js=d.createElement(s);
					js.id=id;js.src="//platform.twitter.com/widgets.js";
					fjs.parentNode.insertBefore(js,fjs);
				}
			}(document,"script","twitter-wjs");
			</script>';
    return $i;
}

// Share on Twitter Link

add_shortcode('nimbus_share_twitter_link', 'nimbus_share_twitter_link');

function nimbus_share_twitter_link($atts, $content = null) {
    global $post;
    $post_url = get_permalink($post->ID);
    $post_title = get_the_title($post->ID);
    $i = '<a href="http://twitter.com/home/?status=Read ' . $post_title . ' at ' . $post_url . '">Share on Twitter</a>';
    return $i;
}

// Twitter Profile Widget  

add_shortcode('nimbus_twitter_profile_widget', 'nimbus_twitter_profile_widget');

function nimbus_twitter_profile_widget($atts, $content = null) {
    extract(shortcode_atts(array(
                'username' => 'nimbusthemes',
                'shell_background' => 'bdbdbd',
                'shell_font_color' => '707070',
                'tweets_background' => 'f5f5f5',
                'tweets_font_color' => '5c5c5c',
                'links_color' => '0781eb',
                'height' => '300',
                'width' => '250',
                'tweets_number' => '4',
                    ), $atts));
    $i = '	<script type="text/javascript" src="http://widgets.twimg.com/j/2/widget.js"></script>
			<script type="text/javascript">
			new TWTR.Widget({
				version: 2,
				type: "profile",
				rpp: ' . $tweets_number . ',
				interval: 30000,
				width: ' . $width . ',
				height: ' . $height . ',
				theme: {
					shell: {
						background: "#' . $shell_background . '",
						color: "#' . $shell_font_color . '"
					},
					tweets: {
						background: "#' . $tweets_background . '",
						color: "#' . $tweets_font_color . '",
						links: "#' . $links_color . '"
					}
				},
				features: {
					scrollbar: false,
					loop: false,
					live: false,
					behavior: "all"
				}
			}).render().setUser("' . $username . '").start();
			</script>';
    return $i;
}

// Twitter Search Widget  

add_shortcode('nimbus_twitter_search_widget', 'nimbus_twitter_search_widget');

function nimbus_twitter_search_widget($atts, $content = null) {
    extract(shortcode_atts(array(
                'username' => 'nimbusthemes',
                'shell_background' => 'bdbdbd',
                'shell_font_color' => '707070',
                'tweets_background' => 'f5f5f5',
                'tweets_font_color' => '5c5c5c',
                'links_color' => '0781eb',
                'height' => '300',
                'width' => '250',
                'search' => 'Nimbus Themes',
                'title' => 'Amazing WordPress Themes at',
                'subject' => 'Nimbus Themes',
                    ), $atts));
    $i = '	<script type="text/javascript" src="http://widgets.twimg.com/j/2/widget.js"></script>
			<script type="text/javascript">
			new TWTR.Widget({
				version: 2,
				type: "search",
				search: "' . $search . '",
				interval: 30000,
				title: "' . $title . '",
				subject: "' . $subject . '",
				width: ' . $width . ',
				height: ' . $height . ',
				theme: {
					shell: {
						background: "#' . $shell_background . '",
						color: "#' . $shell_font_color . '"
					},
					tweets: {
						background: "#' . $tweets_background . '",
						color: "#' . $tweets_font_color . '",
						links: "#' . $links_color . '"
					}
				},
				features: {
					scrollbar: false,
					loop: true,
					live: true,
					behavior: "default"
				}
			}).render().start();
			</script>';
    return $i;
}

/* * *************************************************************************************************** */
// Facebook Related
/* * *************************************************************************************************** */

// Facebook Like Button

add_shortcode('nimbus_facebook_like_button', 'nimbus_facebook_like_button');

function nimbus_facebook_like_button($atts, $content = null) {
    global $post;
    $post_url = get_permalink($post->ID);
    $i = '<iframe src="//www.facebook.com/plugins/like.php?href=' . $post_url . '&amp;send=false&amp;layout=standard&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true" class="likebtn"></iframe>';
    return $i;
}

// Facebook Share Button

add_shortcode('nimbus_facebook_share_button', 'nimbus_facebook_share_button');

function nimbus_facebook_share_button($atts, $content = null) {
    global $post;
    $post_url = get_permalink($post->ID);
    $i = '<a name="fb_share" type="button" share_url="' . $post_url . '"></a>
			<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
			type="text/javascript">
			</script>';
    return $i;
}

/* * *************************************************************************************************** */
// Google Plus Related
/* * *************************************************************************************************** */

add_shortcode('nimbus_google_plus_button', 'nimbus_google_plus_button');

function nimbus_google_plus_button($atts, $content = null) {
    global $post;
    $post_url = get_permalink($post->ID);
    $i = '<div class="g-plusone"></div>
			<script type="text/javascript">
			(function() {
			var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
			po.src = "https://apis.google.com/js/plusone.js";
			var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
			})();
			</script>';
    return $i;
}

/* * *************************************************************************************************** */
// Digg Related
/* * *************************************************************************************************** */

add_shortcode('nimbus_digg_button', 'nimbus_digg_button');

function nimbus_digg_button($atts, $content = null) {
    global $post;
    extract(shortcode_atts(array(
                'size' => 'DiggMedium'  //DiggWide, DiggMedium, DiggCompact, DiggIcon
                    ), $atts));
    $post_url = get_permalink($post->ID);
    $i = '<script type="text/javascript">
			(function() {
			var s = document.createElement("SCRIPT"), s1 = document.getElementsByTagName("SCRIPT")[0];
			s.type = "text/javascript";
			s.async = true;
			s.src = "http://widgets.digg.com/buttons.js";
			s1.parentNode.insertBefore(s, s1);
			})();
			</script>
			<p><a class="DiggThisButton ' . $size . '" href="http://digg.com/submit?url=' . $post_url . '"></a></p>';
    return $i;
}

/* * *************************************************************************************************** */
// Stumbleupon Related
/* * *************************************************************************************************** */


// Stumbleupon Badge Large

add_shortcode('nimbus_stumbleupon_large', 'nimbus_stumbleupon_large');

function nimbus_stumbleupon_large($atts, $content = null) {
    global $post;
    $post_url = get_permalink($post->ID);
    $i = '<su:badge layout="5" location="' . $post_url . '"></su:badge>';
    $i .= '<script type="text/javascript"> 
			(function() { 
			var li = document.createElement("script"); li.type = "text/javascript"; li.async = true; 
			li.src = "https://platform.stumbleupon.com/1/widgets.js"; 
			var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(li, s); 
			})(); 
			</script>';
    return $i;
}

// Stumbleupon Badge Small

add_shortcode('nimbus_stumbleupon_small', 'nimbus_stumbleupon_small');

function nimbus_stumbleupon_small($atts, $content = null) {
    global $post;
    $post_url = get_permalink($post->ID);
    $i = '<su:badge layout="1" location="' . $post_url . '"></su:badge>';
    $i .= '<script type="text/javascript"> 
			(function() { 
			var li = document.createElement("script"); li.type = "text/javascript"; li.async = true; 
			li.src = "https://platform.stumbleupon.com/1/widgets.js"; 
			var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(li, s); 
			})(); 
			</script>';
    return $i;
}

//******************************************************************************************************/
// Password Required
/* * *************************************************************************************************** */

add_shortcode('nimbus_login_required', 'nimbus_login_required');

function nimbus_login_required($atts, $content = null) {
    extract(shortcode_atts(array(
                'id' => 'yyyy'
                    ), $atts));
    if (is_user_logged_in()) {
        $i = '<div id="' . $id . '"></div>';
        $i .= $content;
    } else {
        $i = '<div class="pass_req_wrap">';
        $i .= '<div class="pass_req">';
        $i .= '<h2>Log In</h2>';
        $i .= '<form name="loginform" action="' . home_url() . '/wp-login.php" method="post">';
        $i .= '<p class="login-username">';
        $i .= '<label>Username</label><br />';
        $i .= '<input type="text" name="log" class="input" value="" size="20" tabindex="10" />';
        $i .= '</p>';
        $i .= '<p class="login-password">';
        $i .= '<label>Password</label><br />';
        $i .= '<input type="password" name="pwd" class="input" value="" size="20" tabindex="20" />';
        $i .= '</p>';
        $i .= '<p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> Remember Me</label></p>';
        $i .= '<p class="login-submit">';
        $i .= '<input type="submit" name="wp-submit" class="nimbus_login" value="Log In" tabindex="100" />';
        $i .= '<input type="hidden" name="redirect_to" value="' . get_permalink() . '/#' . $id . '" />';
        $i .= '</p>';
        $i .= '</form>';
        $i .= '</div>';
        $i .= '</div>';
    }
    return $i;
}

/* * *************************************************************************************************** */
// General Blog Info
/* * *************************************************************************************************** */

// Authors List

add_shortcode('nimbus_authors_list', 'nimbus_authors_list');

function nimbus_authors_list($atts = array()) {
    global $wpdb;
    $users = $wpdb->get_results("SELECT ID, display_name FROM {$wpdb->users}");

    foreach ($users as $user) {
        $i = "<div class='bio_wrap'>";
        $i .= get_avatar($user->ID, 105);
        $i .= "<h3>" . $user->display_name . "</h3>";
        $i .= "<p>" . get_user_meta($user->ID, 'description', true) . "</p>";
        $i .= "<div class='clear'></div>";
        $i .= "</div>";
    }

    return $i;
}

/* * *************************************************************************************************** */
// Google Docs Viewer
/* * *************************************************************************************************** */

add_shortcode('nimbus_google_docs_viewer', 'nimbus_google_docs_viewer');

function nimbus_google_docs_viewer($atts = array()) {
    extract(shortcode_atts(array(
                'doc_url' => ''  // Absolute File Path to Document
                    ), $atts));

    $i = '<div class="clear20"></div><center><iframe src="http://docs.google.com/viewer?url=' . $doc_url . '&embedded=true" width="600" height="780" style="border: none;"></iframe></center><div class="clear20"></div>';
    return $i;
}

 

/* * *************************************************************************************************** */
// Tooltips
/* * *************************************************************************************************** */


add_shortcode('nimbus_tooltip', 'nimbus_tooltip');

function nimbus_tooltip($atts, $content = null) {
    extract(shortcode_atts(array(
                'tip' => 'My Tool Tip Here',
                'color' => 'black', // black, white, blue, purple, orange, red, yellow, magenta, green
                'size' => 'large' // large, small
                    ), $atts));

    $i = '<span class="nimbus_tooltip_' . $size . '_' . $color . '" title="' . $tip . '" >' . $content . '</span>';
    return $i;
}

/* * *************************************************************************************************** */
// Dropcaps
/* * *************************************************************************************************** */

// Small

add_shortcode('nimbus_dropcap_small', 'nimbus_dropcap_small');

function nimbus_dropcap_small($atts, $content = null) {
    extract(shortcode_atts(array(
                'color' => '000000' // hex 
                    ), $atts));

    $i = '<span class="dropcap_small" style="color:#' . $color . ';" >' . $content . '</span>';
    return $i;
}

// Medium

add_shortcode('nimbus_dropcap_medium', 'nimbus_dropcap_medium');

function nimbus_dropcap_medium($atts, $content = null) {
    extract(shortcode_atts(array(
                'color' => '000000'  // hex 
                    ), $atts));

    $i = '<span class="dropcap_medium" style="color:#' . $color . ';" >' . $content . '</span>';
    return $i;
}

// Large

add_shortcode('nimbus_dropcap_large', 'nimbus_dropcap_large');

function nimbus_dropcap_large($atts, $content = null) {
    extract(shortcode_atts(array(
                'color' => '000000' // hex  
                    ), $atts));

    $i = '<span class="dropcap_large" style="color:#' . $color . ';" >' . $content . '</span>';
    return $i;
}

// Huge

add_shortcode('nimbus_dropcap_huge', 'nimbus_dropcap_huge');

function nimbus_dropcap_huge($atts, $content = null) {
    extract(shortcode_atts(array(
                'color' => '000000'  // hex 
                    ), $atts));

    $i = '<span class="dropcap_huge" style="color:#' . $color . ';" >' . $content . '</span>';
    return $i;
}

/* * *************************************************************************************************** */
// Nimbus Typography
/* * *************************************************************************************************** */

add_shortcode('nimbus_typography_one', 'nimbus_typography_one');

function nimbus_typography_one($atts, $content = null) {
    extract(shortcode_atts(array(
                'color' => '000000', // hex 
                'size' => '20px', // size
                'weight' => 'normal', // bold, normal  
                'shadow' => 'none',
                'style' => 'normal' // normal, italic, oblique
                    ), $atts));

    if ($shadow == 'none'){ 
        $text_shadow = '';
    } else {
        $text_shadow = 'text-shadow: 1px 1px 0px #' . $shadow . '; filter: dropshadow(color=#' . $shadow . ', offx=1, offy=1);';
    }
                    
    $i = '<span class="nimbus_typography_one" style="color:#' . $color . '; ' . $text_shadow . ' font-style:' . $style . ';  font-size:' . $size . '; font-weight:' . $weight . '; line-height:1.1em;" >' . $content . '</span>';
    return $i;
}

add_shortcode('nimbus_typography_two', 'nimbus_typography_two');

function nimbus_typography_two($atts, $content = null) {
    extract(shortcode_atts(array(
                'color' => '000000',
                'size' => '20px',
                'weight' => 'normal', // bold, normal  
                'shadow' => 'none',
                'style' => 'normal' // normal, italic, oblique
                    ), $atts));
                    
    if ($shadow == 'none'){ 
        $text_shadow = '';
    } else {
        $text_shadow = 'text-shadow: 1px 1px 0px #' . $shadow . '; filter: dropshadow(color=#' . $shadow . ', offx=1, offy=1);';
    }              

    $i = '<span class="nimbus_typography_two" style="color:#' . $color . '; ' . $text_shadow . ' font-style:' . $style . '; font-size:' . $size . '; font-weight:' . $weight . '; line-height:1.1em;" >' . $content . '</span>';
    return $i;
}

add_shortcode('nimbus_typography_three', 'nimbus_typography_three');

function nimbus_typography_three($atts, $content = null) {
    extract(shortcode_atts(array(
                'color' => '000000',
                'size' => '20px',
                'weight' => 'normal'
                    ), $atts));

    $i = '<span class="nimbus_typography_three" style="color:#' . $color . '; font-size:' . $size . '; font-weight:' . $weight . '; line-height:1.1em;" >' . $content . '</span>';
    return $i;
}

/* * *************************************************************************************************** */
// Custom Lists
/* * *************************************************************************************************** */

// Large

add_shortcode('nimbus_list', 'nimbus_list');

function nimbus_list($atts, $content = null) {
    extract(shortcode_atts(array(
                'type' => 'icons-small_358'
                    ), $atts));

    $i = '<ul class="nimbus_list" style="list-style-image:url(' . get_template_directory_uri() . '/shortcodes/images/' . $type . '.png)" >' . $content . '</ul>';
    return $i;
}

// Large

add_shortcode('nimbus_list_small', 'nimbus_list_small');

function nimbus_list_small($atts, $content = null) {
    extract(shortcode_atts(array(
                'type' => 'icons-small_358'
                    ), $atts));

    $i = '<ul class="nimbus_list_small" style="list-style-image:url(' . get_template_directory_uri() . '/shortcodes/images/' . $type . '.png)" >' . $content . '</ul>';
    return $i;
}

/* * *************************************************************************************************** */
// Tabs
/* * *************************************************************************************************** */

// Tab

add_shortcode('nimbus_tab', 'nimbus_tab');

function nimbus_tab($atts, $content = null) {
    extract(shortcode_atts(array(
                'label' => ''
                    ), $atts));

    global $NIMBUS_TAB_ARR;
    $NIMBUS_TAB_ARR[] = array('label' => $label, 'content' => $content);
}

// Tabs

add_shortcode('nimbus_tabs', 'nimbus_tabs');

function nimbus_tabs($atts, $content = null) {
    global $NIMBUS_TAB_ARR;
    $id = nimbus_rand_string(12);
    $NIMBUS_TAB_ARR = array();
    do_shortcode(trim($content));
    $select_tab = '<ul class="' . $id . ' css-tabs">';
    $content_tab ='';
    foreach ($NIMBUS_TAB_ARR as $key => $tab) {
        $unique_id = nimbus_rand_string(12);
        $select_tab .= '<li><a href="#" rel="tab" class="' . $unique_id . '">' . $tab['label'] . '</a></li>';
        $content_tab .= '<div id="tab' . $unique_id . '">' . do_shortcode(trim($tab['content'])) . '<div class="clear"></div></div>';
    }
    $select_tab .= '</ul>';
    $i = '<script type="text/javascript">jQuery(document).ready(function() { jQuery(".' . $id . '").tabs(".' . $id . '_panes > div"); });</script>';
    $i .= $select_tab . '<div class="' . $id . '_panes css-panes">' . $content_tab . '</div>';
    return $i;
}

/* * *************************************************************************************************** */
// Image Styles
/* * *************************************************************************************************** */

add_shortcode('nimbus_image', 'nimbus_image');

function nimbus_image($atts, $content = null) {
    extract(shortcode_atts(array(
                'type' => 'simple-border',
                'align' => 'none' // left, right, center, none            
                    ), $atts));

    $i = '<div class="nimbus_image ' . $type . ' image_' . $align . '" >' . do_shortcode($content) . '</div>';
    return $i;
}

/* * *************************************************************************************************** */
// Table Styles
/* * *************************************************************************************************** */

add_shortcode('nimbus_table', 'nimbus_table');

function nimbus_table($atts, $content = null) {
    extract(shortcode_atts(array(
                'type' => 'minimal'
                    ), $atts));

    $i = '<div class="nimbus_table_' . $type . ' " >' . do_shortcode($content) . '</div>';
    return $i;
}

/* * *************************************************************************************************** */
// Info Box
/* * *************************************************************************************************** */

add_shortcode('nimbus_info_box', 'nimbus_info_box');

function nimbus_info_box($atts, $content = null) {
    extract(shortcode_atts(array(
                'top_color' => 'ffffff',
                'mid_color' => 'f3f3f3',
                'bottom_color' => 'ebebeb',
                'font_color' => '535353',
                'border_color' => 'd8d8d8'
                    ), $atts));

    $i = '<div class="info_box" style="width:100%; color:#' . $font_color . '; border:1px solid #' . $border_color . '; padding:20px 0; background: #' . $top_color . '; background: -moz-linear-gradient(top,  #' . $top_color . ' 0%, #' . $mid_color . ' 3%, #' . $bottom_color . ' 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#' . $top_color . '), color-stop(3%,#' . $mid_color . '), color-stop(100%,#' . $bottom_color . ')); background: -webkit-linear-gradient(top,  #' . $top_color . ' 0%,#' . $mid_color . ' 3%,#' . $bottom_color . ' 100%); background: -o-linear-gradient(top,  #' . $top_color . ' 0%,#' . $mid_color . ' 3%,#' . $bottom_color . ' 100%); background: -ms-linear-gradient(top,  #' . $top_color . ' 0%,#' . $mid_color . ' 3%,#' . $bottom_color . ' 100%); background: linear-gradient(top,  #' . $top_color . ' 0%,#' . $mid_color . ' 3%,#' . $bottom_color . ' 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#' . $top_color . '\', endColorstr=\'#' . $bottom_color . '\',GradientType=0 ); " >' . do_shortcode(wptexturize(wpautop($content))) . '</div>';
    return $i;
}

/* * *************************************************************************************************** */
// Divider Styles
/* * *************************************************************************************************** */

// single

add_shortcode('nimbus_single_divider', 'nimbus_single_divider');

function nimbus_single_divider($atts, $content = null) {
    extract(shortcode_atts(array(
                'type' => 'solid',
                'color' => '9e9e9e',
                'margin' => '20px'
                    ), $atts));

    $i = '<div style="width:100%; border-top:1px ' . $type . ' #' . $color . '; margin:' . $margin . ' 0px;" ></div>';
    return $i;
}

// double

add_shortcode('nimbus_double_divider', 'nimbus_double_divider');

function nimbus_double_divider($atts, $content = null) {
    extract(shortcode_atts(array(
                'top_type' => 'dashed',
                'top_color' => '002248',
                'bottom_type' => 'dashed',
                'bottom_color' => '0f77ee',
                'margin' => '20px'
                    ), $atts));

    $i = '<div style="width:100%; border-top:1px ' . $top_type . ' #' . $top_color . '; border-bottom:1px ' . $bottom_type . ' #' . $bottom_color . '; margin:' . $margin . ' 0px;" ></div>';
    return $i;
}

// solid

add_shortcode('nimbus_solid_divider', 'nimbus_solid_divider');

function nimbus_solid_divider($atts, $content = null) {
    extract(shortcode_atts(array(
                'height' => '5px',
                'color' => 'b7b7b7',
                'margin' => '20px'
                    ), $atts));

    $i = '<div style="width:100%; height:' . $height . '; background:#' . $color . '; margin:' . $margin . ' 0px;" ></div>';
    return $i;
}

// fancy grad gray

add_shortcode('nimbus_gray_grad_divider', 'nimbus_gray_grad_divider');

function nimbus_gray_grad_divider($atts, $content = null) {
    extract(shortcode_atts(array(
                'margin' => '20px'
                    ), $atts));

    $i = '<img style=" width:100%; margin:' . $margin . ' 0px;" src="' . get_template_directory_uri() . '/shortcodes/images/large_gray_grad_div.png" alt="Divider" />';
    return $i;
}

// fancy grad white

add_shortcode('nimbus_white_grad_divider', 'nimbus_white_grad_divider');

function nimbus_white_grad_divider($atts, $content = null) {
    extract(shortcode_atts(array(
                'margin' => '20px'
                    ), $atts));

    $i = '<img style=" width:100%; margin:' . $margin . ' 0px;" src="' . get_template_directory_uri() . '/shortcodes/images/large_white_grad_div.png" alt="Divider" />';
    return $i;
}

/* * *************************************************************************************************** */
// jQuery Accordion
/* * *************************************************************************************************** */

// Accordion

add_shortcode('nimbus_accordion', 'nimbus_accordion');

function nimbus_accordion($atts, $content = null) {
    extract(shortcode_atts(array(
                'label' => ''
                    ), $atts));

    global $NIMBUS_ACCORDION_ARR;
    $NIMBUS_ACCORDION_ARR[] = array('label' => $label, 'content' => wptexturize(wpautop($content)));
}

// Accordions

add_shortcode('nimbus_accordions', 'nimbus_accordions');

function nimbus_accordions($atts, $content = null) {
    extract(shortcode_atts(array(
                'open' => 'false'
                    ), $atts));

    global $NIMBUS_ACCORDION_ARR;
    $id = nimbus_rand_string(12);
    $j = 1;
    $NIMBUS_ACCORDION_ARR = array();
    do_shortcode($content);
    $i .= '<script type="text/javascript">jQuery(function() { jQuery("#' . $id . '").tabs("#' . $id . ' div.pane", {tabs: "h2", effect: "slide", initialIndex: null}); }); </script>';
    $i .= '<div id="' . $id . '" class="accordion">';
    foreach ($NIMBUS_ACCORDION_ARR as $key => $accordion) {
        if ($j == 1) {
            if ($open == "true") {
                $label_class = "class='current'";
                $pane_style = "style='display:block'";
            } else {
                $label_class = "";
                $pane_style = "";
            }
        } else {
            $label_class = "";
            $pane_style = "";
        }
        $i .= '<h2 ' . $label_class . '>' . $accordion['label'] . '</h2>';
        $i .= '<div class="pane" ' . $pane_style . '>' . $accordion['content'] . '</div>';
        $j++;
    }
    $i .= '</div>';

    return $i;
}

/* * *************************************************************************************************** */
// Show More
/* * *************************************************************************************************** */

add_shortcode('nimbus_show_more', 'nimbus_show_more');

function nimbus_show_more($atts, $content = null) {
    extract(shortcode_atts(array(
                'label' => '',
                'open' => 'false'
                    ), $atts));
    $id = nimbus_rand_string(12);
    $i = '<script type="text/javascript"> jQuery(document).ready(function(){';
    if (trim($open) == "false") {
        $i .= 'jQuery(".more_content_' . $id . '").hide();';
    }
    $i .= 'jQuery(".more_title_' . $id . '").click(function(){';
    $i .= 'jQuery(this).next(".more_content_' . $id . '").slideToggle(250);';
    $i .= '}); }); </script>';
    $i .= '<h2 class="hide_show_title more_title_' . $id . '"><span>' . $label . '</span></h2>';
    $i .= '<div class="hide_show_content more_content_' . $id . '">' . $content . '</div>';

    return $i;
}

/* * *************************************************************************************************** */
// Google Charts
/* * *************************************************************************************************** */

// Google Charts Planned


/* * *************************************************************************************************** */
// Adsense Ads From Panel
/* * *************************************************************************************************** */

// Adsense Ads From Panel Planned


/* * *************************************************************************************************** */
// Related Posts By Tag
/* * *************************************************************************************************** */

// Related Posts By Tag Planned


/* * *************************************************************************************************** */
// Contact Form
/* * *************************************************************************************************** */

// Contact Form Planned


/* * *************************************************************************************************** */
// Paypal Donation
/* * *************************************************************************************************** */

add_shortcode('nimbus_pp_donate', 'nimbus_pp_donate');

function nimbus_pp_donate($atts, $content = null) {
    extract(shortcode_atts(array(
                'email' => 'evan@sheamediaco.com',
                'name' => 'Nimbus Themes'
                    ), $atts));

    $i = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">';
    $i .= '<input type="hidden" name="cmd" value="_donations" />';
    $i .= '<input type="hidden" name="business" value="' . $email . '" />';
    $i .= '<input type="hidden" name="lc" value="US" />';
    $i .= '<input type="hidden" name="item_name" value="' . $name . '" />';
    $i .= '<input type="hidden" name="no_note" value="0" />';
    $i .= '<input type="hidden" name="currency_code" value="USD" />';
    $i .= '<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_LG.gif:NonHostedGuest" />';
    $i .= '<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" />';
    $i .= '<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" />';
    $i .= '</form>';
    return $i;
}

/******************************************************************************************************/
// RSS Parser
/******************************************************************************************************/

// RSS Parser




