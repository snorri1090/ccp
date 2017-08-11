<?php

class nimbus_about_me_widget extends WP_Widget {

    function __construct() {
        parent::WP_Widget('nimbus_about_me_widget', THEME_NAME . ' About Me Widget', array('description' => THEME_NAME . ' About Me Widget'));
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract($args);
        $about_me_title = trim($instance['about_me_title']);
        $about_me_email = trim($instance['about_me_email']);
        $about_me_description = trim($instance['about_me_description']);
        $about_me_name = trim($instance['about_me_name']);
        $about_me_ed1 = trim($instance['about_me_ed1']);
        $about_me_ed2 = trim($instance['about_me_ed2']);
        $about_me_ed3 = trim($instance['about_me_ed3']);
        $about_me_ed4 = trim($instance['about_me_ed4']);
        $about_me_in1 = trim($instance['about_me_in1']);
        $about_me_in2 = trim($instance['about_me_in2']);
        $about_me_in3 = trim($instance['about_me_in3']);
        $about_me_in4 = trim($instance['about_me_in4']);
        $about_me_cust_t = trim($instance['about_me_cust_t']);
        $about_me_cust1 = trim($instance['about_me_cust1']);
        $about_me_cust2 = trim($instance['about_me_cust2']);
        $about_me_cust3 = trim($instance['about_me_cust3']);
        $about_me_cust4 = trim($instance['about_me_cust4']);

        echo '<div class="about_me_box">';
        echo $before_widget;

        if ($about_me_title != '') {
            echo $before_title . $about_me_title . $after_title;
        }
        if ($about_me_email != '') {
            echo get_avatar($about_me_email, $size = '85');
        }
        if ($about_me_name != '') {
            echo '<p><strong>' . $about_me_name . '</strong></p>';
        }
        if ($about_me_description != '') {
            echo wptexturize(wpautop($about_me_description));
        }
        if (( $about_me_ed1 != '') || ( $about_me_ed2 != '' ) || ( $about_me_ed3 != '') || ( $about_me_ed4 != '')) {
            echo '<p><strong>Education History</strong><br />';
            if ($about_me_ed1) {
                echo $about_me_ed1 . '<br />';
            }
            if ($about_me_ed2) {
                echo $about_me_ed2 . '<br />';
            }
            if ($about_me_ed3) {
                echo $about_me_ed3 . '<br />';
            }
            if ($about_me_ed4) {
                echo $about_me_ed4;
            }
            echo '</p>';
        }

        if (( $about_me_in1 != '') || ( $about_me_in2 != '' ) || ( $about_me_in3 != '') || ( $about_me_in4 != '')) {
            echo '<p><strong>Interests</strong><br />';
            if ($about_me_in1 != '') {
                echo $about_me_in1 . '<br />';
            }
            if ($about_me_in2) {
                echo $about_me_in2 . '<br />';
            }
            if ($about_me_in3) {
                echo $about_me_in3 . '<br />';
            }
            if ($about_me_in4) {
                echo $about_me_in4;
            }
            echo '</p>';
        }

        if (( $about_me_cust_t != '') || ( $about_me_cust1 != '') || ( $about_me_cust2 != '' ) || ( $about_me_cust3 != '') || ( $about_me_cust4 != '')) {
            if ($about_me_cust_t != '') {
                echo '<p><strong>' . $about_me_cust_t . '</strong><br />';
            }
            if ($about_me_cust1 != '') {
                echo $about_me_in1 . '<br />';
            }
            if ($about_me_cust2) {
                echo $about_me_cust2 . '<br />';
            }
            if ($about_me_in3) {
                echo $about_me_cust3 . '<br />';
            }
            if ($about_me_in4) {
                echo $about_me_cust4;
            }
            echo '</p>';
        }

        echo $after_widget;
        echo '</div>';
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['about_me_title'] = strip_tags($new_instance['about_me_title']);
        $instance['about_me_email'] = strip_tags($new_instance['about_me_email']);
        $instance['about_me_description'] = strip_tags($new_instance['about_me_description']);
        $instance['about_me_name'] = strip_tags($new_instance['about_me_name']);
        $instance['about_me_ed1'] = strip_tags($new_instance['about_me_ed1']);
        $instance['about_me_ed2'] = strip_tags($new_instance['about_me_ed2']);
        $instance['about_me_ed3'] = strip_tags($new_instance['about_me_ed3']);
        $instance['about_me_ed4'] = strip_tags($new_instance['about_me_ed4']);
        $instance['about_me_in1'] = strip_tags($new_instance['about_me_in1']);
        $instance['about_me_in2'] = strip_tags($new_instance['about_me_in2']);
        $instance['about_me_in3'] = strip_tags($new_instance['about_me_in3']);
        $instance['about_me_in4'] = strip_tags($new_instance['about_me_in4']);
        $instance['about_me_cust_t'] = strip_tags($new_instance['about_me_cust_t']);
        $instance['about_me_cust1'] = strip_tags($new_instance['about_me_cust1']);
        $instance['about_me_cust2'] = strip_tags($new_instance['about_me_cust2']);
        $instance['about_me_cust3'] = strip_tags($new_instance['about_me_cust3']);
        $instance['about_me_cust4'] = strip_tags($new_instance['about_me_cust4']);

        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('about_me_title' => 'About Me', 'about_me_email' => nimbus_get_option('public_email'), 'about_me_description' => '', 'about_me_name' => '', 'about_me_ed1' => '', 'about_me_ed2' => '', 'about_me_ed3' => '', 'about_me_ed4' => '', 'about_me_in1' => '', 'about_me_in2' => '', 'about_me_in3' => '', 'about_me_in4' => '', 'about_me_cust_t' => '', 'about_me_cust1' => '', 'about_me_cust2' => '', 'about_me_cust3' => '', 'about_me_cust4' => '',));
        $about_me_title = strip_tags($instance['about_me_title']);
        $about_me_email = strip_tags($instance['about_me_email']);
        $about_me_description = strip_tags($instance['about_me_description']);
        $about_me_name = strip_tags($instance['about_me_name']);
        $about_me_ed1 = strip_tags($instance['about_me_ed1']);
        $about_me_ed2 = strip_tags($instance['about_me_ed2']);
        $about_me_ed3 = strip_tags($instance['about_me_ed3']);
        $about_me_ed4 = strip_tags($instance['about_me_ed4']);
        $about_me_in1 = strip_tags($instance['about_me_in1']);
        $about_me_in2 = strip_tags($instance['about_me_in2']);
        $about_me_in3 = strip_tags($instance['about_me_in3']);
        $about_me_in4 = strip_tags($instance['about_me_in4']);
        $about_me_cust_t = strip_tags($instance['about_me_cust_t']);
        $about_me_cust1 = strip_tags($instance['about_me_cust1']);
        $about_me_cust2 = strip_tags($instance['about_me_cust2']);
        $about_me_cust3 = strip_tags($instance['about_me_cust3']);
        $about_me_cust4 = strip_tags($instance['about_me_cust4']);
        ?>
        <p>	
            <strong>Title</strong><br />
            <label for="<?php echo $this->get_field_id('about_me_title'); ?>">About Me Widget Title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_title'); ?>" name="<?php echo $this->get_field_name('about_me_title'); ?>" type="text" value="<?php echo $about_me_title; ?>" />
        </p>
        <p>
            <strong>Gravatar</strong><br />
            <label for="<?php echo $this->get_field_id('about_me_email'); ?>">Email address to display a gravatar (not public):</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_email'); ?>" name="<?php echo $this->get_field_name('about_me_email'); ?>" type="text" value="<?php echo $about_me_email; ?>" />
        </p>
        <p>
            <strong>Name &amp; Description</strong><br />
            <label for="<?php echo $this->get_field_id('about_me_name'); ?>">Name:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_name'); ?>" name="<?php echo $this->get_field_name('about_me_name'); ?>" type="text" value="<?php echo $about_me_name; ?>" /><br /><br />
            <label for="<?php echo $this->get_field_id('about_me_description'); ?>">A description of you:</label> 
            <textarea class="widefat" style="min-height:120px;" id="<?php echo $this->get_field_id('about_me_description'); ?>" name="<?php echo $this->get_field_name('about_me_description'); ?>" ><?php echo $about_me_description; ?></textarea>
        </p>
        <p>
            <strong>Education History</strong><br />
            <label for="<?php echo $this->get_field_id('about_me_ed1'); ?>">Education #1:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_ed1'); ?>" name="<?php echo $this->get_field_name('about_me_ed1'); ?>" type="text" value="<?php echo $about_me_ed1; ?>" /><br /><br />
            <label for="<?php echo $this->get_field_id('about_me_ed2'); ?>">Education #2:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_ed2'); ?>" name="<?php echo $this->get_field_name('about_me_ed2'); ?>" type="text" value="<?php echo $about_me_ed2; ?>" /><br /><br />
            <label for="<?php echo $this->get_field_id('about_me_ed3'); ?>">Education #3:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_ed3'); ?>" name="<?php echo $this->get_field_name('about_me_ed3'); ?>" type="text" value="<?php echo $about_me_ed3; ?>" /><br /><br />
            <label for="<?php echo $this->get_field_id('about_me_ed4'); ?>">Education #4:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_ed4'); ?>" name="<?php echo $this->get_field_name('about_me_ed4'); ?>" type="text" value="<?php echo $about_me_ed4; ?>" />
        </p>
        <p>
            <strong>Your Interests</strong><br />
            <label for="<?php echo $this->get_field_id('about_me_in1'); ?>">Interest #1:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_in1'); ?>" name="<?php echo $this->get_field_name('about_me_in1'); ?>" type="text" value="<?php echo $about_me_in1; ?>" /><br /><br />
            <label for="<?php echo $this->get_field_id('about_me_in2'); ?>">Interest #2:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_in2'); ?>" name="<?php echo $this->get_field_name('about_me_in2'); ?>" type="text" value="<?php echo $about_me_in2; ?>" /><br /><br />
            <label for="<?php echo $this->get_field_id('about_me_in3'); ?>">Interest #3:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_in3'); ?>" name="<?php echo $this->get_field_name('about_me_in3'); ?>" type="text" value="<?php echo $about_me_in3; ?>" /><br /><br />
            <label for="<?php echo $this->get_field_id('about_me_in4'); ?>">Interest #4:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_in4'); ?>" name="<?php echo $this->get_field_name('about_me_in4'); ?>" type="text" value="<?php echo $about_me_in4; ?>" />
        </p>
        <p>
            <strong>Custom</strong><br />
            <label for="<?php echo $this->get_field_id('about_me_cust_t'); ?>">Custom Title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_cust_t'); ?>" name="<?php echo $this->get_field_name('about_me_cust_t'); ?>" type="text" value="<?php echo $about_me_cust_t; ?>" /><br /><br />
            <label for="<?php echo $this->get_field_id('about_me_cust1'); ?>">Custom #1:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_cust1'); ?>" name="<?php echo $this->get_field_name('about_me_cust1'); ?>" type="text" value="<?php echo $about_me_cust1; ?>" /><br /><br />
            <label for="<?php echo $this->get_field_id('about_me_cust2'); ?>">Custom #2</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_cust2'); ?>" name="<?php echo $this->get_field_name('about_me_cust2'); ?>" type="text" value="<?php echo $about_me_cust2; ?>" /><br /><br />
            <label for="<?php echo $this->get_field_id('about_me_cust3'); ?>">Custom #3:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_cust3'); ?>" name="<?php echo $this->get_field_name('about_me_cust3'); ?>" type="text" value="<?php echo $about_me_cust3; ?>" /><br /><br />
            <label for="<?php echo $this->get_field_id('about_me_cust4'); ?>">Custom #4:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('about_me_cust4'); ?>" name="<?php echo $this->get_field_name('about_me_cust4'); ?>" type="text" value="<?php echo $about_me_cust4; ?>" />
        </p>
        <?php
    }

}

add_action('widgets_init', create_function('', 'register_widget("nimbus_about_me_widget");'));