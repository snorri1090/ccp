<?php

class nimbus_contact_widget extends WP_Widget {

    function __construct() {
        parent::WP_Widget('nimbus_contact_widget', THEME_NAME . ' Contact Info Widget', array('description' => THEME_NAME . ' Contact Info Widget'));
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract($args);
        $contact_title = $instance['contact_title'];
        $contact_address1 = $instance['contact_address1'];
        $contact_address2 = $instance['contact_address2'];
        $contact_address3 = $instance['contact_address3'];
        $contact_phone = $instance['contact_phone'];
        $contact_email = $instance['contact_email'];
        $contact_fax = $instance['contact_fax'];


        echo $before_widget;

        if ($contact_title)
            echo $before_title . $contact_title . $after_title;
        ?>

        <p><?php if ($contact_address1) echo $contact_address1 . '<br />'; ?>
            <?php if ($contact_address2) echo $contact_address2 . '<br />'; ?>
            <?php if ($contact_address3) echo $contact_address3; ?></p>

        <?php
        if (( $contact_fax != '') || ( $contact_email != '') || ( $contact_phone != '' )) {
            echo '<p class="contact_widget_lastp">';
        }
        ?>
        <?php if ($contact_email) echo '<a href="mailto:' . $contact_email . '">' . $contact_email . '</a><br />'; ?>
        <?php if ($contact_phone) echo 'Phone ' . $contact_phone . '<br />'; ?>
        <?php if ($contact_fax) echo 'Fax ' . $contact_fax; ?>
        <?php
        if (( $contact_fax != '') || ( $contact_email != '') || ( $contact_phone != '' )) {
            echo '</p>';
        }
        ?>

        <?php
        echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['contact_title'] = strip_tags($new_instance['contact_title']);
        $instance['contact_address1'] = strip_tags($new_instance['contact_address1']);
        $instance['contact_address2'] = strip_tags($new_instance['contact_address2']);
        $instance['contact_address3'] = strip_tags($new_instance['contact_address3']);
        $instance['contact_phone'] = strip_tags($new_instance['contact_phone']);
        $instance['contact_email'] = strip_tags($new_instance['contact_email']);
        $instance['contact_fax'] = strip_tags($new_instance['contact_fax']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('contact_title' => 'Contact', 'contact_address1' => '', 'contact_address2' => '', 'contact_address3' => '', 'contact_phone' => nimbus_get_option('public_phone'), 'contact_email' => nimbus_get_option('public_email'), 'contact_fax' => nimbus_get_option('public_fax')));
        $contact_title = strip_tags($instance['contact_title']);
        $contact_address1 = strip_tags($instance['contact_address1']);
        $contact_address2 = strip_tags($instance['contact_address2']);
        $contact_address3 = strip_tags($instance['contact_address3']);
        $contact_phone = strip_tags($instance['contact_phone']);
        $contact_email = strip_tags($instance['contact_email']);
        $contact_fax = strip_tags($instance['contact_fax']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('contact_title'); ?>">Contact Widget Title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('contact_title'); ?>" name="<?php echo $this->get_field_name('contact_title'); ?>" type="text" value="<?php echo $contact_title; ?>" />

            <label for="<?php echo $this->get_field_id('contact_address1'); ?>">Address Line One:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('contact_address1'); ?>" name="<?php echo $this->get_field_name('contact_address1'); ?>" type="text" value="<?php echo $contact_address1; ?>" />

            <label for="<?php echo $this->get_field_id('contact_address2'); ?>">Address Line Two:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('contact_address2'); ?>" name="<?php echo $this->get_field_name('contact_address2'); ?>" type="text" value="<?php echo $contact_address2; ?>" />

            <label for="<?php echo $this->get_field_id('contact_address3'); ?>">Address Line Three:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('contact_address3'); ?>" name="<?php echo $this->get_field_name('contact_address3'); ?>" type="text" value="<?php echo $contact_address3; ?>" />

            <label for="<?php echo $this->get_field_id('contact_email'); ?>">Email Address:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('contact_email'); ?>" name="<?php echo $this->get_field_name('contact_email'); ?>" type="text" value="<?php echo $contact_email; ?>" />

            <label for="<?php echo $this->get_field_id('contact_phone'); ?>">Phone Number:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('contact_phone'); ?>" name="<?php echo $this->get_field_name('contact_phone'); ?>" type="text" value="<?php echo $contact_phone; ?>" />

            <label for="<?php echo $this->get_field_id('contact_fax'); ?>">Fax Number:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('contact_fax'); ?>" name="<?php echo $this->get_field_name('contact_fax'); ?>" type="text" value="<?php echo $contact_fax; ?>" />

        </p>
        <?php
    }

}

add_action('widgets_init', create_function('', 'register_widget("nimbus_contact_widget");'));