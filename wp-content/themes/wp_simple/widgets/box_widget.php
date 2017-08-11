<?php
add_action('widgets_init', create_function('', 'register_widget("nimbus_box_widget");'));

class nimbus_box_widget extends WP_Widget {

    function __construct() {
        parent::WP_Widget('nimbus_box_widget', THEME_NAME . ' Box Widget', array('description' => THEME_NAME . ' Box Widget'));
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract($args);
        $background_color = $instance['background_color'];
        $border_color = $instance['border_color'];
        $box_content = $instance['box_content'];


        echo $before_widget;
        
        ?>

        <div class="box_widget" style="background-color:#<?php echo $background_color; ?>; border:1px solid #<?php echo $border_color; ?>;">

            <?php echo wptexturize(wpautop($box_content)); ?>

        </div>

        <?php
        echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['background_color'] = strip_tags($new_instance['background_color']);
        $instance['border_color'] = strip_tags($new_instance['border_color']);
        $instance['box_content'] = $new_instance['box_content'];
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('background_color' => 'ffffff', 'border_color' => 'cccccc', 'box_content' => ''));
        $background_color = strip_tags($instance['background_color']);
        $border_color = strip_tags($instance['border_color']);
        $box_content = $instance['box_content'];
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('background_color'); ?>">Background Color:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('background_color'); ?>" name="<?php echo $this->get_field_name('background_color'); ?>" type="text" value="<?php echo $background_color; ?>" />
            <label for="<?php echo $this->get_field_id('border_color'); ?>">Border Color:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('border_color'); ?>" name="<?php echo $this->get_field_name('border_color'); ?>" type="text" value="<?php echo $border_color; ?>" />
            <label for="<?php echo $this->get_field_id('box_content'); ?>">Box Content:</label> 
            <textarea class="widefat tall_widget_textarea" id="<?php echo $this->get_field_id('box_content'); ?>" name="<?php echo $this->get_field_name('box_content'); ?>" ><?php echo $box_content; ?></textarea><br /><br />
        </p>

        <?php
    }

}

