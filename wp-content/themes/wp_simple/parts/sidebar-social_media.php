<?php
$social_type = array("nimbus_facebook_url", "nimbus_linkedin_url", "nimbus_twitter_url", "nimbus_youtube_url", "nimbus_google_plus_url", "nimbus_vimeo_url", "nimbus_flickr_url", "nimbus_wpcom_url", "nimbus_pinterest_url", "nimbus_amazon_url", "nimbus_instagram_url", "nimbus_tumblr_url", "nimbus_mail_url");
foreach ($social_type as $key => $id) {
    $$id = trim(nimbus_get_option($id));
    if (empty($$id)) {
        if (nimbus_get_option('nimbus_example_content') == "on") {
            $$id = '#';
        }
    } else {
        $id = $$id;
    }
}

?>
<h3 class="social_title"><?php echo nimbus_get_option('nimbus_social_sidebar_title'); ?></h3>
<ul id='social_media_icons'>
    <?php 
    if (!empty($nimbus_facebook_url)) { 
        echo"<li id='facebook_icon'><a target='_blank' href='" . $nimbus_facebook_url . "'></a></li>";
    } 
    if (!empty($nimbus_linkedin_url)) { 
        echo"<li id='linkedin_icon'><a target='_blank' href='" . $nimbus_linkedin_url . "'></a></li>";
    } 
    if (!empty($nimbus_twitter_url)) { 
        echo"<li id='twitter_icon'><a target='_blank' href='" . $nimbus_twitter_url . "'></a></li>"; 
    } 
    if (!empty($nimbus_youtube_url)) { 
        echo"<li id='youtube_icon'><a target='_blank' href='" . $nimbus_youtube_url . "'></a></li>"; 
    } 
    if (!empty($nimbus_google_plus_url)) { 
        echo"<li id='google_plus_icon'><a target='_blank' href='" . $nimbus_google_plus_url . "'></a></li>";
    } 
    if (!empty($nimbus_vimeo_url)) { 
        echo"<li id='vimeo_icon'><a target='_blank' href='" . $nimbus_vimeo_url . "'></a></li>";
    }
    if (!empty($nimbus_flickr_url)) { 
        echo"<li id='flickr_icon'><a target='_blank' href='" . $nimbus_flickr_url . "'></a></li>";
    }
    if (!empty($nimbus_wpcom_url)) { 
        echo"<li id='wpcom_icon'><a target='_blank' href='" . $nimbus_wpcom_url . "'></a></li>";
    }
    if (!empty($nimbus_pinterest_url)) { 
        echo"<li id='pinterest_icon'><a target='_blank' href='" . $nimbus_pinterest_url . "'></a></li>";
    }
    if (!empty($nimbus_amazon_url)) { 
        echo"<li id='amazon_icon'><a target='_blank' href='" . $nimbus_amazon_url . "'></a></li>";
    }
    if (!empty($nimbus_instagram_url)) { 
        echo"<li id='instagram_icon'><a target='_blank' href='" . $nimbus_instagram_url . "'></a></li>";
    }
    if (!empty($nimbus_tumblr_url)) { 
        echo"<li id='tumblr_icon'><a target='_blank' href='" . $nimbus_tumblr_url . "'></a></li>";
    }
    if (!empty($nimbus_facebook_url)) { 
        echo"<li id='mail_icon'><a href='mailto:" . nimbus_get_option('nimbus_mail_url') . "'></a></li>";
    }
    if (nimbus_get_option('nimbus_hide_rss_icon') == 0) {
    ?>
        <li id='rss_icon'><a href='<?php echo bloginfo('rss2_url'); ?>'></a></li>
    <?php    
    }
    ?>
</ul>
<div class='clear20'></div>


