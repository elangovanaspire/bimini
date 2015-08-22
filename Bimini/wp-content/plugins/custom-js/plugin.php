<?php

/*
  Plugin Name: custom-js
  Plugin URI: http://www.udemy.com
  Description: Test Plugin 1 - How to Make a Plugin for WordPress
  Author: K. Von Dyson
  Version: 1.0
  Author URI: http://www.udemy.com
  License: GPL2
 */
/*
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/*function my_enqueue($hook) {
    wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . 'js/custom.js');
}
*/
//add_action( 'admin_enqueue_scripts', 'my_enqueue' );

/*
 * Check and uncheck weekday checkboxes using every day checkbox in activity guide screen
 */
add_action('in_admin_footer', 'check_everyday_activity_guide');

function check_everyday_activity_guide() {
    ?>
    <script type='text/javascript'>
        jQuery(document).ready(function() {
            if (jQuery('#acf-field-day').length > 0) {                
                var acf = jQuery('#acf-field-day').val();
                jQuery("#acf-field-day").change(function() {                    
                    jQuery("[id^=acf-field-day-]").each(function() {
                        if(jQuery("#acf-field-day:checked").is(':checked')) {                            
                            jQuery(this).attr('checked', true);
                        } else {
                            jQuery(this).attr('checked', false);
                        }                        
                    });
                });
                jQuery("[id^=acf-field-day-]").change(function() {  
                    var flag = true;
                    jQuery("[id^=acf-field-day-]").each(function() {
                        if(jQuery(this).is(':checked')) {                                                        
                        } else {
                            flag = false;
                        }
                        if(flag==true){
                            jQuery("#acf-field-day").attr('checked', true);
                        } else {
                            jQuery("#acf-field-day").attr('checked', false);
                        }                        
                    });
                });                                
            }
        });</script>
    <?php

    // write some php here or just remake the footer and use die(); to finish the page etc. 
}

/*
 * Validate end time and start time in todays event screen
 */
add_action('in_admin_footer', 'validate_time_todays_events');

function validate_time_todays_events() {
    ?>
    <script type='text/javascript'>
        jQuery(document).ready(function() {
            jQuery("form").submit(function() {
                if (jQuery('form input[id=post_type]').val() == 'todays-events') {
                    $startTime = jQuery("#acf-start_time input:text");
                    $endTime = jQuery("#acf-end_time input:text");
                                                            
                    var tim1 = am_pm_to_hours($startTime.val()), tim2 = am_pm_to_hours($endTime.val());
                    var ary1 = tim1.split(':'), ary2 = tim2.split(':');
                    var minsdiff = parseInt(ary2[0], 10) * 60 + parseInt(ary2[1], 10) - parseInt(ary1[0], 10) * 60 - parseInt(ary1[1], 10);
                    if(minsdiff<=0) {
                        alert('End Time should be later than Start Time');
                        return false;
                    }
                }
                $startTime = jQuery("#acf-start_time input:text");
                $endTime = jQuery("#acf-end_time input:text");

                $startTime.data('validation', false);
                $endTime.data('validation', false);                
            });
            function am_pm_to_hours(time) {
                console.log(time);
                var hours = Number(time.match(/^(\d+)/)[1]);
                var minutes = Number(time.match(/:(\d+)/)[1]);
                var AMPM = time.match(/\s(.*)$/)[1];
                if (AMPM == "PM" && hours < 12)
                    hours = hours + 12;
                if (AMPM == "AM" && hours == 12)
                    hours = hours - 12;
                var sHours = hours.toString();
                var sMinutes = minutes.toString();
                if (hours < 10)
                    sHours = "0" + sHours;
                if (minutes < 10)
                    sMinutes = "0" + sMinutes;
                return (sHours + ':' + sMinutes);
            }            
        });
    </script>
    <?php

    // write some php here or just remake the footer and use die(); to finish the page etc. 
}

/*
 * Set maximum length of title field
 */
add_action('in_admin_footer', 'set_maximum_title_length');

function set_maximum_title_length() {
    $check=array();
    //$check['area-attractions']='20';
    $check['in-room-ordering']='50';
    global $post;
    ?>
    <script type='text/javascript'>
        jQuery(document).ready(function() {      
            <?php foreach($check as $key=>$check_data){
                if($post->post_type==$key) {                                       
                    ?>
                    if(jQuery('#titlediv input[id="title"]').length>0)
                        jQuery('#titlediv input[id="title"]').attr('maxlength', <?php echo $check_data ?>);
            <?php }}?>
        });
    </script>
    <?php

    // write some php here or just remake the footer and use die(); to finish the page etc. 
}

/*
 * Disable title field
 */
add_action('in_admin_footer', 'disable_title_field');

function disable_title_field() {
    global $post;
    $post_type=array('inroom-dining-menu','frames','social-sharing', 'weather-api','property-map','area-map','email-template','banner-image');
    ?>
    <script type='text/javascript'>
        jQuery(document).ready(function() {      
            <?php if(in_array($post->post_type,$post_type)) { ?>
                    if(jQuery('#titlediv input[id="title"]').length>0)
                        jQuery('#titlediv input[id="title"]').attr('disabled', true);                    
            <?php } ?>
        });
    </script>
    <?php

    // write some php here or just remake the footer and use die(); to finish the page etc. 
}

/*
 * Customize the admin toolbar
 */
function my_edit_toolbar($wp_toolbar) {
    $wp_toolbar->remove_node('wp-logo');
    //$wp_toolbar->remove_node('site-name');
    $wp_toolbar->remove_node('updates');
    $wp_toolbar->remove_node('comments');
    $wp_toolbar->remove_node('new-content');
    //$wp_toolbar->remove_node('top-secondary');
}
 
add_action('admin_bar_menu', 'my_edit_toolbar', 999);

/*
 * Hide screen options and help in toolbar
 */
add_action('in_admin_footer', 'hide_screen_options');

function hide_screen_options() {   
    ?>
    <script type='text/javascript'>
        jQuery(document).ready(function() {                 
                    if(jQuery('#screen-meta-links').length>0)
                        jQuery('#screen-meta-links').hide();                                
        });
    </script>
    <?php

    // write some php here or just remake the footer and use die(); to finish the page etc. 
}

/*
 * Load date in todays events
 */
add_action('in_admin_footer', 'load_date_in_todays_events');

function load_date_in_todays_events() {
    global $post;
    ?>
    <script type='text/javascript'>
        jQuery(document).ready(function() {
    <?php if (!empty($post->start_date)) { ?>
                jQuery('.acf-date_picker .input').attr('disabled',true);
              
    <?php } else { ?>
               /*jQuery('.acf-date_picker .input').datepicker({
                    minDate: new Date()
                });*/
    <?php } ?>
        });
    </script>
    <?php
// write some php here or just remake the footer and use die(); to finish the page etc. 
}

/*
 * Remove Posts column in user table
 */
add_action('manage_users_columns', 'remove_user_posts_column');
function remove_user_posts_column($column_headers) {
    unset($column_headers['posts']);
    return $column_headers;
}
?>
