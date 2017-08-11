<?php
/* **************************************************************************************************** */
// Define Paths
/* **************************************************************************************************** */

$get_wp_version = get_bloginfo('version');
$theme_data = wp_get_theme();
define('OPTIONS_PATH', get_template_directory_uri() . '/nimbus/');
define('JS_PATH', get_template_directory() . '/js/');
define('CSS_PATH', get_template_directory() . '/css/');
define('THEME_NAME', $theme_data['Name']);
define('THEME_OPTIONS', str_replace(" ", "_", strtolower(THEME_NAME)) . "_options");
define( 'NIMBUS_UPDATE_XML_FILE', 'http://nimbusupdate.s3.amazonaws.com/simple.xml' ); 
define( 'NIMBUS_UPDATE_CACHE_INTERVAL', 5000 );

/* **************************************************************************************************** */
// Load Admin Panel
/* **************************************************************************************************** */

require_once(get_template_directory() . '/nimbus/options.php');
require_once(get_template_directory() . '/nimbus/options_arr.php');


/* **************************************************************************************************** */
// Do Update Notification
/* **************************************************************************************************** */

// Adds an Admin Notice if out of date

function nimbus_update_alert() {
	if (function_exists('simplexml_load_string')) { 
		$nimbus_update_xml = nimbus_check_for_update(NIMBUS_UPDATE_CACHE_INTERVAL); 
		$nimbus_update_theme_data = wp_get_theme(); 
		if( str_replace(".", "", $nimbus_update_xml->latest) > str_replace(".", "", $nimbus_update_theme_data->Version)) {
        ?>
            <div class="error nimbus_update" style="background: #e9f7ff url('<?php echo OPTIONS_PATH; ?>images/nimbus-support.jpg') no-repeat top left; padding: 120px 20px 20px; background-color: #e9f7ff; border-color: #dfdfdf;">
            <p><?php _e( 'A new version of the <strong>' . THEME_NAME . ' Theme</strong> is available. The new version may contain security and appearance updates. Please <a target="_blank" href="http://www.nimbusthemes.com/">log into your Nimbus Themes Account</a> to download and install version <strong>' . $nimbus_update_xml->latest . '</strong> now.', 'nimbus' ); ?></p>
            </div>
		<?php        
		}
	}
}
add_action( 'admin_notices', 'nimbus_update_alert', 1000 );


// Check for updated version

function nimbus_check_for_update($interval) {
	$nimbus_update_cache = 'nimbus-update-cache';
	$nimbus_update_cache_last = 'nimbus-update-cache-last';
	$nimbus_last_update = get_option( $nimbus_update_cache_last );
	$nimbus_now = time();
	if ( !$nimbus_last_update || (( $nimbus_now - $nimbus_last_update ) > $interval) ) {
		if( function_exists('curl_init') ) { 
			$ch = curl_init(NIMBUS_UPDATE_XML_FILE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$cache = curl_exec($ch);
			curl_close($ch);
		} else {
			$cache = file_get_contents(NIMBUS_UPDATE_XML_FILE); 
		}
		if ($cache) {				
			update_option( $nimbus_update_cache, $cache );
			update_option( $nimbus_update_cache_last, time() );
		} 
		$nimbus_update_data = get_option( $nimbus_update_cache );
	} else {
		$nimbus_update_data = get_option( $nimbus_update_cache );
	}
	if( strpos((string)$nimbus_update_data, '<update>') === false ) {
		$nimbus_update_data = '<?xml version="1.0" encoding="UTF-8"?><update><latest>1.0</latest></update>';
	}
    
	$nimbus_update_xml = simplexml_load_string($nimbus_update_data); 

	return $nimbus_update_xml;
}




/* **************************************************************************************************** */
// Flush Rewrite on Activation
/* **************************************************************************************************** */

function nimbus_rewrite_flush() {
    flush_rewrite_rules();
}

add_action('after_switch_theme', 'nimbus_rewrite_flush');


/* **************************************************************************************************** */
// Setup Theme
/* **************************************************************************************************** */

add_action('after_setup_theme', 'nimbus_setup');

if (!function_exists('nimbus_setup')):

    function nimbus_setup() {


       // Localization
        
        $lang_local = get_template_directory() . '/lang';
        load_theme_textdomain('nimbus', $lang_local);

        // Register Thumbnail Sizes

        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(1170, 9999, true);
        add_image_size('nimbus_270_170', 270, 170, true);
        add_image_size('nimbus_740_420', 740, 420, true);
        add_image_size('nimbus_105_90', 105, 90, true);
        add_image_size('nimbus_1140_420', 1140, 420, true);
        add_image_size('nimbus_1130_410', 1130, 410, true);

        // Load feed links	

        add_theme_support('automatic-feed-links');
        
        // WooCommerce Support
        
        add_theme_support( 'woocommerce' );
        
        // Support Custom Background
        
        $nimbus_custom_background_defaults = array(
            'default-color' => 'ffffff'
        );
        add_theme_support( 'custom-background', $nimbus_custom_background_defaults );        

        // Register Menus

        if (function_exists('register_nav_menu')) {
            register_nav_menu('primary', __('Primary Menu', 'nimbus'));
        }
    }

endif;


/* **************************************************************************************************** */
// Set posts per page
/* **************************************************************************************************** */

add_action( 'pre_get_posts', 'nimbus_posts_per_page', 1 );

function nimbus_posts_per_page( $query ) {

    if ( is_admin() || ! $query->is_main_query() )
        return;

    if ( is_home() || is_archive() || is_author() || is_search() ) {
        $query->set( 'posts_per_page', nimbus_get_option('posts_on_blog') );
        return;
    }

}


/* **************************************************************************************************** */
// Check if pagination is needed
/* **************************************************************************************************** */

function nimbus_show_pagination() {
    global $wp_query;
    return ($wp_query->max_num_pages > 1);
}


/* **************************************************************************************************** */
// Modify Search Form
/* **************************************************************************************************** */

function nimbus_modify_search_form($form) {
    $form = '<form method="get" id="searchform" action="' . home_url() . '/" >';
    if (is_search()) {
        $form .='<input type="text" value="' . esc_attr(apply_filters('the_search_query', get_search_query())) . '" name="s" id="s" />';
    } else {
        $form .='<input type="text" value="Search" name="s" id="s"  onfocus="if(this.value==this.defaultValue)this.value=\'\';" onblur="if(this.value==\'\')this.value=this.defaultValue;"/>';
    }
    $form .= '<input type="image" id="searchsubmit" src="' . get_stylesheet_directory_uri() . '/images/search_icon.png" />
            </form>';
    return $form;
}
add_filter('get_search_form', 'nimbus_modify_search_form');


/* **************************************************************************************************** */
// Override gallery style
/* **************************************************************************************************** */

add_filter( 'use_default_gallery_style', '__return_false' );


/* **************************************************************************************************** */
// Set Content Width
/* **************************************************************************************************** */

if (!isset($content_width))
    $content_width = 770;
    
/* **************************************************************************************************** */
// Upload Mimes
/* **************************************************************************************************** */

require_once(get_template_directory() . '/nimbus/mime/mime.php');    

/* **************************************************************************************************** */
// Register Sidebars
/* **************************************************************************************************** */

add_action('widgets_init', 'nimbus_register_sidebars');

function nimbus_register_sidebars() {

    register_sidebar(array(
        'name' => __('Default Page Sidebar', 'nimbus'),
        'id' => 'sidebar_pages',
        'description' => __('Widgets in this area will be displayed in the sidebar on the pages.', 'nimbus'),
        'before_widget' => '<div id="%1$s" class="widget %2$s widget sidebar_editable">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget_title">',
        'after_title' => '</h3>'
    ));

    register_sidebar(array(
        'name' => __('Default Blog Sidebar', 'nimbus'),
        'id' => 'sidebar_blog',
        'description' => __('Widgets in this area will be displayed in the sidebar on the blog, archives, and the frontpage if the frontpage is set to show latest posts.', 'nimbus'),
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget_title">',
        'after_title' => '</h3>'
    ));

    register_sidebar(array(
        'name' => __('Footer Left', 'nimbus'),
        'id' => 'footer-left',
        'description' => __('Widgets in this area will be shown in the left footer column.', 'nimbus'),
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget_title">',
        'after_title' => '</h3>'
    ));

    register_sidebar(array(
        'name' => __('Footer Center', 'nimbus'),
        'id' => 'footer-center',
        'description' => __('Widgets in this area will be shown in the center footer column.', 'nimbus'),
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget_title">',
        'after_title' => '</h3>'
    ));

    register_sidebar(array(
        'name' => __('Footer Right', 'nimbus'),
        'id' => 'footer-right',
        'description' => __('Widgets in this area will be shown in the right footer column.', 'nimbus'),
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget_title">',
        'after_title' => '</h3>'
    ));

    register_sidebar(array(
        'name' => __('Frontpage Sidebar', 'nimbus'),
        'id' => 'sidebar_fp',
        'description' => __('Widgets in this area will be shown on the side of the frontpage content area.', 'nimbus'),
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget_title">',
        'after_title' => '</h3>'
    ));

    
    // create 50 alternate sidebar widget areas for use on post and pages 
    $i = 1;
    while ($i <= 50) {
        register_sidebar(array(
            'name' => __('Alternate Sidebar #', 'nimbus') . $i,
            'id' => 'sidebar_' . $i,
            'description' => __('Widgets in this area will be displayed in the sidebar for any posts, pages or portfolio items that are taged with sidebar', 'nimbus') . $i . '.',
            'before_widget' => '<div class="widget">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget_title">',
            'after_title' => '</h3>'
        ));
        $i++;
    }
}

/* **************************************************************************************************** */
// Excerpt Modifications
/* **************************************************************************************************** */

// Excerpt Length

add_filter('excerpt_length', 'nimbus_excerpt_length');

function nimbus_excerpt_length($length) {
    return 40;
}

// Excerpt More

add_filter('excerpt_more', 'nimbus_excerpt_more');

function nimbus_excerpt_more($more) {
    return '';
}

// Add to pages

add_action('init', 'nimbus_add_excerpts_to_pages');

function nimbus_add_excerpts_to_pages() {
    add_post_type_support('page', 'excerpt');
}

/* **************************************************************************************************** */
// Enable Threaded Comments
/* **************************************************************************************************** */

add_action('wp_enqueue_scripts', 'nimbus_threaded_comments');

function nimbus_threaded_comments() {
    if (is_singular() && comments_open() && (get_option('thread_comments') == 1)) {
        wp_enqueue_script('comment-reply');
    }
}

/* **************************************************************************************************** */
// Modify Comments Output
/* **************************************************************************************************** */

function nimbus_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li <?php comment_class(); ?> id="li_comment_<?php comment_ID() ?>">
        <div id="comment_<?php comment_ID(); ?>" class="comment_wrap clearfix">
    <?php echo get_avatar($comment, $size = '75'); ?>
            <div class="comment_content">
                <p class="left"><strong><?php comment_author_link(); ?></strong><br />
                <?php echo(get_comment_date()) ?> <?php edit_comment_link(__('(Edit)', 'nimbus'), '  ', '') ?></p>
                <p class="right"><?php comment_reply_link(array_merge($args, array('reply_text' => __('Leave a Reply', 'nimbus'), 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?></p>
                <div class="clear"></div>
    <?php if ($comment->comment_approved == '0') : ?>
                    <em><?php _e('Your comment is awaiting moderation.', 'nimbus') ?></em>
    <?php endif; ?>
        <?php comment_text() ?>
            </div>
            <div class="clear"></div>
        </div> 

        <?php
    }

/* **************************************************************************************************** */
// Modify Ping Output
/* **************************************************************************************************** */

    function nimbus_ping($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        ?>
        <li id="comment_<?php comment_ID(); ?>"><?php comment_author_link(); ?> - <?php comment_excerpt(); ?> 
    <?php
    }

/* **************************************************************************************************** */
// Modify Comment Text Fields
/* **************************************************************************************************** */

    add_filter('comment_form_default_fields', 'nimbus_comment_fields');

    function nimbus_comment_fields($fields) {

        $commenter = wp_get_current_commenter();
        $req = get_option('require_name_email');
        $aria_req = ( $req ? " aria-required='true'" : '' );

        $fields['author'] = '<div class="row"><div class="col-md-4 comment_fields"><p class="comment-form-author">' . '<label for="author">' . __('Name', 'nimbus') . '</label> ' . ( $req ? '<span class="required">*</span><br />' : '' ) . '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>';
        $fields['email'] = '<p class="comment-form-email"><label for="email">' . __('Email', 'nimbus') . '</label> ' . ( $req ? '<span class="required">*</span><br />' : '' ) . '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></p>';
        $fields['url'] = '<p class="comment-form-url"><label for="url">' . __('Website', 'nimbus') . '</label><br />' . '<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p></div> ';

        return $fields;
    }
    
    
/* **************************************************************************************************** */
// Modify Avatar Classes
/* **************************************************************************************************** */
    
add_filter('get_avatar','nimbus_avatar_class');

function nimbus_avatar_class($class) {
    $class = str_replace("class='avatar", "class='avatar img-responsive", $class) ;
    return $class;
}

/* **************************************************************************************************** */
// Add Post Link Classes
/* **************************************************************************************************** */

add_filter('next_post_link', 'nimbus_posts_link_next_class');

function nimbus_posts_link_next_class($format){
     $format = str_replace('href=', 'class="post_next btn" href=', $format);
     return $format;
}

add_filter('previous_post_link', 'nimbus_posts_link_prev_class');

function nimbus_posts_link_prev_class($format) {
     $format = str_replace('href=', 'class="post_prev btn" href=', $format);
     return $format;
}


/* **************************************************************************************************** */
// Add next_posts Link Classes
/* **************************************************************************************************** */

add_filter('next_posts_link_attributes', 'nimbus_posts_link_class');
add_filter('previous_posts_link_attributes', 'nimbus_posts_link_class');

function nimbus_posts_link_class() {
    return 'class="btn"';
}


/* **************************************************************************************************** */
// Add Image Classes ##Look for way to apply to exsisting
/* **************************************************************************************************** */

function nimbus_add_image_class($class){
    $class .= ' img-responsive';
    return $class;
}
add_filter('get_image_tag_class','nimbus_add_image_class');


/* **************************************************************************************************** */
// Load Widgets
/* **************************************************************************************************** */

    if (get_bloginfo('version') >= 2.8) {

        foreach (glob(get_template_directory() . '/widgets/*.php') as $widget) {

            include($widget);
        }
    }


/* **************************************************************************************************** */
// Load Shortcodes
/* **************************************************************************************************** */

    require_once(get_template_directory() . '/shortcodes/shortcodes.php');


/* **************************************************************************************************** */
// Create SEO Box
/* **************************************************************************************************** */

if (nimbus_get_option('nimbus_seo_off') == 0) {
    add_action("admin_init", "nimbus_seo_meta_box");
}

function nimbus_seo_meta_box() {

    if (nimbus_get_option('page_switch_seo') == 1) {
        add_meta_box("seo_meta_box", __('Nimbus SEO Panel', 'nimbus'), "nimbus_call_seo_meta_box", "page", "normal", "high");
    }
    if (nimbus_get_option('post_switch_seo') == 1) {
        add_meta_box("seo_meta_box", __('Nimbus SEO Panel', 'nimbus'), "nimbus_call_seo_meta_box", "post", "normal", "high");
    }
}

function nimbus_call_seo_meta_box() {

    global $post;
    $custom = get_post_custom($post->ID);

    if (isset($custom["seo_title"])) {
        $seo_title = $custom["seo_title"][0];
    } else {
        $seo_title = "";
    }
    if (isset($custom["seo_keywords"])) {
        $seo_keywords = $custom["seo_keywords"][0];
    } else {
        $seo_keywords = "";
    }
    if (isset($custom["seo_description"])) {
        $seo_description = $custom["seo_description"][0];
    } else {
        $seo_description = "";
    }

    echo '<input type="hidden" name="meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    ?>  

    <p><?php _e('Fill in the fields below to optimize your post or page for search engines.', 'nimbus') ?></p>
    <table style="width:100%;">
        <tr><td style="width:15%;"><label><?php _e('Title:', 'nimbus') ?></label></td><td><input style="width:55%;" name="seo_title" value="<?php echo $seo_title; ?>" size="20" /></td></tr> 
        <tr><td style="width:15%;"><label><?php _e('Keywords:', 'nimbus') ?></label></td><td><input style="width:55%;" name="seo_keywords" value="<?php echo $seo_keywords; ?>" size="20"  /></td></tr>  
        <tr><td style="width:15%;"><label><?php _e('Description:', 'nimbus') ?></label></td><td><div id="seo_description_wrap"><textarea style="width:80%; min-height:100px;" id="seo_description" name="seo_description" cols="30" rows="2"  /><?php echo $seo_description; ?></textarea></div></td></tr> 
    </table>
    <?php
}

/* **************************************************************************************************** */
// Save SEO Box
/* **************************************************************************************************** */


    add_action('save_post', 'nimbus_save_seo');

    function nimbus_save_seo($post_id) {

        global $post;

        // verify nonce
        if (isset($_POST['meta_box_nonce'])) {
            if (!wp_verify_nonce($_POST['meta_box_nonce'], basename(__FILE__))) {
                return $post_id;
            }
        }





        // check autosave

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // check permissions
        if (isset($_POST['post_type'])) {

            if ('page' == $_POST['post_type']) {
                if (!current_user_can('edit_page', $post_id)) {
                    return $post_id;
                }
            } elseif (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }


        }

        if (isset($_POST['seo_title'])) {
            update_post_meta($post_id, 'seo_title', $_POST['seo_title']);
        }
        if (isset($_POST['seo_keywords'])) {
            update_post_meta($post_id, 'seo_keywords', $_POST['seo_keywords']);
        }
        if (isset($_POST['seo_description'])) {
            update_post_meta($post_id, 'seo_description', $_POST['seo_description']);
        }
    }

/* **************************************************************************************************** */
// Process SEO Title
/* **************************************************************************************************** */

if (nimbus_get_option('nimbus_seo_off') == 0) {
    add_action('wp_title', 'nimbus_seo_title');
}    
    
function nimbus_seo_title() {

    global $wp_query;
    $title = get_bloginfo('name');
    $seporate = ' | ';
    if (is_front_page()) {
        $title = nimbus_get_option('frontpage_title');  
    } else if (is_page() || is_single()) {
        $postid = $wp_query->post->ID;
        $i = get_post_meta($postid, 'seo_title', true);
        if ($i == "") {
            if (nimbus_get_option('default_title_config') == "post-site") {
                $title = the_title('','',false) . $seporate . get_bloginfo('name');
            }
            if (nimbus_get_option('default_title_config') == "site-post") {
                $title = get_bloginfo('name') . $seporate . the_title('','',false);
            }
            if (nimbus_get_option('default_title_config') == "site") {
                $title = get_bloginfo('name');
            }
            if (nimbus_get_option('default_title_config') == "post") {
                $title = the_title();
            }
        } else {
            $title = $i;
        } 
    }
    wp_reset_query();
    return $title;
}

/* **************************************************************************************************** */
// Process SEO Description
/* **************************************************************************************************** */

if (nimbus_get_option('nimbus_seo_off') == 0) {
    add_action('wp_head', 'nimbus_seo_description');
}

function nimbus_seo_description() {

    global $wp_query;


    echo "<!-- SEO settings from " . THEME_NAME . ". --> \n";
    if (is_front_page()) {
        echo "<meta name='description' content='" . nimbus_get_option('frontpage_description') . "' />\n";
    } else if (is_page() || is_single()) {
        $postid = $wp_query->post->ID;
        $i = get_post_meta($postid, 'seo_description', true);
        if ($i == "") {
            echo "";
        } else {
            echo "<meta name='description' content='" . $i . "' />\n";


        } 
    }
    wp_reset_query();
}

/* **************************************************************************************************** */
// Process SEO Keywords
/* **************************************************************************************************** */

if (nimbus_get_option('nimbus_seo_off') == 0) {
    add_action('wp_head', 'nimbus_seo_keywords');
}

function nimbus_seo_keywords() {

    global $wp_query;

    if (is_front_page()) {
        echo "<meta name='keywords' content='" . nimbus_get_option('frontpage_keywords') . "' />\n";
    } else if (is_page() || is_single()) {
        $postid = $wp_query->post->ID;
        $i = get_post_meta($postid, 'seo_keywords', true);
        if ($i == "") {
            echo "";
        } else {
            echo "<meta name='keywords' content='" . $i . "' />\n";
        }

    }
    wp_reset_query();
}

/* **************************************************************************************************** */
// Process SEO Canonicalization
/* **************************************************************************************************** */

if (nimbus_get_option('nimbus_seo_off') == 0) {
    add_action('wp_head', 'nimbus_seo_canonicalization');
}

function nimbus_seo_canonicalization() {

    global $wp_query;

    if (nimbus_get_option('canonical') == 1) {
        if (is_front_page()) {
            echo "<link rel='canonical' href='" . home_url() . "' />\n";
        } else if (is_page() || is_single()) {
            echo "<link rel='canonical' href='" . get_permalink() . "' />\n";
        } else if (is_category() || is_archive()) {
            echo "<link rel='canonical' href='" . get_category_link(get_query_var('cat')) . "'/>\n";
        } else if (is_tag()) {
            $tag_slug = get_term_by('slug', get_query_var('tag'), 'post_tag');
            echo "<link rel='canonical' href='" . get_tag_link($tag_slug->term_id) . "'/>\n";
        } else if (is_author()) {
            $author = get_userdata(get_query_var('author'));
            echo "<link rel='canonical' href='" . get_author_posts_url(false, $author->ID, $author->user_nicename) . "'/>\n";
        } else if (is_day()) {
            echo "<link rel='canonical' href='" . get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day')) . "'/>\n";
        } else if (is_month()) {
            echo "<link rel='canonical' href='" . get_month_link(get_query_var('year'), get_query_var('monthnum')) . "'/>\n";
        } else if (is_year()) {
            echo "<link rel='canonical' href='" . get_year_link(get_query_var('year')) . "'/>\n";
        }
    }
    wp_reset_query();
}

/* **************************************************************************************************** */
// Register Featured Image Options Box
/* **************************************************************************************************** */

add_action("admin_init", "nimbus_featured_image_options_meta_box");

function nimbus_featured_image_options_meta_box() {

    add_meta_box("featured_image_options_meta_box", __('Nimbus Featured Image Options', 'nimbus'), "nimbus_call_featured_image_options_meta_box", "page", "side", "high");
    add_meta_box("featured_image_options_meta_box", __('Nimbus Featured Image Options', 'nimbus'), "nimbus_call_featured_image_options_meta_box", "post", "side", "high");
}

function nimbus_call_featured_image_options_meta_box() {

    global $post, $wp_query;

    $custom = get_post_custom($post->ID);
    if (isset($custom["on_page_checked"])) {
        $on_page_checked = $custom["on_page_checked"][0];
    } 
    if ((nimbus_get_option('nimbus_banner_option') == "slideshow")) {
        if (isset($custom["ss_checked"])) {
            $ss_checked = $custom["ss_checked"][0];
        }
    }


    echo '<input type="hidden" name="meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';


    if (( get_option('page_on_front') ==  $post->ID )) { 
    ?>
        <p><?php _e('There are no image options because this page is set as the Front Page', 'nimbus') ?></p>
    <?php 
    } else {
    ?>        


        <p><?php _e('Remember you need to attach a Featured Image for these setting to take effect.', 'nimbus') ?></p>
        
        <table>
                <tr><td><label><input type="checkbox" name="on_page_checked" value="true" <?php if (( get_post_meta($post->ID, 'include_image_on_page', true) == "true" ) || ( get_post_status ( $post->ID ) == 'auto-draft' )) { ?> checked <?php } ?> /></label></td><td>Include Image at the Top of the Page</td></tr>          
                <tr><td><label><input type="checkbox" name="ss_checked" value="true" <?php if (get_post_meta($post->ID, 'include_in_slideshow', true) == "true") { ?> checked <?php } ?> /></label></td><td>Include in Slideshow</td></tr>  
        </table>


    <?php } 

}

/* **************************************************************************************************** */
// Save Featured Image Options Box
/* **************************************************************************************************** */


add_action('save_post', 'nimbus_save_featured_image_options_meta_box');

function nimbus_save_featured_image_options_meta_box($post_id) {

    global $post;

    // verify nonce

    if (isset($_POST['meta_box_nonce'])) {
        if (!wp_verify_nonce($_POST['meta_box_nonce'], basename(__FILE__))) {
            return $post_id;
        }
    }

    // check autosave

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if (isset($_POST['post_type'])) {

        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    }

    if (isset($_POST['ss_checked'])) {
        update_post_meta($post_id, 'include_in_slideshow', $_POST['ss_checked']);
    } else {
        delete_post_meta($post_id, 'include_in_slideshow');
    }

    if (isset($_POST['on_page_checked'])) {
        update_post_meta($post_id, 'include_image_on_page', $_POST['on_page_checked']);
    } else {
        delete_post_meta($post_id, 'include_image_on_page');
    }
}



/* **************************************************************************************************** */
// Register Featured Post Meta Box
/* **************************************************************************************************** */

add_action("admin_init", "nimbus_featured_post_options_meta_box");

function nimbus_featured_post_options_meta_box() {
    add_meta_box("featured_post_options_meta_box", __('Nimbus Featured Post Options', 'nimbus'), "nimbus_call_featured_post_options_meta_box", "post", "side", "high");
}

function nimbus_call_featured_post_options_meta_box() {

    global $post, $wp_query;
    
    $custom = get_post_custom($post->ID);
    if (isset($custom["featured_post"])) {
        $featured_post = $custom["featured_post"][0];
    }



    echo '<input type="hidden" name="meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    ?>
        <p><?php _e('Setting this post as featured will cause it to display on the frontpage in the featured post location. This will only take effect if this is the most recently published featured post.', 'nimbus') ?></p>
        <table>
                <tr><td><label><input type="checkbox" name="featured_post" value="true" <?php if (get_post_meta($post->ID, 'featured_post', true) == "true") { ?> checked <?php } ?> /></label></td><td>Set as Featured Post</td></tr> 
        </table>
    <?php  

}

/* **************************************************************************************************** */
// Save Featured Post Meta Box
/* **************************************************************************************************** */


add_action('save_post', 'nimbus_save_featured_post_options_meta_box');

function nimbus_save_featured_post_options_meta_box($post_id) {

    global $post;

    // verify nonce

    if (isset($_POST['meta_box_nonce'])) {
        if (!wp_verify_nonce($_POST['meta_box_nonce'], basename(__FILE__))) {
            return $post_id;
        }
    }

    // check autosave

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if (isset($_POST['post_type'])) {

        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    }

    if (isset($_POST['featured_post'])) {
        update_post_meta($post_id, 'featured_post', $_POST['featured_post']);
    } else {
        delete_post_meta($post_id, 'featured_post');
    }
}


/* **************************************************************************************************** */
// Register Banner Content Metabox
/* **************************************************************************************************** */

add_action('add_meta_boxes', 'nimbus_banner_meta_box');

function nimbus_banner_meta_box() {
    global $post;
    $unique_template = get_post_meta($post->ID, '_wp_page_template', TRUE);

    if (( get_option('page_on_front') ==  $post->ID ) || ($unique_template == 'alt_frontpage.php')) {
        add_meta_box("banner_meta_box", __('Nimbus Frontpage Banner Panel', 'nimbus'), "nimbus_call_banner_meta_box", "page", "normal", "high");
    }
}

function nimbus_call_banner_meta_box($post) {

    wp_nonce_field(plugin_basename(__FILE__), 'banner_meta_box');

    if (get_post_meta($post->ID, 'frontpage_banner_content', true)) {
        $value = get_post_meta($post->ID, 'frontpage_banner_content', false);
    } else {
        $value[0] = '';
    }

    wp_editor($value[0], 'frontpage_banner_content');
}

/* **************************************************************************************************** */
// Save Banner Content Metabox
/* **************************************************************************************************** */

add_action('save_post', 'nimbus_banner_save_postdata');

function nimbus_banner_save_postdata($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (( isset($_POST['banner_meta_box']) ) && (!wp_verify_nonce($_POST['banner_meta_box'], plugin_basename(__FILE__)) ))
        return;

    if (( isset($_POST['post_type']) ) && ( 'page' == $_POST['post_type'] )) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    if (isset($_POST['frontpage_banner_content'])) {
        update_post_meta($post_id, 'frontpage_banner_content', $_POST['frontpage_banner_content']);
    }
}

/* **************************************************************************************************** */
// Register Sidebar Select Box
/* **************************************************************************************************** */

add_action("admin_init", "nimbus_sidebar_meta_box");

function nimbus_sidebar_meta_box() {

    add_meta_box("sidebar_meta_box", __('Nimbus Sidebar Options', 'nimbus'), "nimbus_call_sidebar_meta_box", "page", "side", "high");
    add_meta_box("sidebar_meta_box", __('Nimbus Sidebar Options', 'nimbus'), "nimbus_call_sidebar_meta_box", "post", "side", "high");
}

function nimbus_call_sidebar_meta_box() {

    global $post;

    $custom = get_post_custom($post->ID);
    if (isset($custom["alt_sidebar_select"])) {
        $alt_sidebar_select = $custom["alt_sidebar_select"][0];
    } else {
        $alt_sidebar_select = "";
    }
    echo '<input type="hidden" name="meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    ?>  

    <p><?php __('Enter the number of the alternate sidebar you would like to apply. Leave blank to use default.', 'nimbus') ?></p>
    <table>
        <tr><td><label><?php _e('Sidebar #', 'nimbus') ?></label></td><td><input type="text" name="alt_sidebar_select" style="width:35px;" value="<?php echo $alt_sidebar_select; ?>" size="20" maxlength="2" /></td></tr> 
    </table>
    <?php
}

/* **************************************************************************************************** */
// Save Sidebar Select Box
/* **************************************************************************************************** */

add_action('save_post', 'nimbus_save_sidebar_meta_box');

function nimbus_save_sidebar_meta_box($post_id) {

    global $post;

    // verify nonce

    if (isset($_POST['meta_box_nonce'])) {
        if (!wp_verify_nonce($_POST['meta_box_nonce'], basename(__FILE__))) {
            return $post_id;
        }
    }

    // check autosave

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if (isset($_POST['post_type'])) {
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    }

    if (isset($_POST['alt_sidebar_select'])) {
        update_post_meta($post_id, 'alt_sidebar_select', $_POST['alt_sidebar_select']);
    }
}

/* **************************************************************************************************** */
// Register Alt Frontpage Template Setting
/* **************************************************************************************************** */

add_action('add_meta_boxes', 'nimbus_alt_front_meta_box');

function nimbus_alt_front_meta_box() {
    global $post;
    $unique_template = get_post_meta($post->ID, '_wp_page_template', TRUE);
    if ($unique_template == 'alt_frontpage.php') {
        add_meta_box("nimbus_alt_front_meta_box", "Nimbus Alternate Frontpage Settings Panel", "nimbus_call_alt_front_meta_box", "page", "normal", "high");
    }
}

function nimbus_call_alt_front_meta_box($post) {

    global $post;
    $custom = get_post_custom($post->ID);
    if (isset($_POST['banner_option'])) {
        $banner_option = $custom["banner_option"][0];
    }
    if (isset($custom["full_width_banner_url"])) {
        $full_width_banner_url = $custom["full_width_banner_url"][0];
    } else {
        $full_width_banner_url = "";
    }
    if (isset($_POST['toggle_featured'])) {
        $toggle_featured = $custom["toggle_featured"][0];
    }
    if (isset($_POST['left_featured'])) {
        $left_featured = $custom["left_featured"][0];
    }
    if (isset($_POST['center_featured'])) {
        $center_featured = $custom["center_featured"][0];
    }
    if (isset($_POST['right_featured'])) {
        $right_featured = $custom["right_featured"][0];
    }
    if (isset($_POST['fp_content_pos'])) {
        $fp_content_pos = $custom["fp_content_pos"][0];
    }
    if (isset($_POST['fp_menu_pos'])) {
        $fp_menu_pos = $custom["fp_menu_pos"][0];
    }    
    if (isset($_POST['toggle_sidebar'])) {
        $toggle_sidebar = $custom["toggle_sidebar"][0];
    }


    $pages = array();
    $get_pages = get_pages('sort_column=post_parent,menu_order');





    echo '<input type="hidden" name="meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    ?>  

    <table style="width:100%;">
            <tr>
            <td style="width:20%;">
                <label><?php _e('Display menu Position:', 'nimbus') ?></label>
            </td>
            <td>
                <input class="" type="radio" name="fp_menu_pos" id="fp_menu_pos-below" value="below" <?php if (get_post_meta($post->ID, 'fp_menu_pos', true) == "below") { ?> checked <?php } ?>><label for="fp_menu_pos-below">Below Banners</label><br />
                <input class="" type="radio" name="fp_menu_pos" id="fp_menu_pos-above" value="above" <?php if (get_post_meta($post->ID, 'fp_menu_pos', true) == "above") { ?> checked <?php } ?>><label for="fp_menu_pos-above">Fixed Top</label><br />
            </td>
        </tr> 
        <tr>
            <td style="width:20%;"><label><?php _e('Select Banner Type:', 'nimbus') ?></label></td><td>
                <input class="" type="radio" name="banner_option" id="banner_option-none" value="none" <?php if (get_post_meta($post->ID, 'banner_option', true) == "none") { ?> checked <?php } ?> /><label for="banner_option-none">No Banner, Menu at the Top of the Page</label><br />
                <input class="" type="radio" name="banner_option" id="banner_option-image_full" value="image_full"  <?php if (get_post_meta($post->ID, 'banner_option', true) == "image_full") { ?> checked <?php } ?> /><label for="banner_option-image_full">Full Width Banner</label><br />
                <input class="" type="radio" name="banner_option" id="banner_option-image_full_fixed" value="image_full_fixed" <?php if (get_post_meta($post->ID, 'banner_option', true) == "image_full_fixed") { ?> checked <?php } ?> /><label for="banner_option-image_full_fixed">Full Width Banner With Image Fixed</label><br />
                <input class="" type="radio" name="banner_option" id="banner_option-image_content_width" value="image_content_width" <?php if (get_post_meta($post->ID, 'banner_option', true) == "image_content_width") { ?> checked <?php } ?> /><label for="banner_option-image_content_width">Banner Image That Spans the Width of the Content Area</label><br />
                <input class="" type="radio" name="banner_option" id="banner_option-image_content_width_border" value="image_content_width_border" <?php if (get_post_meta($post->ID, 'banner_option', true) == "image_content_width_border") { ?> checked <?php } ?> /><label for="banner_option-image_content_width_border">Banner Image That Spans the Width of the Content Area With a Border</label><br />
                <input class="" type="radio" name="banner_option" id="banner_option-slideshow" value="slideshow" <?php if (get_post_meta($post->ID, 'banner_option', true) == "slideshow") { ?> checked <?php } ?> /><label for="banner_option-slideshow">Slideshow That Spans the Width of the Content Area</label><br />
                <input class="" type="radio" name="banner_option" id="banner_option-content_only" value="content_only" <?php if (get_post_meta($post->ID, 'banner_option', true) == "content_only") { ?> checked <?php } ?> /><label for="banner_option-content_only">Full Width Banner Content: No image.</label>
            </td>
        </tr> 
        <tr><td><label><?php _e('Select the Full Width banner off your desktop. It should be 2000px X 1250px.', 'nimbus') ?></label></td><td><input type="text" name="full_width_banner_url" style="width:235px;" value="<?php echo $full_width_banner_url; ?>" size="20" maxlength="200" /></td></tr> 
        <tr>
            <td style="width:20%;">
                <label><?php _e('Display Featured Pages:', 'nimbus') ?></label>
            </td>
            <td>
                <input id="toggle_featured" class="" type="checkbox" name="toggle_featured" <?php if (get_post_meta($post->ID, 'toggle_featured', true) == "on") { ?> checked <?php } ?>><span class="checkbox_label">Show Featured</span>
            </td>
        </tr>  
        <tr>
            <td style="width:20%;">
                <label><?php _e('Left Feature Column:', 'nimbus') ?></label>
            </td>
            <td>
                <select class="" name="left_featured" id="left_featured">
                    <?php foreach ($get_pages as $page) { ?>
                        <option value="<?php echo $page->ID; ?>" <?php if (get_post_meta($post->ID, 'left_featured', true) == $page->ID) { ?> selected <?php } ?> ><?php echo $page->post_title; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>  
        <tr>
            <td style="width:20%;">
                <label><?php _e('Center Left Feature Column:', 'nimbus') ?></label>
            </td>
            <td>
                <select class="" name="center_left_featured" id="center_left_featured">
                    <?php foreach ($get_pages as $page) { ?>
                        <option value="<?php echo $page->ID; ?>" <?php if (get_post_meta($post->ID, 'center_left_featured', true) == $page->ID) { ?> selected <?php } ?> ><?php echo $page->post_title; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width:20%;">
                <label><?php _e('Center Right Feature Column:', 'nimbus') ?></label>
            </td>
            <td>
                <select class="" name="center_right_featured" id="center_right_featured">
                    <?php foreach ($get_pages as $page) { ?>
                        <option value="<?php echo $page->ID; ?>" <?php if (get_post_meta($post->ID, 'center_right_featured', true) == $page->ID) { ?> selected <?php } ?> ><?php echo $page->post_title; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width:20%;">
                    <label><?php _e('Right Feature Column:', 'nimbus') ?></label>
            </td>
            <td>
                <select class="" name="right_featured" id="right_featured">
                    <?php foreach ($get_pages as $page) { ?>
                        <option value="<?php echo $page->ID; ?>" <?php if (get_post_meta($post->ID, 'right_featured', true) == $page->ID) { ?> selected <?php } ?> ><?php echo $page->post_title; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="width:20%;">
                <label><?php _e('Display Main Content Position:', 'nimbus') ?></label>
            </td>
            <td>
                <input class="" type="radio" name="fp_content_pos" id="fp_content_pos-below" value="below" <?php if (get_post_meta($post->ID, 'fp_content_pos', true) == "below") { ?> checked <?php } ?>><label for="fp_content_pos-below">Below Featured Pages</label><br />
                <input class="" type="radio" name="fp_content_pos" id="fp_content_pos-above" value="above" <?php if (get_post_meta($post->ID, 'fp_content_pos', true) == "above") { ?> checked <?php } ?>><label for="fp_content_pos-above">Above Featured Pages</label><br />
                <input class="" type="radio" name="fp_content_pos" id="fp_content_pos-none" value="none" <?php if (get_post_meta($post->ID, 'fp_content_pos', true) == "none") { ?> checked <?php } ?>><label for="fp_content_pos-none">Don't Display Content</label><br />
            </td>
        </tr> 
        <tr>
            <td style="width:20%;">
                <label><?php _e('Display Content Row Sidebar:', 'nimbus') ?></label>
            </td>
            <td>
                <input id="toggle_sidebar" class="" type="checkbox" name="toggle_sidebar"  <?php if (get_post_meta($post->ID, 'toggle_sidebar', true) == "on") { ?> checked <?php } ?>><span class="checkbox_label"><?php _e('Show Sidebar', 'nimbus') ?></span>
            </td>
        </tr>  
    </table>


    <?php
}

/* **************************************************************************************************** */
// Save Alt Frontpage Template Setting
/* **************************************************************************************************** */

add_action('save_post', 'nimbus_alt_front_save_postdata');

function nimbus_alt_front_save_postdata($post_id) {

    global $post;

    // verify nonce

    if (isset($_POST['meta_box_nonce'])) {
        if (!wp_verify_nonce($_POST['meta_box_nonce'], basename(__FILE__))) {
            return $post_id;
        }
    }

    // check autosave

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if (isset($_POST['post_type'])) {
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    }

    if (isset($_POST['banner_option'])) {
        update_post_meta($post_id, 'banner_option', $_POST['banner_option']);
    }
    if (isset($_POST['full_width_banner_url'])) {
        update_post_meta($post_id, 'full_width_banner_url', $_POST['full_width_banner_url']);
    }
    if (isset($_POST['toggle_featured'])) {
        update_post_meta($post_id, 'toggle_featured', $_POST['toggle_featured']);
    }
    if (isset($_POST['left_featured'])) {
        update_post_meta($post_id, 'left_featured', $_POST['left_featured']);
    }
    if (isset($_POST['center_right_featured'])) {
        update_post_meta($post_id, 'center_right_featured', $_POST['center_right_featured']);
    }
    if (isset($_POST['center_left_featured'])) {
        update_post_meta($post_id, 'center_left_featured', $_POST['center_left_featured']);
    }
    if (isset($_POST['right_featured'])) {
        update_post_meta($post_id, 'right_featured', $_POST['right_featured']);
    }
    if (isset($_POST['fp_content_pos'])) {
        update_post_meta($post_id, 'fp_content_pos', $_POST['fp_content_pos']);
    }
    if (isset($_POST['fp_menu_pos'])) {
        update_post_meta($post_id, 'fp_menu_pos', $_POST['fp_menu_pos']);
    }
    if (isset($_POST['toggle_sidebar'])) {
        update_post_meta($post_id, 'toggle_sidebar', $_POST['toggle_sidebar']);
    }

}

/* **************************************************************************************************** */
// Load Admin Scripts
/* **************************************************************************************************** */

add_action('admin_print_scripts', 'nimbus_admin_scripts');

function nimbus_admin_scripts() {

    if (is_admin()) {

        wp_enqueue_script('jquery');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_register_script('charcount', get_template_directory_uri() . '/js/charCount.js', array('jquery'), '1.0');
        wp_enqueue_script('charcount');
        wp_register_script('nimbus_admin', get_template_directory_uri() . '/js/admin.js', array('jquery'), '1.0');
        wp_enqueue_script('nimbus_admin');
    }
}

/* **************************************************************************************************** */
// Load Admin CSS
/* **************************************************************************************************** */

add_action('admin_print_styles', 'nimbus_admin_styles');

function nimbus_admin_styles() {

    if (is_admin()) {

        wp_enqueue_style('thickbox');

        wp_register_style("admin_css", get_template_directory_uri() . "/css/admin.css", array(), "1.0", "all");
        wp_enqueue_style('admin_css');
    }
}

/* **************************************************************************************************** */
// Load Public Scripts
/* **************************************************************************************************** */

add_action('wp_enqueue_scripts', 'nimbus_public_scripts');

function nimbus_public_scripts() {

    if (!is_admin()) {

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');

        wp_register_script('nivo_slider', get_template_directory_uri() . '/js/jquery.nivo.slider.pack.js', array('jquery'), '1.1');
        wp_enqueue_script('nivo_slider');

        wp_register_script('nibus_public', get_template_directory_uri() . '/js/nimbus_public.js', array('jquery'), '1.0' );
        wp_enqueue_script('nibus_public'); 

        wp_register_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '2.2.2');
        wp_enqueue_script('bootstrap');
        
        wp_register_script('jquery_tools', get_template_directory_uri() . '/js/jquery.tools.min.js', array('jquery'), '1.2.6');
        wp_enqueue_script('jquery_tools');

    }
}

/* **************************************************************************************************** */
// Load Public Scripts in Conditional
/* **************************************************************************************************** */

add_action('wp_head', 'nimbus_public_scripts_conditional');

function nimbus_public_scripts_conditional() {
?>
    <!--[if lt IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
    <![endif]-->
<?php
}


/* **************************************************************************************************** */
// Load Public CSS
/* **************************************************************************************************** */


add_action('wp_print_styles', 'nimbus_public_styles');

function nimbus_public_styles() {

    if (!is_admin()) {

        wp_register_style("nivo_css", get_template_directory_uri() . "/css/nivo-slider.css", array(), "1.0", "all");
        wp_enqueue_style('nivo_css');

        wp_register_style("bootstrap", get_template_directory_uri() . "/css/bootstrap.min.css", array(), "1.0", "all");
        wp_enqueue_style('bootstrap');

        wp_register_style("bootstrap_fix", get_template_directory_uri() . "/css/bootstrap-fix.css", array(), "1.0", "all");
        wp_enqueue_style('bootstrap_fix');
        
        wp_register_style( 'nimbus-style', get_bloginfo( 'stylesheet_url' ), false, get_bloginfo('version') );
        wp_enqueue_style( 'nimbus-style' );

    }
}


/* **************************************************************************************************** */
// Scripts to footer
/* **************************************************************************************************** */

add_action('wp_footer', 'nimbus_wp_footer');

function nimbus_wp_footer() {
?>
<script>
jQuery(window).load(function() {
    jQuery('button, input[type="button"], input[type="reset"], input[type="submit"]').addClass('btn <?php echo nimbus_get_option('nimbus_button_color');?>');
    jQuery('a.btn').addClass('<?php echo nimbus_get_option('nimbus_button_color');?>');
});
</script>
    
<?php
}


/* **************************************************************************************************** */
// Register Post Types
/* **************************************************************************************************** */


// None 

/* **************************************************************************************************** */
// Register Post Type Taxonomies
/* **************************************************************************************************** */


// None 

/* **************************************************************************************************** */
// Register Gravatar
/* **************************************************************************************************** */

add_filter('avatar_defaults', 'nimbus_gravatar');

function nimbus_gravatar($avatar_defaults) {
    $myavatar = nimbus_get_option('gravatar');
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

/* **************************************************************************************************** */
// Create Menu For Public View
/* **************************************************************************************************** */


if (!has_nav_menu('primary')) {
nimbus_create_default_menu();
}

function nimbus_create_default_menu() {
    $nimbus_menu_name = THEME_NAME . ' Menu';
    $nimbus_menu_location = 'primary';
    $nimbus_menu_exists = wp_get_nav_menu_object( $nimbus_menu_name );
    if(!$nimbus_menu_exists){
        $menu_id = wp_create_nav_menu($nimbus_menu_name);
        $pages = get_pages( array('number' => 5 ) );
        
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => 'Home',
            'menu-item-url' => home_url( '/' ),
            'menu-item-type' => 'custom',
            'menu-item-status' => 'publish'));
        
        foreach( $pages as $page ) {	

            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => $page->post_title,
                'menu-item-object' => 'page',
                'menu-item-object-id' => $page->ID,
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish')
            );

        }	

        if( !has_nav_menu( $nimbus_menu_location ) ){
            $locations = get_theme_mod('nav_menu_locations');
            $locations[$nimbus_menu_location] = $menu_id;
            set_theme_mod( 'nav_menu_locations', $locations );
        }
    }
}
            
            
/* **************************************************************************************************** */
// Nav Walker Class Based on: (see below)
/* **************************************************************************************************** */

/**
 * Class Name: wp_bootstrap_navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class wp_bootstrap_navwalker extends Walker_Nav_Menu {

	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu\">\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		/**
		 * Dividers, Headers or Disabled
		 * =============================
		 * Determine whether the item is a Divider, Header, Disabled or regular
		 * menu item. To prevent errors we use the strcasecmp() function to so a
		 * comparison that is not case sensitive. The strcasecmp() function returns
		 * a 0 if the strings are equal.
		 */
		if (strcasecmp($item->attr_title, 'divider') == 0 && $depth === 1) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if (strcasecmp($item->title, 'divider') == 0 && $depth === 1) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if (strcasecmp($item->attr_title, 'dropdown-header') == 0 && $depth === 1) {
			$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
		} else if (strcasecmp($item->attr_title, 'disabled') == 0) {
			$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
		} else {

			$class_names = $value = '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

			if($args->has_children) {	$class_names .= ' dropdown'; }
			if(in_array('current-menu-item', $classes)) { $class_names .= ' active'; }

			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names .'>';

			$atts = array();
			$atts['title']  = ! empty( $item->title ) 	   ? $item->title 	   : '';
			$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
			$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';

			//If item has_children add atts to a
			if($args->has_children && $depth === 0) {
				$atts['href']   		= '#';
				$atts['data-toggle']	= 'dropdown';
				$atts['class']			= 'dropdown-toggle';
			} else {
				$atts['href'] = ! empty( $item->url ) ? $item->url : '';
			}

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output = $args->before;

			/*
			 * Glyphicons
			 * ===========
			 * Since the the menu item is NOT a Divider or Header we check the see
			 * if there is a value in the attr_title property. If the attr_title
			 * property is NOT null we apply it as the class name for the glyphicon.
			 */

			if(! empty( $item->attr_title )){
				$item_output .= '<a'. $attributes .'><span class="glyphicon ' . esc_attr( $item->attr_title ) . '"></span>&nbsp;';
			} else {
				$item_output .= '<a'. $attributes .'>';
			}

			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= ($args->has_children && $depth === 0) ? ' <span class="caret"></span></a>' : '</a>';
			$item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth. 
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */

	function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( !$element ) {
            return;
        }

        $id_field = $this->db_fields['id'];

        //display this element
        if ( is_object( $args[0] ) ) {
           $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
        }

        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

	
}


