<?php 
global $post;

// set variables if front-page
if (is_front_page()) {
    $banner_option = nimbus_get_option('nimbus_banner_option');
    $full_width_banner_url = nimbus_get_option('nimbus_full_width_banner');
    if (empty($full_width_banner_url)) {
        $full_width_banner_url = get_template_directory_uri() . '/images/preview/crater-lake.jpg';
    }
    
// set variables if alternate template    
} else if (is_page_template('alt_frontpage.php')) {
    $banner_option = trim(get_post_meta($post->ID, 'banner_option', true));
    $full_width_banner_url = trim(get_post_meta($post->ID, 'full_width_banner_url', true));
}

// Do frontpage banner options
if ((is_front_page() && !is_paged()) || is_page_template('alt_frontpage.php')) {

    // Full width image with fixed positioning
    if ($banner_option == 'image_full_fixed') { 
    ?>
        <div id="fixed_banner" class="">
            <?php 
            get_template_part( 'parts/banner', 'full_content'); 
            ?>
        </div>
    <?php 
    
    // Full width image with non-fixed positioning
    } else if ($banner_option == 'image_full') { 
    ?>
        <div id="full_banner">
            <?php 
            get_template_part( 'parts/banner', 'full_content'); 
            ?>
        </div>
    <?php 
    
    // Content width banner
    } else if ($banner_option == 'image_content_width') { 
    ?>
        <div class="container">
            <?php 
            get_template_part( 'parts/image', '1140_420');
            ?>
        </div>
    <?php 
    
    // Content width banner with border
    } else if ($banner_option == 'image_content_width_border') { 
    ?>
        <div class="container">
            <?php 
            get_template_part( 'parts/image', '1130_410');
            ?>
        </div>
    <?php 
    
    // Content width slideshow
    } else if ($banner_option == 'slideshow') { 
    ?>
        <div class="container slider-wrapper theme-<?php echo nimbus_get_option('nimbus_slideshow_theme');?>">
            <div class="ribbon"></div>
                <div id="full_content_width_slider" class="nivoSlider"> 
                    <?php
                    $original_query = $wp_query;
                    $wp_query = null;
                    $wp_query = new WP_Query(array("meta_key" => "include_in_slideshow", "meta_value" => "true", "post_type" => array('post', 'page'), "posts_per_page" => "-1", "post__not_in" => get_option( "sticky_posts" )));
                    if (have_posts()) { 
                        while (have_posts()) { 
                            the_post();
                            ?>		
                            <a href="<?php the_permalink(); ?>">
                            <?php
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('nimbus_1140_420', array('class' => ''));
                            }
                            ?>
                            </a>
                         <?php 
                        }
                    
                    }
                    $wp_query = null;
                    $wp_query = $original_query;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
    <?php 
    
    // Full width slideshow *not currently active
    } else if ($banner_option == 'slideshow_full') { 
    ?>
        <div class="full_width_slideshow_container slider-wrapper theme-<?php echo nimbus_get_option('nimbus_slideshow_theme');?>">
            <div class="ribbon"></div>
                <div id="full_width_slider" class="nivoSlider"> 
                    <?php
                    $original_query = $wp_query;
                    $wp_query = null;
                    $wp_query = new WP_Query(array("meta_key" => "include_in_slideshow", "meta_value" => "true", "post_type" => array('post', 'page'), "posts_per_page" => "-1", "post__not_in" => get_option( "sticky_posts" )));
                    if (have_posts()) { 
                        while (have_posts()) { 
                            the_post();
                            ?>		
                            <a href="<?php the_permalink(); ?>">
                            <?php
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('nimbus_1140_420', array('class' => '', 'title' => get_post_meta($post->ID, 'banner_caption', true)));
                            }
                            ?>
                            </a>
                         <?php 
                        }
                    
                    }
                    $wp_query = null;
                    $wp_query = $original_query;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
    <?php 
    
    // Content only, no banner
    } else if ($banner_option == 'content_only') { 
        get_template_part( 'parts/banner', 'full_content'); 
    } 

// Not on frontpage, do standard layout    
} else {
?>

  <div class="container">
     <?php
        get_template_part( 'parts/logo');
        ?>
    </div>
<?php
}     
?>