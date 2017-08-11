<?php

class nimbus_popular_posts_widget extends WP_Widget {

    function __construct() {
        parent::WP_Widget('nimbus_popular_posts_widget', THEME_NAME . ' Popular Posts Widget', array('description' => THEME_NAME . ' Popular Posts Widget'));
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract($args);
        $popular_posts_title = $instance['popular_posts_title'];
        $popular_posts_num = $instance['popular_posts_num'];

        echo $before_widget;
        if ($popular_posts_title)
            echo $before_title . $popular_posts_title . $after_title;
        echo '<ul class="pop_posts">';
        query_posts('orderby=comment_count&showposts=6');
        if (have_posts()) : while (have_posts()) : the_post();
                ?>
                <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a><br /><span class="comment_widget_num"><?php comments_number('0 Comments', '1 Comment', '% Comments'); ?></span></li>
            <?php endwhile; ?>
        <?php else : ?>
            <li>Sorry, no posts were found.</li>
        <?php endif; ?>

        <?php
        echo '</ul>';
        echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['popular_posts_title'] = strip_tags($new_instance['popular_posts_title']);
        $instance['popular_posts_num'] = strip_tags($new_instance['popular_posts_num']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('popular_posts_title' => 'Popular Posts', 'popular_posts_num' => '5'));
        $popular_posts_title = strip_tags($instance['popular_posts_title']);
        $popular_posts_num = strip_tags($instance['popular_posts_num']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('popular_posts_title'); ?>">Popular Posts Widget Title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('popular_posts_title'); ?>" name="<?php echo $this->get_field_name('popular_posts_title'); ?>" type="text" value="<?php echo $popular_posts_title; ?>" />

            <label for="<?php echo $this->get_field_id('popular_posts_num'); ?>">Number of posts:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('popular_posts_num'); ?>" name="<?php echo $this->get_field_name('popular_posts_num'); ?>" type="text" value="<?php echo $popular_posts_num; ?>" />

        </p>
        <?php
    }

}

add_action('widgets_init', create_function('', 'register_widget("nimbus_popular_posts_widget");'));