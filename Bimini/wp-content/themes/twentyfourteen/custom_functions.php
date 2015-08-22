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
    $post_type = array('pacificsunsets', 'spa', 'shopping', 'night-life', 'promotions', 'pool', 'fitness-center', 'area-attractions', 'email-template',
        'inroom-dining-menu', 'restaurants-and-bars', 'in-room-ordering', 'menu-category',
        'area-map', 'property-map', 'banner-image', 'todays-events', 'activity-guide', 'planning-a-meeting',
        'slots', 'table-games', 'responsible-gaming', 'players-club', 'tutorials',
        'frames', 'social-sharing', 'weather-api', 'page','tram-schedule'); //tram-schedule added
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
    $post_type = array('pacificsunsets', 'spa', 'pool', 'fitness-center', 'email-template', 'inroom-dining-menu',
        'area-map', 'property-map', 'banner-image', 'menu-category', 'planning-a-meeting',
        'slots', 'table-games', 'responsible-gaming', 'players-club', 'tutorials',
        'frames', 'social-sharing', 'weather-api', 'page','tram-schedule');
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
    if ('page' == get_post_type() && $post->ID == 951) {
        unset($actions['edit']);
        unset($actions['view']);
        unset($actions['trash']);
        unset($actions['inline hide-if-no-js']);
    } else {
        unset($actions['trash']);
        unset($actions['inline hide-if-no-js']);
    }
    return $actions;
}

function remove_trash_bulk_actions($actions) {
    if ('page' == get_post_type() && $post->ID == 951) {
        unset($actions['edit']);
        unset($actions['view']);
        unset($actions['trash']);
        unset($actions['inline hide-if-no-js']);
    } else {
        unset($actions['trash']);
        unset($actions['inline hide-if-no-js']);
    }
    return $actions;
}

add_filter('page_row_actions', 'wpse16327_page_row_actions', 10, 2);

add_filter('bulk_actions-edit-page', 'remove_trash_bulk_actions');
add_filter('months_dropdown_results', '__return_empty_array');


/*
 * function to remove AddNew & search for all custom posts
 */

function hide_that_stuff() {
    if ('pacificsunsets'==get_post_type() || 'spa' == get_post_type() || 'pool' == get_post_type() || 'fitness-center' == get_post_type() ||
            'email-template' == get_post_type() || 'inroom-dining-menu' == get_post_type() || 'menu-category' == get_post_type() ||
            'area-map' == get_post_type() || 'property-map' == get_post_type() || 'banner-image' == get_post_type() || 'planning-a-meeting' == get_post_type() ||
            'slots' == get_post_type() || 'table-games' == get_post_type() || 'responsible-gaming' == get_post_type() || 'players-club' == get_post_type() || 'tutorials' == get_post_type() ||
            'frames' == get_post_type() || 'social-sharing' == get_post_type() || 'weather-api' == get_post_type() || 'tram-schedule' == get_post_type())
        echo '<style type="text/css">
    #favorite-actions {display:none;}
    .add-new-h2{display:none;}
    .tablenav{display:none;}
    #post-search-input{display:none;}
    #search-submit{display:none;}  
    .misc-pub-section{display:none;}
    .misc-pub-post-status{display:none;}
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
    .misc-pub-section{display:none;}
    .misc-pub-post-status{display:none;}
    #minor-publishing-actions{display:none;}
    </style>';
}

add_action('admin_head', 'hide_stuff');


/*
 * Function to hide version and timestamp in publishing options
 */

function hide() {
    echo '<style type="text/css">
    #visibility {display:none;}
    .curtime {display:none;}
    </style>';
}

add_action('admin_head', 'hide');


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
    $post_type = array('pacificsunsets', 'spa', 'pool', 'fitness-center', 'email-template', 'inroom-dining-menu',
        'area-map', 'property-map', 'banner-image', 'menu-category', 'planning-a-meeting',
        'slots', 'table-games', 'responsible-gaming', 'players-club', 'tutorials',
        'frames', 'social-sharing', 'weather-api', 'page','tram-schedule');
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
    echo '<style type="text/css">    
     #edit-slug-box{display:none;} 
    </style>';
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
        'pdf' => 'image/pdf'
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
    $title_mandatory = array('area-attractions', 'shopping', 'night-life', 'fitness-center', 'promotions', 'pool', 'spa','pacificsunsets',
        'menu-category', 'restaurants-and-bars', 'planning-a-meeting', 'todays-events', 'activity-guide',
        'slots', 'table-games', 'responsible-gaming', 'players-club', 'tutorials','tram-schedule');
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
    <?php
    if ($screen->post_type == 'in-room-ordering') {
        ?>
                $('#titlediv').prepend('<p><strong>Title (Enter below 50 characters)</strong><strong style="color:#cc0000;">*</strong></p>');
    <?php } elseif (in_array($screen->post_type, $title_mandatory)) { ?>
                $('#titlediv').prepend('<p><strong>Title </strong><strong style="color:#cc0000;">*</strong></p>');
    <?php } else { ?>
                $('#titlediv').prepend('<p><strong>Title </strong></p>');
    <?php } ?>
        });
    </script>     

    <script  type="text/javascript">
        jQuery(document).ready(function() {
    <?php //if ($screen->post_type == 'page') {   ?>
            if (jQuery('#post-query-submit').length)
                jQuery('#post-query-submit').hide();
    <?php //}   ?>
            jQuery('#post').submit(function() {
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
    .attachment-info .details .edit-attachment {
     display:none;
    }
    .attachment-info .details .delete-attachment {
     display:none;
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


/*
 * Functions to add Month & year & to make as 2nd column in list view for Activity Guide -- starts here
 * Functions to add Date & to make as 2nd column in list view for Todays Events -- starts here
 */

function ST4_get_featured_text($post_ID) {
    if ('activity-guide' == get_post_type()) {
        $meta_data = get_metadata('post', $post_ID);
        if (!empty($meta_data) && count($meta_data) > 0) {
            $month = $meta_data['month'];
            $year = $meta_data['year'];
            $month = maybe_unserialize($month[0]);
            $data = implode(", ", $month) . " " . $year[0];
        } else {
            $data = "";
        }
    } elseif ('todays-events' == get_post_type()) {
        $meta_data = get_metadata('post', $post_ID, 'start_date');
        if (!empty($meta_data) && count($meta_data) > 0) {
            $data = date('m/d/Y', strtotime($meta_data[0]));
        } else {
            $data = "";
        }
    }
    return $data;
}

function ST4_columns_head($defaults) {
    if ('activity-guide' == get_post_type()) {
        $defaults = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title'),
            'featured_text' => __('Month & Year'),
            'date' => __('Date')
        );
    } elseif ('todays-events' == get_post_type()) {
        $defaults = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title'),
            'featured_text' => __('Event Date'),
            'date' => __('Date')
        );
    } else {
        $defaults = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title'),
            'date' => __('Date')
        );
    }
    return $defaults;
}

function ST4_columns_content($column_name) {
    global $post;
    if ($post->ID) {
        if ($column_name == 'featured_text') {
            $post_featured_text = ST4_get_featured_text($post->ID);
            if ($post_featured_text) {
                echo $post_featured_text;
            }
        }
    }
}

add_filter('manage_posts_columns', 'ST4_columns_head');
add_action('manage_posts_custom_column', 'ST4_columns_content', 10, 1);

/*
 * Functions to add Month & year & to make as 2nd column in list view for Activity Guide -- ends here
 * Functions to add Date & to make as 2nd column in list view for Todays Events -- ends here
 */


/*
 * Function to remove private & draft from status
 */

function status_changing() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("a.editinline").live("click", function() {
                var ilc_qe_id = inlineEditPost.getId(this);
                setTimeout(function() {
                    $('#edit-' + ilc_qe_id + ' select[name="_status"] option[value="draft"]').remove();
                    $('#edit-' + ilc_qe_id + ' select[name="_status"] option[value="private"]').remove();
                    $('#edit-' + ilc_qe_id + ' select[name="_status"] option[value="pending"]').remove();
                    $('#edit-' + ilc_qe_id + ' select[name="_status"]').append(new Option("Unpublished", "pending"));
                }, 100);
            });

            $('#doaction, #doaction2').live("click", function() {
                setTimeout(function() {
                    $('#bulk-edit select[name="_status"] option[value="draft"]').remove();
                    $('#bulk-edit select[name="_status"] option[value="private"]').remove();
                    $('#bulk-edit select[name="_status"] option[value="pending"]').remove();
                    $('#bulk-edit select[name="_status"]').append(new Option("Unpublished", "pending"));
                }, 100);
            });
        });
    </script>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#post_status option[value="draft"]').remove();
            $('#post_status option[value="private"]').remove();
            $('#post_status option[value="pending"]').remove();
            $('#post_status').append(new Option("Unpublished", "pending"));
        });
    </script>
    <?php
}

add_action('admin_head', 'status_changing');

/*
 * Function to track Delete permanently for listing screen data
 */
add_action('before_delete_post', 'track_permanent_delete');

function track_permanent_delete($postid) {
    // We check if the global post type isn't ours and just return
    global $post_type;
    global $wpdb;
    $screen_data = array("area-attractions" => "3100", "shopping" => "3200", "night-life" => "3300", "promotions" => "3500",
        "in-room-ordering" => "2120", "restaurants-and-bars" => "2200", "todays-events" => "5220", "activity-guide" => "5300");
    if (array_key_exists($post_type, $screen_data)) {
        $wpdb->insert(
                'wp_track_deleted', array(
            'post_type_screen_id' => $screen_data[$post_type],
            'post_deleted_date' => date('Y-m-d H:i:s')
                ), array(
            '%s',
            '%s')
        );
    }
    if ($post_type == 'todays-events') {
        $wpdb->query(
                $wpdb->prepare(
                        "
                DELETE FROM wp_events 
		 WHERE post_id = %d
		 ", $postid
                )
        );
    }
    return;
}
