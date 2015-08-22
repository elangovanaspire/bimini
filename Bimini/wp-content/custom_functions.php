<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.

 */

/*
 * function to remove quick edit for all custom posts
 * 
 */

function remove_quick_edit($actions) {
    global $post;
    $post_type = array('spa', 'shopping', 'night-life', 'promotions', 'pool', 'fitness-center', 'area-attractions', 'email-template',
        'inroom-dining-menu', 'restaurants-and-bars', 'in-room-ordering', 'menu-category',
        'area-map', 'property-map', 'banner-image', 'todays-events', 'activity-guide', 'planning-a-meeting',
        'slots', 'table-games', 'responsible-gaming', 'players-club', 'tutorials',
        'frames', 'social-sharing', 'weather-api', 'page');
    if (in_array($post->post_type, $post_type)) {
        unset($actions['inline hide-if-no-js']);
    }
    return $actions;
}

if (is_admin()) {
    add_filter('post_row_actions', 'remove_quick_edit', 10, 2);
}


/*
 * function to remove trash for all custom posts
 */

function remove_trash($actions) {
    global $post;
    $post_type = array('spa', 'pool', 'fitness-center', 'email-template', 'inroom-dining-menu',
        'area-map', 'property-map', 'banner-image', 'menu-category', 'planning-a-meeting',
        'slots', 'table-games', 'responsible-gaming', 'players-club', 'tutorials',
        'frames', 'social-sharing', 'weather-api', 'page');
    if (in_array($post->post_type, $post_type)) {
        unset($actions['trash']);
        unset($submenu['edit.php?post_type=agents'][10]);
    }
    return $actions;
}

if (is_admin()) {
    add_filter('post_row_actions', 'remove_trash', 10, 2);
}


/*
 *  functions to remove quick edit & trash for page
 */

function wpse16327_page_row_actions($actions, $post) {
    unset($actions['trash']);
    unset($actions['inline hide-if-no-js']);
    return $actions;
}

function remove_trash_bulk_actions($actions) {
    unset($actions['trash']);
    unset($actions['edit']);
    return $actions;
}

add_filter('page_row_actions', 'wpse16327_page_row_actions', 10, 2);

add_filter('bulk_actions-edit-page', 'remove_trash_bulk_actions');
add_filter('months_dropdown_results', '__return_empty_array');


/*
 * function to remove AddNew & search for all custom posts
 */

function hide_that_stuff() {
    if ('spa' == get_post_type() || 'pool' == get_post_type() || 'fitness-center' == get_post_type() ||
            'email-template' == get_post_type() || 'inroom-dining-menu' == get_post_type() || 'menu-category' == get_post_type() ||
            'area-map' == get_post_type() || 'property-map' == get_post_type() || 'banner-image' == get_post_type() || 'planning-a-meeting' == get_post_type() ||
            'slots' == get_post_type() || 'table-games' == get_post_type() || 'responsible-gaming' == get_post_type() || 'players-club' == get_post_type() || 'tutorials' == get_post_type() ||
            'frames' == get_post_type() || 'social-sharing' == get_post_type() || 'weather-api' == get_post_type())
        echo '<style type="text/css">
    #favorite-actions {display:none;}
    .add-new-h2{display:none;}
    .tablenav{display:none;}
    #post-search-input{display:none;}
    #search-submit{display:none;}   
    </style>';
}

add_action('admin_head', 'hide_that_stuff');


/*
 * function to remove AddNew & search(without pagination) for page
 */

function hide_stuff() {
    if ('page' == get_post_type())
        echo '<style type="text/css">
    #favorite-actions {display:none;}
    .add-new-h2{display:none;}    
    #post-search-input{display:none;}
    #search-submit{display:none;}
    .vers{display:none;}
    .post-com-count-wrapper{display:none;}
    </style>';
}

add_action('admin_head', 'hide_stuff');

/*
 * function for food category
 */

function hide_food_category() {
    echo '<style type="text/css">    
    #tagsdiv-food_category{display:none;}
    </style>';
}

add_action('admin_head', 'hide_food_category');


/*
 * function to hide Move to Trash in publishing section for all custom posts
 */

function hide_publishing_actions() {
    $post_type = array('spa', 'pool', 'fitness-center', 'email-template', 'inroom-dining-menu',
        'area-map', 'property-map', 'banner-image', 'menu-category', 'planning-a-meeting',
        'slots', 'table-games', 'responsible-gaming', 'players-club', 'tutorials',
        'frames', 'social-sharing', 'weather-api', 'page');
    global $post;
    if (in_array($post->post_type, $post_type)) {
        echo '
                <style type="text/css">                    
                    #delete-action{
                        display:none;
                    }
                </style>
            ';
    }
}

add_action('admin_head-post.php', 'hide_publishing_actions');
add_action('admin_head-post-new.php', 'hide_publishing_actions');


/*
 * function to hide Move to Trash in publishing section & description for page
 */

function hide_publishing() {
    $post_type = array('page');
    global $post;
    if (in_array($post->post_type, $post_type)) {
        echo '
                <style type="text/css">   
                    #inline hide-if-no-js{
                        display:none;
                    }
                    #pageparentdiv{
                        display:none;
                    }
                    #postimagediv{
                        display:none;
                    }
                    #wp-content-editor-tools{
                        display:none;
                    }
                    #mceu_29-body{
                        display:none;
                    }
                    #mceu_31-body{
                        display:none;
                    }
                    #mceu_28-body{
                        display:none;
                    }
                    #mceu_34{
                        display:none;
                    }
                    #content_ifr{
                        display:none;
                    }
                    #mceu_33{
                        display:none;
                    }
                    #post-status-info{
                        display:none;
                    }
                    #wp-content-editor-container{
                        display:none;
                    }
                </style>
            ';
    }
}

add_action('admin_head-post.php', 'hide_publishing');
add_action('admin_head-post-new.php', 'hide_publishing');


/*
 * function to hide permalink for non admin users
 */

function hide_permalink() {
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        if ($user->roles[0] <> 'administrator') {
            echo '<style type="text/css">    
     #edit-slug-box{display:none;} 
    </style>';
        }
    }
}

add_action('admin_head', 'hide_permalink');


/*
 * function to update category name for posts in in-room-ordering
 */

function set_my_categories() {
    global $post;
    global $wpdb;
    if ($post->post_type == 'in-room-ordering') {
        if ($post->ID) {
            $category = get_post_custom_values('category', $post->ID);
            $cat_id = $category[0];
            $sql = "SELECT name FROM wp_terms WHERE term_id = " . $cat_id;
            $results = $wpdb->get_results($sql);
            $category = $results[0]->name;
            wp_set_post_terms($post->ID, $category, 'food_category');
        }
    }
}

add_action('save_post', 'set_my_categories');


/*
 * function to restrict input types for images
 */

function restrict_mime($mimes) {
    $mimes = array('jpg|jpeg|jpe' => 'image/jpeg',
        'png' => 'image/png',
    );
    return $mimes;
}

add_filter('upload_mimes', 'restrict_mime');


/*
 * function to update the timestamp for events
 */

function set_my_events() {
    global $post;
    global $wpdb;
    if ($post->post_type == 'todays-events') {
        if ($post->ID) {
            $post_meta = get_metadata('post', $post->ID);
            $wpdb->query(
                    $wpdb->prepare(
                            "
                DELETE FROM wp_events 
		 WHERE post_id = %d
		 ", $post->ID
                    )
            );
            $wpdb->insert(
                    'wp_events', array(
                'post_id' => $post->ID,
                'start_date' => empty($post_meta['start_date'][0]) ? "" : $post_meta['start_date'][0],
                'start_time' => empty($post_meta['start_time'][0]) ? "" : $post_meta['start_time'][0],
                'end_time' => empty($post_meta['end_time'][0]) ? "" : $post_meta['end_time'][0]
                    ), array(
                '%d',
                '%s',
                '%s',
                '%s',
                    )
            );
        }
    }
}

add_action('save_post', 'set_my_events');


/*
 * function to update a single field monthyear for activity guide
 */

function set_my_activities() {
    global $post;
    global $wpdb;
    if ($post->post_type == 'activity-guide') {
        if ($post->ID) {
            $post_meta = get_metadata('post', $post->ID);
            if (empty($post_meta['monthandyear'][0])) {
                add_post_meta($post->ID, 'monthandyear', $post_meta['month'][0] . ' ' . $post_meta['year'][0]);
            } else {
                update_post_meta($post->ID, 'monthandyear', $post_meta['month'][0] . ' ' . $post_meta['year'][0]);
            }
        }
    }
}

add_action('save_post', 'set_my_activities');


/*
 * function to set title as mandatory & restricting max limit for posts in in-room-ordering
 */

function custom_admin_js() {
    global $typenow;
    $screen = get_current_screen();
    $post_id = $_GET['post'];
    $action = $_GET['action'];
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
    <?php
    if ($screen->post_type == 'in-room-ordering') {
        ?>
                $('#titlediv').prepend('<p><strong>Title (Enter 50 characters only)</strong><strong style="color:#cc0000;">*</strong></p>');
    <?php } else { ?>
                $('#titlediv').prepend('<p><strong>Title </strong><strong style="color:#cc0000;">*</strong></p>');
    <?php } ?>
        });
    </script>     

    <script  type="text/javascript">
        jQuery(document).ready(function() {
    <?php if ($screen->post_type == 'page') { ?>
                if (jQuery('#post-query-submit').length)
                    jQuery('#post-query-submit').hide();
    <?php } ?>
            jQuery('#post').submit(function() {
    <?php
    if ($screen->post_type == 'in-room-ordering') {
        ?>
                    if ((jQuery("#title").val().length > 0) && (jQuery("#title").val().length < 51)) {
                        jQuery('#ajax-loading').hide();
                        jQuery('#title').removeAttr("style");
                        jQuery('#publish').removeClass('button-primary-disabled');
                        return true;
                    }
                    else {
                        jQuery('#title').css("border", "1px solid #cc0000");
                        jQuery('#title').css("background-color", "#ffebe8 ");
                        jQuery('#ajax-loading').hide();
                        jQuery('.spinner').hide();
                        jQuery('#publish').removeClass('button-primary-disabled');
                        return false;
                    }
    <?php } else {
        ?>
                    if (jQuery("#title").val().length > 0) {
                        jQuery('#ajax-loading').hide();
                        jQuery('#title').removeAttr("style");
                        jQuery('#publish').removeClass('button-primary-disabled');
                        return true;
                    }
                    else {
                        jQuery('#title').css("border", "1px solid #cc0000");
                        jQuery('#title').css("background-color", "#ffebe8 ");
                        jQuery('#ajax-loading').hide();
                        jQuery('.spinner').hide();
                        jQuery('#publish').removeClass('button-primary-disabled');
                        return false;
                    }
    <?php } ?>
                return false;
            });
        });
    </script>
    <?php
}

add_action('admin_footer', 'custom_admin_js');

/*
 * function to set background color for LHS menu
 */

function my_custom_background() {
    echo '<style>
    .featured {
      background-color: #0074a2;
     
    } 
  </style>';
}

add_action('admin_head', 'my_custom_background');

/*
 * function to hide delete in custom taxonomy food_category
 */

function hide_taxonomy_actions() {
    $taxonomy = "food_category";
    $taxonomies = get_taxonomies();
    if ($taxonomies) {
        foreach ($taxonomies as $taxonomy) {
            if ($taxonomy->name == $taxonomy) {
                echo '
                <style type="text/css">                    
                    delete{
                        display:none;
                    }
                </style>
            ';
            }
        }
    }
}

add_action('admin_head-post.php', 'hide_taxonomy_actions');
add_action('admin_head-post-new.php', 'hide_taxonomy_actions');

/*
 * Change Pages label, singular, etc
 */

function change_post_object_label() {
    global $wp_post_types;
    $labels = &$wp_post_types['page']->labels;
    $labels->name = 'Dashboard Menu';
    $labels->singular_name = 'Dashboard Menu';
    $labels->add_new = 'Add Dashboard Menu';
    $labels->add_new_item = 'Add Dashboard Menu';
    $labels->edit_item = 'Edit Dashboard Menu';
    $labels->new_item = 'Dashboard Menu';
    $labels->view_item = 'View Dashboard Menu';
    $labels->search_items = 'Search Dashboard Menus';
    $labels->not_found = 'No Dashboard Menus found';
    $labels->not_found_in_trash = 'No Dashboard Menus found in Trash';
}

add_action('init', 'change_post_object_label');
