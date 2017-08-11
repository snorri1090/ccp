<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php wp_title('', true); ?></title>
        <link rel="Shortcut Icon" href="<?php
        if (nimbus_get_option('favicon') != "") {
            echo nimbus_get_option('favicon');
        } else {
            echo get_template_directory_uri(); ?>/images/favicon.ico <?php } ?>" type="image/x-icon" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <?php 
        wp_head();
        get_template_part( 'parts/scripts', 'conditional_head');
        ?>
	<meta name="description" content="Columbia City Pilates is committed to providing an outstanding pilates fitness in the greater Seattle area in Washington State.">
	<meta name="keywords" content="Pilates, dL Powers, Seattle Pilates, Western Washington Pilates, Fitness, Free Trial‎, Mat Classes‎, Workshops‎, Beginner Center‎, Adductor Muscles, Classical Pilates, Contemporary Pilates, Contrology, Core Strength, Counter Stretch, Diaphragmatic Breathing">
    </head>
    <body <?php body_class(); ?>>
        <header>
	<div class=".full_banner" style="width:100%; background-image:url('http://cc-pilates.com/wp-content/uploads/banners/banner_<?php global $post; echo $post->post_name; ?>.jpg'); background-size:100%; background-repeat: no-repeat; background-color: #3c5555;">
	    <?php get_template_part( 'parts/logo');  ?>
 	    </div>
            <?php 
                //get_template_part( 'parts/banner');
	        //get_template_part( 'parts/logo');
            get_template_part( 'parts/menu');
            ?>
        </header>