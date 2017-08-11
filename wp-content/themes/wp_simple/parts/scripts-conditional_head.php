<?php 
if ((nimbus_get_option('nimbus_banner_option') == 'slideshow') || (nimbus_get_option('nimbus_banner_option') == 'slideshow_full') || (is_page_template('alt_frontpage.php') && (get_post_meta($post->ID, 'banner_option', true) == "slideshow"))) { 
?>
    <link rel='stylesheet' href='<?php echo get_stylesheet_directory_uri(); ?>/css/nivo-themes/<?php echo nimbus_get_option('nimbus_slideshow_theme');?>/<?php echo nimbus_get_option('nimbus_slideshow_theme');?>.css' type='text/css' media='all' />
    <script type="text/javascript">
    jQuery(window).load(function() {
        jQuery('#full_content_width_slider, #full_width_slider').nivoSlider({
            effect: '<?php echo nimbus_get_option('nimbus_slideshow_effect') ?>',               
            slices: <?php echo nimbus_get_option('nimbus_slideshow_slices') ?>,                     
            boxCols: <?php echo nimbus_get_option('nimbus_slideshow_box_col') ?>,                     
            boxRows: <?php echo nimbus_get_option('nimbus_slideshow_box_rows') ?>,                     
            animSpeed: <?php echo nimbus_get_option('nimbus_slideshow_trans_speed') ?>,                 
            pauseTime: <?php echo nimbus_get_option('nimbus_slideshow_pause') ?>,                
            startSlide: 0,                 
            directionNav: <?php echo nimbus_get_option('nimbus_slideshow_direct_nav') ?>,             
            controlNav: <?php echo nimbus_get_option('nimbus_slideshow_control_nav') ?>,                       
            pauseOnHover: <?php echo nimbus_get_option('nimbus_slideshow_hover_pause') ?>,             
            manualAdvance: <?php echo nimbus_get_option('nimbus_slideshow_manual_advance') ?>,                          
            randomStart: <?php echo nimbus_get_option('nimbus_slideshow_random_start') ?>
        });
    });
    </script>
<?php 
} 
?>