<?php

class CROCS_JSON_Posts extends WP_JSON_Posts {

    /**
     * Server object
     *
     * @var WP_JSON_ResponseHandler
     */
    protected $server;

    /**
     * Constructor
     *
     * @param WP_JSON_ResponseHandler $server Server object
     */
    public function __construct(WP_JSON_ResponseHandler $server) {
        $this->server = $server;
    }

    /**
     * Register the post-related routes
     *
     * @param array $routes Existing routes
     * @return array Modified routes
     */
    public function register_routes($routes) {
        $post_routes = array(
            // Post endpoints
            '/requests' => array(
                array(array($this, 'list_posts'), WP_JSON_Server::READABLE),
                array(array($this, 'new_post'), WP_JSON_Server::CREATABLE | WP_JSON_Server::ACCEPT_JSON),
            ),
            '/requests/(?P<id>\d+)' => array(
                array(array($this, 'display_post'), WP_JSON_Server::READABLE),
                array(array($this, 'edit_post'), WP_JSON_Server::EDITABLE | WP_JSON_Server::ACCEPT_JSON),
                array(array($this, 'delete_post'), WP_JSON_Server::DELETABLE),
            ),
            '/requests/(?P<id>\d+)/revisions' => array(
                array($this, 'get_revisions'), WP_JSON_Server::READABLE
            ),
            //meta sync
            '/requests/check_meta' => array(
                array($this, 'check_meta'), WP_JSON_Server::READABLE
            ),
            // Meta
            '/requests/history' => array(
                array(array($this, 'get_history'), WP_JSON_Server::READABLE),
            ),
            // Meta
            '/requests/taxonomies' => array(
                array(array($this, 'get_taxonomy_terms'), WP_JSON_Server::READABLE),
            ),
            // Meta
            '/requests/(?P<id>\d+)/meta' => array(
                array(array($this, 'get_all_meta'), WP_JSON_Server::READABLE),
                array(array($this, 'add_meta'), WP_JSON_Server::CREATABLE | WP_JSON_Server::ACCEPT_JSON),
            ),
            '/requests/(?P<id>\d+)/meta/(?P<mid>\d+)' => array(
                array(array($this, 'get_meta'), WP_JSON_Server::READABLE),
                array(array($this, 'update_meta'), WP_JSON_Server::EDITABLE | WP_JSON_Server::ACCEPT_JSON),
                array(array($this, 'delete_meta'), WP_JSON_Server::DELETABLE),
            ),
            // Comments
            '/requests/(?P<id>\d+)/comments' => array(
                array(array($this, 'get_comments'), WP_JSON_Server::READABLE),
            ),
            '/requests/(?P<id>\d+)/comments/(?P<comment>\d+)' => array(
                array(array($this, 'get_comment'), WP_JSON_Server::READABLE),
                array(array($this, 'delete_comment'), WP_JSON_Server::DELETABLE),
            ),
            // Meta-post endpoints
            '/requests/types' => array(
                array($this, 'get_post_types'), WP_JSON_Server::READABLE
            ),
            '/requests/types/(?P<type>\w+)' => array(
                array($this, 'get_post_type'), WP_JSON_Server::READABLE
            ),
            '/requests/statuses' => array(
                array($this, 'get_post_statuses'), WP_JSON_Server::READABLE
            ),
        );
        return array_merge($routes, $post_routes);
    }

    public function new_post($data) {
        if (!empty($data['date_time'])) {
            $date_array = explode('-', $data['date_time']);
            if (count($date_array) > 2) {
                $formatted_date = $date_array[1] . '-' . $date_array[0] . '-' . $date_array[2];
                $data['formatted_date_time'] = mysql2date('Y-m-d H:i', $formatted_date, 0);
            } else {
                $this->set_status(404);
                return array('message' => __('Invalid Date.'));
            }
        }
        $post = array();
        $post_types = array('make-up-room', 'request-pillows', 'turn-down-room', 'additional-amenities', 'set-wake-up-call',
            'cancel-wake-up-call', 'set-donot-disturb', 'cancel-donot-disturb', 'request-car', 'cancel-request-car',
            'request-lugguage', 'dining-tray', 'in-room-ordering');
        if (!in_array($data['type'], $post_types)) {
            $this->set_status(404);
            return array('message' => __('Invalid Type.'));
        }

        $validation = $this->validations($data);
        if (!$validation['flag']) {
            $this->set_status(404);
            return array('message' => $validation['message']);
        }

        $post_type = array("request-car", "set-wake-up-call", "set-donot-disturb");
        if (in_array($data['type'], $post_type) && !empty($data['date_time'])) {
            $post_id = $this->check_insert($data);
            if (!$post_id) {
                $post = $this->format($data);
                $post_id = $this->insert_post($post);
                $result_meta = $this->handle_post_meta_action($post_id, $post['post_meta']);
            } else {
                $this->set_status(404);
                return array('message' => __('Already set on this date.'));
            }
        }
        $result_mail = $this->send_mail($data);
        return array('message' => $this->get_customer_message($data));
    }

    protected function check_insert($data) {
        global $wpdb;
        $table = _get_meta_table('post');
        $sql = "SELECT distinct(p.ID) FROM " . $wpdb->posts . " as p "
                . " INNER JOIN " . $table . " as m ON m.post_id=p.ID and m.meta_value='" . $data['uid'] . "'"
                . " WHERE p.post_type LIKE %s";
        $results = $wpdb->get_results($wpdb->prepare($sql, $data['type']));
        $post_ids = array();
        $post_ids1 = array();
        foreach ($results as $row) {
            $post_ids[] = $row->ID;
        }

        $sql1 = "SELECT distinct post_id FROM $table WHERE post_id in ('" . implode("','", $post_ids) . "') and meta_value LIKE %s ";
        $results_set1 = $wpdb->get_results($wpdb->prepare($sql1, $data['room_number']));

        foreach ($results_set1 as $row1) {
            $post_ids1[] = $row1->post_id;
        }

        $sql2 = "SELECT post_id FROM $table WHERE post_id in ('" . implode("','", $post_ids1) . "') and meta_value LIKE %s ";
        $results_set2 = $wpdb->get_results($wpdb->prepare($sql2, $data['date_time']));

        if ($results_set2) {
            return true;
        }
        return false;
    }

    protected function insert_post($data) {
        $post_ID = wp_insert_post($data);
        return $post_ID;
    }

    protected function handle_post_meta_action($post_id, $data) {
        foreach ($data as $meta_array) {
            if (empty($meta_array['ID'])) {
                // Creation
                $result = $this->add_meta($post_id, $meta_array);
            }
        }
        return true;
    }

    protected function format($data) {
        $post['post_type'] = $data['type'];
        $post['post_title'] = $data['message'] . $data['room_no'];
        $post['post_status'] = $data['status'];
        $post['post_meta'] = array();
        $i = 1;
        foreach ($data as $key => $value) {
            $post['post_meta'][$i]['key'] = $key;
            $post['post_meta'][$i]['value'] = $value;
            $i++;
        }
        return $post;
    }

    public function validations($data) {
        $flag = true;
        if (empty($data['uid'])) {
            $flag = false;
            $message = "Invalid unique id.";
        }
        if (empty($data['room_number']) || strlen($data['room_number']) > 10) {
            $flag = false;
            $message = "Invalid room number.";
        }
        if (empty($data['name']) || strlen($data['name']) > 30) {
            $flag = false;
            $message = "Invalid name.";
        }

        if ($flag) {
            switch ($data['type']) {
                case 'make-up-room':
                    break;
                case 'request-pillows':
                    if (empty($data['pillows'])) {
                        $flag = false;
                        $message = "Invalid pillow.";
                    }
                    break;
                case 'turn-down-room':
                    break;
                case 'additional-amenities':
                    break;
                case 'set-wake-up-call':
                    if (empty($data['date_time']) || strlen($data['date_time']) > 20) {
                        $flag = false;
                        $message = "Invalid date time.";
                    }
                    break;
                case 'cancel-wake-up-call':
                    break;
                case 'set-donot-disturb':
                    if (empty($data['date_time']) || strlen($data['date_time']) > 20) {
                        $flag = false;
                        $message = "Invalid date time.";
                    }
                    break;
                case 'cancel-donot-disturb':
                    break;
                case 'request-car':
                    /* if (empty($data['date_time']) || strlen($data['date_time']) > 20) {
                      $flag = false;
                      $message = "Invalid date time.";
                      } */
                    break;
                case 'cancel-request-car':
                    break;
                case 'request-lugguage':
                    break;
                case 'request-dining-tray':
                    break;
                default :
                    $flag = true;
            }
        }
        return array('flag' => $flag, 'message' => $message);
    }

    /**
     * Send a HTTP status code
     *
     * @param int $code HTTP status
     */
    protected function set_status($code) {
        status_header($code);
    }

    public function add_meta($id, $data) {
        $id = (int) $id;

        if (empty($id)) {
            $this->set_status(404);
            return array('message' => __('Invalid post ID.'));
        }

        $post = get_post($id, ARRAY_A);

        if (empty($post['ID'])) {
            $this->set_status(404);
            return array('message' => __('Invalid post ID.'));
        }

        if (!array_key_exists('key', $data)) {
            $this->set_status(400);
            return array('message' => __('Missing meta key.'));
        }
        if (!array_key_exists('value', $data)) {
            $this->set_status(400);
            return array('message' => __('Missing meta value.'));
        }

        if (empty($data['key'])) {
            $this->set_status(400);
            return array('message' => __('Invalid meta key.'));
        }

        if (!$this->is_valid_meta_data($data['value'])) {
            // for now let's not allow updating of arrays, objects or serialized values.
            $this->set_status(400);
            return array('message' => __('Invalid provided meta data for action.'));
        }

        if (is_protected_meta($data['key'])) {
            $this->set_status(403);
            return array('message' => __('Forbidden Error.'));
        }

        $meta_key = wp_slash($data['key']);
        $value = wp_slash($data['value']);

        $result = add_post_meta($id, $meta_key, $value);

        if (!$result) {
            $this->set_status(400);
            return array('message' => __('Could not add post meta.'));
        }

        $response = json_ensure_response($this->get_meta($id, $result));

        if (is_wp_error($response)) {
            return $response;
        }

        $response->set_status(201);
        $response->header('Location', json_url('/posts/' . $id . '/meta/' . $result));

        return $response;
    }

    public function send_mail($data) {
        global $wpdb;
        $table = $wpdb->posts;
        $sql = "SELECT p.ID FROM " . $wpdb->posts . " as p "
                . " WHERE p.post_type LIKE %s AND post_name LIKE '" . $data['type'] . "' ";
        $results = $wpdb->get_results($wpdb->prepare($sql, 'email-template'));

        $post_id = 0;
        foreach ($results as $row) {
            $post_id = $row->ID;
        }
        $email_temp = get_metadata('post', $post_id);
        $to2 = $email_temp['to_emailaddress']['0'];
        $subject2 = $email_temp['subject']['0'];
        $message2 = $email_temp['message_header']['0'];

        //need to be completed with exact messages
        $message2 .= $this->get_admin_message($data);
        $message2 .= $email_temp['message_footer']['0'];
        add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
        $result2 = wp_mail($to2, $subject2, $message2);

        if (!empty($data['email'])) {
            $to1 = $data['email'];
            $subject1 = $data['message'] . " " . $data['room_no'];
            $message1 = $this->get_customer_message($data);
            $result1 = wp_mail($to1, $subject1, $message1);
        }
        return true;
    }

    public function get_customer_message($data) {
        $formatted_text = "";
        if (!empty($data['formatted_date_time'])) {
            $formatted_date = mysql2date('m-d-Y', $data['formatted_date_time'], 0);
            $formatted_time = mysql2date('h:i A', $data['formatted_date_time'], 0);
            $formatted_text = ' on ' . $formatted_date . ' at ' . $formatted_time;
        }
        switch ($data['type']) {
            case 'make-up-room':
                $message = 'Housekeeping has been notified';
                break;
            case 'request-pillows':
                $message = 'Guest service has been notified';
                break;
            case 'turn-down-room':
                $message = 'Housekeeping has been notified';
                break;
            case 'additional-amenities':
                $message = 'Guest service has been notified';
                break;
            case 'set-wake-up-call':
                if (!empty($data['status']) && $data['status'] == 'cancel')
                    $message = 'Your wake up call has been cancelled' . $formatted_text;
                else
                    $message = 'Your wake up call has been set' . $formatted_text;
                break;
            case 'cancel-wake-up-call':
                $message = 'Your wake up call has been cancelled' . $formatted_text;
                break;
            case 'set-donot-disturb':
                if (!empty($data['status']) && $data['status'] == 'cancel')
                    $message = 'Your do not disturb has been cancelled';
                else
                    $message = 'Your do not disturb has been set';
                break;
            case 'cancel-donot-disturb':
                $message = 'Your do not disturb has been cancelled';
                break;
            case 'request-car':
                if (!empty($data['status']) && $data['status'] == 'cancel')
                    $message = 'Your request for car has been cancelled' . $formatted_text;
                else
                    $message = 'Your request for car has been notified' . $formatted_text;
                break;
            case 'cancel-request-car':
                $message = 'Your request for car has been cancelled' . $formatted_text;
                break;
            case 'dining-tray':
                $message = 'Your request dining tray pickup has been notified';
                break;
            case 'in-room-ordering':
                $message = 'Your in room ordering has been notified';
                break;
            case 'request-lugguage':
                $message = 'Your request luggage has been notified' . $formatted_text;
                break;
        }
        return $message;
    }

    public function get_admin_message($data) {
        $message = '';
        if (!empty($data['name'])) {
            $message.= '<p><label>Last Name : </label>' . $data['name'] . '</p>';
        }
        if (!empty($data['room_number'])) {
            $message.= '<p><label>Room Number : </label>' . $data['room_number'] . '</p>';
        }
        if (!empty($data['formatted_date_time'])) {
            $formatted_date = mysql2date('m-d-Y', $data['formatted_date_time'], 0);
            $formatted_time = mysql2date('h:i A', $data['formatted_date_time'], 0);

            $message.= '<p><label>Date : </label>' . $formatted_date . '</p>';
            $message.= '<p><label>Time : </label>' . $formatted_time . '</p>';
        }
        if (!empty($data['pillows'])) {
            $message.= '<p><label>Number of Pillows : </label>' . $data['pillows'] . '</p>';
        }
        if (!empty($data['email'])) {
            $message.= '<p><label>Email Address : </label>' . $data['email'] . '</p>';
        }
        if (!empty($data['message'])) {
            $message.= '<p><label>Remarks : </label>' . $data['message'] . '</p>';
        }
        if (!empty($data['order_summary']) && !empty($data['item_count'])) {
            $message.='<table border="1" cellspacing="0" cellpadding="5">';
            $message.='<tr><td colspan="3" align="center"><strong> Order Summary </strong></td></tr>';
            $message.='<tr><td><strong> Item Name </strong></td>';
            $message.='<td><strong> Quantity </strong></td>';
            $message.='<td><strong> Price </strong></td></tr>';
            foreach ($data['order_summary'] as $order) {

                $message.='<tr><td>' . $order['item_name'] . '</td>';
                $message.='<td align="right">' . $order['qty'] . '</td>';
                $message.='<td align="right">' . $order['price'] . '</td></tr>';
            }
            $message.='<tr><td><strong>Total</strong> </td>';
            $message.='<td align="right"><strong>' . $data['order_qty'] . '</strong></td>';
            $message.='<td align="right"><strong>' . $data['order_total'] . '</strong></td></tr>';
            $message.='</table>';
        }
        return $message;
    }

    public function get_all_meta($id) {
        $id = (int) $id;

        if (empty($id)) {
            $this->set_status(404);
            return array('message' => __('Invalid post ID.'));
        }

        $post = get_post($id, ARRAY_A);

        if (empty($post['ID'])) {
            $this->set_status(404);
            return array('message' => __('Invalid post ID.'));
        }

        /* if ( ! $this->check_edit_permission( $post ) ) {
          return new WP_Error( 'json_cannot_edit', __( 'Sorry, you cannot edit this post' ), array( 'status' => 403 ) );
          } */

        global $wpdb;
        $table = _get_meta_table('post');
        $results = $wpdb->get_results($wpdb->prepare("SELECT meta_id, meta_key, meta_value FROM $table WHERE post_id = %d", $id));

        $meta = array();

        foreach ($results as $row) {
            $value = $this->prepare_meta($id, $row, true);

            if (is_wp_error($value)) {
                continue;
            }

            $meta[] = $value;
        }

        return apply_filters('json_prepare_meta', $meta, $id);
    }

    public function get_meta($id, $mid) {
        $id = (int) $id;

        if (empty($id)) {
            $this->set_status(404);
            return array('message' => __('Invalid post ID.'));
        }

        $post = get_post($id, ARRAY_A);

        if (empty($post['ID'])) {
            $this->set_status(404);
            return array('message' => __('Invalid post ID.'));
        }

        /* if ( ! $this->check_edit_permission( $post ) ) {
          return new WP_Error( 'json_cannot_edit', __( 'Sorry, you cannot edit this post' ), array( 'status' => 403 ) );
          } */

        $meta = get_metadata_by_mid('post', $mid);

        if (empty($meta)) {
            $this->set_status(404);
            return array('message' => __('Invalid post ID.'));
        }

        if (absint($meta->post_id) !== $id) {
            $this->set_status(400);
            return array('message' => __('Meta does not belong to this post.'));
        }

        return $this->prepare_meta($id, $meta);
    }

    /**
     * Delete a post for any registered post type
     *
     * @uses wp_delete_post()
     * @param int $id
     * @return true on success
     */
    public function delete_post($id, $force = false) {
        $id = (int) $id;

        if (empty($id)) {
            $this->set_status(404);
            return array('message' => __('Invalid Request ID.'));
        }

        $post = get_post($id, ARRAY_A);

        if (empty($post['ID'])) {
            $this->set_status(404);
            return array('message' => __('Invalid Request ID.'));
        }

        $post_type = get_post_type_object($post['post_type']);

//		if ( ! current_user_can( $post_type->cap->delete_post, $id ) ) {
//			return new WP_Error( 'json_user_cannot_delete_post', __( 'Sorry, you are not allowed to delete this post.' ), array( 'status' => 401 ) );
//		}

        $data = array();
        $result = get_metadata('post', $id);

        foreach ($result as $key => $value) {
            $data[$key] = empty($value[0]) ? "" : $value[0];
        }
        $data['status'] = 'cancel';

        if ($data['type'] == "set-wake-up-call") {
            $data['type'] = "cancel-wake-up-call";
        }
        if ($data['type'] == "set-donot-disturb") {
            $data['type'] = "cancel-donot-disturb";
        }
        if ($data['type'] == "request-car") {
            $data['type'] = "cancel-request-car";
        }
        $result_mail = $this->send_mail($data);
        //return $data;
        $result = wp_delete_post($id, $force);

        //mail function
        if (!$result) {
            $this->set_status(500);
            return array('message' => __('The post cannot be deleted.'));
        }
        return array('message' => $this->get_customer_message($data));

        if ($force) {
            return array('message' => __('Your request has been cancelled' . $post['post_type']));
        } else {
            // TODO: return a HTTP 202 here instead
            return array('message' => __('Your request has been cancelled' . $post['post_type']));
        }
    }

    /**
     * Retrieve custom fields for post.
     *
     * @param int $id Post ID
     * @return (array[]|WP_Error) List of meta object data on success, WP_Error otherwise
     */
    public function get_history($uid, $type) {

        if (empty($uid)) {
            $this->set_status(404);
            return array('message' => __('Invalid Unique ID.'));
        }

        //$post = get_post($id, ARRAY_A);

        if (empty($uid)) {
            $this->set_status(404);
            return array('message' => __('Invalid Unique ID.'));
        }

        if (!$this->check_edit_permission($post)) {
            //return new WP_Error( 'json_cannot_edit', __( 'Sorry, you cannot edit this post' ), array( 'status' => 403 ) );
        }

        global $wpdb;
        $table = _get_meta_table('post');
        $sql = "SELECT distinct(p.ID) FROM " . $wpdb->posts . " as p "
                . " INNER JOIN " . $table . " as m ON m.post_id=p.ID and m.meta_value='" . $uid . "'"
                . " WHERE p.post_type LIKE %s";
        $results = $wpdb->get_results($wpdb->prepare($sql, $type));
        $post_ids = array();
        foreach ($results as $row) {
            $post_ids[] = $row->ID;
        }
        //return array($post_ids);
        $sql = "SELECT post_id, meta_value, NOW() as n FROM $table "
                . "WHERE post_id in ('" . implode("','", $post_ids) . "') "
                . "and meta_key LIKE %s ";
        if ($type != 'set-donot-disturb') {
            $sql.= "and meta_value > now() ";
        }
        $sql.= "order by meta_value asc";
        //return array($sql);
        $results = $wpdb->get_results($wpdb->prepare($sql, 'formatted_date_time'));

        $meta = array();

        foreach ($results as $row) {
            $meta[] = array('post_id' => $row->post_id, 'date_time' => mysql2date('m-d-Y h:i A', $row->meta_value, 0));
        }
        return array('count' => count($meta), 'result' => $meta);
    }

    /**
     * Retrieve posts.
     *
     * @since 3.4.0
     *
     * The optional $filter parameter modifies the query used to retrieve posts.
     * Accepted keys are 'post_type', 'post_status', 'number', 'offset',
     * 'orderby', and 'order'.
     *
     * The optional $fields parameter specifies what fields will be included
     * in the response array.
     *
     * @uses wp_get_recent_posts()
     * @see WP_JSON_Posts::get_post() for more on $fields
     * @see get_posts() for more on $filter values
     *
     * @param array $filter Parameters to pass through to `WP_Query`
     * @param string $context
     * @param string|array $type Post type slug, or array of slugs
     * @param int $page Page number (1-indexed)
     * @return stdClass[] Collection of Post entities
     */
    public function list_posts($filter = array(), $context = 'view', $type = 'posts', $page = 1) {
        $query = array();
        if ($type != 'posts') {
            $screens = $this->get_screens();
            $screen_id = $type;
            $type = $screens[$type];
        } else {
            $this->set_status(404);
            return array('message' => __('Invalid Type Param.'));
        }

        if ($type == 'in-room-ordering') {
            $screen_attrs = $this->get_screen_attrs();
            //$struct = $this->get_taxonomy_terms('food_category');
            $struct = $this->get_menu_category('food_category');
            $total_menu_count = !empty($struct['total_menu_count']) ? $struct['total_menu_count'] : 0;
            unset($struct['total_menu_count']);
            $data = array('count' => count($struct),
                'total_menu_count' => $total_menu_count,
                'screen_id' => $screen_id,
                'screen_title' => $screen_attrs[$screen_id],
                'screen_image' => "",
                'currency' => "$",
                'result' => $struct
            );
            return $data;
        }

        if ($type == 'todays-events') {
            $screen_attrs = $this->get_screen_attrs();
            $struct = $this->get_list_events($type);
            $banner_data = $this->get_banner_image_by_slug('events');
            $data = array('count' => count($struct),
                'screen_id' => $screen_id,
                'screen_title' => $screen_attrs[$screen_id],
                'screen_image' => "",
                'banner_image' => $banner_data['image'],
                'description' => $banner_data['description'],
                'result' => $struct
            );
            return $data;
        }

        if ($type == 'activity-guide') {
            $screen_attrs = $this->get_screen_attrs();
            $struct = $this->get_list_activity_guide($type);
            $banner_data = $this->get_banner_image_by_slug('activity-guide');
            $data = array('count' => count($struct),
                'screen_id' => $screen_id,
                'screen_title' => $screen_attrs[$screen_id],
                'screen_image' => "",
                'banner_image' => $banner_data['image'],
                'description' => $banner_data['description'],
                'title' => 'Activities and Events',
                'weekday_title' => 'Week day Activities',
                'weekend_title' => 'Weekend Activities',
                'result' => $struct
            );
            return $data;
        }

        // Validate post types and permissions
        $query['post_type'] = array();

        foreach ((array) $type as $type_name) {
            $post_type = get_post_type_object($type_name);

            if (!( (bool) $post_type ) || !$post_type->show_in_json) {
                return new WP_Error('json_invalid_post_type', sprintf(__('The post type "%s" is not valid'), $type_name), array('status' => 403));
            }

            $query['post_type'][] = $post_type->name;
        }

        global $wp;

        // Allow the same as normal WP
        $valid_vars = apply_filters('query_vars', $wp->public_query_vars);

        // If the user has the correct permissions, also allow use of internal
        // query parameters, which are only undesirable on the frontend
        //
		// To disable anyway, use `add_filter('json_private_query_vars', '__return_empty_array');`

        if (current_user_can($post_type->cap->edit_posts)) {
            $private = apply_filters('json_private_query_vars', $wp->private_query_vars);
            $valid_vars = array_merge($valid_vars, $private);
        }

        // Define our own in addition to WP's normal vars
        $json_valid = array('posts_per_page');
        $valid_vars = array_merge($valid_vars, $json_valid);

        // Filter and flip for querying
        $valid_vars = apply_filters('json_query_vars', $valid_vars);
        $valid_vars = array_flip($valid_vars);

        // Exclude the post_type query var to avoid dodging the permission
        // check above
        unset($valid_vars['post_type']);

        foreach ($valid_vars as $var => $index) {
            if (isset($filter[$var])) {
                $query[$var] = apply_filters('json_query_var-' . $var, $filter[$var]);
            }
        }

        // Special parameter handling
        $query['paged'] = absint($page);
        $post_query = new WP_Query();
        $posts_list = $post_query->query($query);
        $response = new WP_JSON_Response();
        $response->query_navigation_headers($post_query);

        if (!$posts_list) {
            $response->set_data(array());
            return $response;
        }

        // holds all the posts data
        $struct = array();

        $response->header('Last-Modified', mysql2date('D, d M Y H:i:s', get_lastpostmodified('GMT'), 0) . ' GMT');

        foreach ($posts_list as $post) {
            $post = get_object_vars($post);

            // Do we have permission to read this post?
            if (!$this->check_read_permission($post)) {
                continue;
            }

            $response->link_header('item', json_url('/posts/' . $post['ID']), array('title' => $post['post_title']));
            $post_data = $this->prepare_post($post, $context);
            if (is_wp_error($post_data)) {
                continue;
            }
            $post_data = $this->format_get_data($post_data, $post['ID']);
            $struct[] = $post_data;
        }
        $screen_attrs = $this->get_screen_attrs();
        $data = array('count' => count($struct),
            'screen_id' => $screen_id,
            'screen_title' => $screen_attrs[$screen_id],
            'screen_image' => "",
            'result' => $struct);
        $response->set_data($data);
        return $response;
    }

    /**
     * Retrieve a post.
     *
     * @uses get_post()
     * @param int $id Post ID
     * @param array $fields Post fields to return (optional)
     * @return array Post entity
     */
    public function display_post($id, $context = 'view') {

        $id = (int) $id;

        if (empty($id)) {
            $this->set_status(404);
            return array('message' => __('Invalid Request ID.'));
        }

        $post = get_post($id, ARRAY_A);

        if (empty($post['ID'])) {
            $this->set_status(404);
            return array('message' => __('Invalid Request ID.'));
        }

        if (!$this->check_read_permission($post)) {
            //return new WP_Error('json_user_cannot_read', __('Sorry, you cannot read this post.'), array('status' => 401));
        }

        // Link headers (see RFC 5988)

        $response = new WP_JSON_Response();
        $response->header('Last-Modified', mysql2date('D, d M Y H:i:s', $post['post_modified_gmt']) . 'GMT');

        $post = $this->prepare_post($post, $context);

        if (is_wp_error($post)) {
            return $post;
        }
        foreach ($post['meta']['links'] as $rel => $url) {
            $response->link_header($rel, $url);
        }
        $post = $this->format_get_data($post, $id);
        $response->link_header('alternate', get_permalink($id), array('type' => 'text/html'));
        $response->set_data($post);

        return $response;
    }

    public function format_get_data($post_data, $id) {
        if ($post_data['type'] == 'frames') {
            return $this->format_generic_data($post_data, $id);
        }
        $result = get_metadata('post', $id);
        foreach ($result as $key => $value) {
            $post_data[$key] = empty($value[0]) ? "" : $value[0];
        }
        $data = array();
        if (!empty($post_data['ID'])) {
            $data['post_id'] = $post_data['ID'];
        } else {
            $data['post_id'] = "";
        }
        if (!empty($post_data['title'])) {
            $data['title'] = html_entity_decode($post_data['title'], ENT_QUOTES, "utf-8");
        } else {
            $data['title'] = "";
        }
        if (!empty($post_data['description'])) {
            $text = html_entity_decode($post_data['description'], ENT_QUOTES, "utf-8");
            $text = str_replace("\u00a0", "<br />", $text);
            $text = str_replace("\r\n", "<br />", $text);
            //$text = strip_tags($text);
            $data['description'] = $text;
            $data['short_description'] = substr(strip_tags($text), 0, 100);
        } else {
            $data['description'] = "";
            $data['short_description'] = "";
        }
        if (!empty($post_data['banner_image']) && $post_data['banner_image'] != null && $post_data['banner_image'] != 'null') {
            $img_attr = wp_get_attachment_image_src($post_data['banner_image'], 'full');
            $data['banner_image'] = (empty($img_attr[0]) || $img_attr[0] == 'null' || $img_attr[0] == null) ? "" : $img_attr[0];
        } else {
            $data['banner_image'] = "";
        }
        if (!empty($post_data['front_banner_image'])) {
            $img_attr = wp_get_attachment_image_src($post_data['front_banner_image'], 'full');
            $data['front_banner_image'] = $img_attr[0];
        } else {
            $data['front_banner_image'] = "";
        }
        if (!empty($post_data['logo'])) {
            $img_attr = wp_get_attachment_image_src($post_data['logo'], 'full');
            $data['logo'] = $img_attr[0];
        } else {
            $data['logo'] = "";
        }
        if (!empty($post_data['external_link'])) {
            $data['external_link'] = $post_data['external_link'];
        } else {
            $data['external_link'] = "";
        }
        if (!empty($post_data['map'])) {
            $img_attr = wp_get_attachment_image_src($post_data['map'], 'full');           
            $data['map'] = $img_attr[0];
        } else {
            $data['map'] = "";
        }
        if (!empty($post_data['menu'])) {
            $img_attr = wp_get_attachment_image_src($post_data['menu'], 'full');
            $data['menu'] = $img_attr[0];
        } else {
            $data['menu'] = "";
        }
        if (!empty($post_data['latitude'])) {
            $data['latitude'] = $post_data['latitude'];
        } else {
            $data['latitude'] = "";
        }
        if (!empty($post_data['longitude'])) {
            $data['longitude'] = $post_data['longitude'];
        } else {
            $data['longitude'] = "";
        }
        if (!empty($post_data['category'])) {
            $term = get_term($post_data['category'], 'food_category');
            $data['category'] = $term->name;
        } else {
            $data['category'] = "";
        }
        if (!empty($post_data['category_name'])) {
            $data['category'] = $post_data['category_name'];
        } else {
            $data['category'] = "";
        }
        if (!empty($post_data['price'])) {
            $data['price'] = $post_data['price'];
        } else {
            $data['price'] = "";
        }
        if (!empty($post_data['speed_dial'])) {
            $data['speed_dial'] = $post_data['speed_dial'];
        } else {
            $data['speed_dial'] = "";
        }
        if (!empty($post_data['user_name'])) {
            $data['user_name'] = $post_data['user_name'];
        } else {
            $data['user_name'] = "";
        }
        return $data;
    }

   /* public function format_generic_data($post_data, $id) {
        $fields_array = get_fields($id);
        if ($fields_array) {            
            foreach ($fields_array as $field_name => $value) {                
                if (!empty($value)) {                    
                    $img_attr = wp_get_attachment_image_src($value, 'full');                    
                    $image = (empty($img_attr[0]) || $img_attr[0] == 'null' || $img_attr[0] == null) ? "" : $img_attr[0];
                } else {
                    $image = "";
                }
                $fields_array[$field_name] = $image;
            }
        }
        return $fields_array;
    }*/
    
     public function format_generic_data($post_data, $id) {
        $fields_array = get_fields($id);
        if ($fields_array) {            
            foreach ($fields_array as $field_name => $value) {                               
                $fields_array[$field_name] = (empty($value['url']) || $value['url'] == 'null' || $value['url'] == null) ? "" : $value['url'];
            }
        }
        return $fields_array;
    }

    public function check_meta($timestamp) {
        global $wpdb;

        if (empty($timestamp)) {
            $this->set_status(404);
            return array('message' => __('Required Timestamp.'));
        }
        $date_time = date('Y-m-d H:i:s', $timestamp);

        $screens = $this->get_screens();
        $sql = "SELECT post_modified_gmt, post_type FROM `wp_posts` 
        WHERE post_type in ('" . implode("','", $screens) . "')  
        and post_modified_gmt > %s     
        and (post_status = 'inherit' or post_status = 'publish') 
        order by post_modified_gmt desc";
        $results = $wpdb->get_results($wpdb->prepare($sql, $date_time));

        $meta = array('timestamp' => (string) $timestamp, 'updates' => array());
        $i = 0;
        foreach ($results as $row) {
            if ($i == 0) {
                /* $meta['data']=(string)date('Y-m-d H:i');
                  $meta['data_time']=(string)strtotime(date('Y-m-d H:i'));
                  $meta['current_date'] = (string)$date_time;
                 */
                //$meta['date_time'] = $date_time;
                $meta['timestamp'] = (string) strtotime($row->post_modified_gmt);
            }
            $data = array_keys($screens, $row->post_type);
            if (!empty($data[0]) && !in_array($data[0], $meta['updates'])) {
                $meta['updates'][] = (string) $data[0];
            }
            $i++;
        }
        return $meta;
    }

    public function get_screens() {
        return array(
            '3100' => 'area-attractions',
            '3200' => 'shopping',
            '3300' => 'night-life',
            '3400' => 'fitness-center',
            '3500' => 'promotions',
            '3600' => 'pool',
            '3700' => 'spa',
            '4100' => 'slots',
            '4200' => 'table-games',
            '4300' => 'responsible-gaming',
            '4400' => 'players-club',
            '4500' => 'tutorials',
            '5400' => 'property-map',
            '5500' => 'area-map',
            '2110' => 'inroom-dining-menu',
            '2120' => 'in-room-ordering',
            '2200' => 'restaurants-and-bars',
            '5210' => 'planning-a-meeting',
            '5220' => 'todays-events',
            '5300' => 'activity-guide',
            '6000' => 'frames',
            '7000' => 'social-sharing'
                //'1008' => 'email-template',
        );
    }

    public function get_screen_attrs() {
        return array(
            '3100' => 'Area Attractions',
            '3200' => 'Shopping',
            '3300' => 'Nightlife',
            '3400' => 'Fitness Center',
            '3500' => 'Promotions',
            '3600' => 'Pool',
            '3700' => 'Spa',
            '4100' => 'Slots',
            '4200' => 'Table Games',
            '4300' => 'Responsible Gaming',
            '4400' => 'Players Club',
            '4500' => 'Tutorials',
            '5400' => 'Property Map',
            '5500' => 'Area map',
            '2110' => 'In Room Dining View Menu',
            '2120' => 'In Room Ordering',
            '2200' => 'Restaurants & Bars',
            '5210' => 'Planning a Meeting',
            '5220' => 'Today Events',
            '5300' => 'Activity Guide',
            '6000' => 'Frames',
            '7000' => 'Social Sharing'
                //'1008' => 'email-template',
        );
    }

    /**
     * Get all terms for a post type
     *
     * @param string $taxonomy Taxonomy slug
     * @return array Term collection
     */
    public function get_taxonomy_terms($taxonomy) {
        if (!taxonomy_exists($taxonomy)) {
            return new WP_Error('json_taxonomy_invalid_id', __('Invalid taxonomy ID.'), array('status' => 404));
        }

        $args = array(
            'hide_empty' => false,
            'orderby' => 'id',
            'order' => 'ASC',
        );
        $terms = get_terms($taxonomy, $args);
        if (is_wp_error($terms)) {
            return $terms;
        }
        $data = array();
        $terms_array = array();
        $term_loop = 0;
        $total_menu_count = 0;
        foreach ($terms as $term) {
            $args = array(
                'post_type' => 'in-room-ordering',
                'tax_query' => array(
                    //'relation' => 'AND',
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => array($term->slug)
                    ),
                )
            );

            $posts_array = get_posts($args);
            $post_data_array = array();
            foreach ($posts_array as $post) {
                if (is_wp_error($post)) {
                    continue;
                }
                $post = get_object_vars($post);
                $post_data = $this->prepare_post($post, $context);
                if (is_wp_error($post_data)) {
                    continue;
                }
                $post_data_array[] = $this->format_get_data($post_data, $post['ID']);
            }
            //if (count($post_data_array) > 0) {
            $terms_array[$term_loop]['title'] = $term->name;
            $terms_array[$term_loop]['description'] = $term->description;
            $term_meta = get_option($taxonomy . '_' . $term->term_id . '_image');
            if (empty($term_meta)) {
                $image = "";
            } else {
                $img_attr = wp_get_attachment_image_src($term_meta, 'full');
                $image = empty($img_attr[0]) ? "" : $img_attr[0];
            }
            $terms_array[$term_loop]['image'] = $image;
            $terms_array[$term_loop]['menu_count'] = count($post_data_array);
            $terms_array[$term_loop]['menus'] = $post_data_array;
            $term_loop++;
            $total_menu_count+= count($post_data_array);
            //   }
        }
        $terms_array['total_menu_count'] = $total_menu_count;
        return $terms_array;
    }

    /**
     * Get all terms for a post type
     *
     * @param string $taxonomy Taxonomy slug
     * @return array Term collection
     */
    public function get_menu_category($taxonomy) {
        if (!taxonomy_exists($taxonomy)) {
            return new WP_Error('json_Taxonomy_invalid_id', __('Invalid taxonomy ID.'), array('status' => 404));
        }

        $args = array(
            'post_type' => 'menu-category',
            'order' => 'ASC'
        );

        $menus = get_posts($args);
        $data = array();
        $menu_array = array();
        $menu_loop = 0;
        $total_menu_count = 0;

        global $wpdb;
        $table = $wpdb->posts;
        $metatable = _get_meta_table('post');

        foreach ($menus as $menu) {
            $sql = "SELECT post_id, post_title FROM $metatable as pm "
                    . " INNER JOIN $table AS p ON p.ID=pm.post_id "
                    . " WHERE pm.meta_key='category' AND pm.meta_value='%d' "
                    . " ORDER BY p.post_title asc";
            $results = $wpdb->get_results($wpdb->prepare($sql, $menu->ID));
            $post_data_array = array();
            foreach ($results as $post) {
                if (is_wp_error($post)) {
                    continue;
                }
                $args = array(
                    'post_type' => 'in-room-ordering',
                        //'ID' => $post->post_id
                );
                $post_data = array('ID' => $post->post_id, 'title' => $post->post_title, 'category_name' => $menu->post_title);
                $post_data_array[] = $this->format_get_data($post_data, $post->post_id);
                $post_list_array = get_posts($args);
                foreach ($post_list_array as $post_detail) {
                    $post = $post_detail;
                }

                $post = get_object_vars($post);
                $post_data = $this->prepare_post($post, $context);
                if (is_wp_error($post_data)) {
                    continue;
                }

                //$post_data_array[] = $this->format_get_data($post_data, $post['ID']);
            }
            $menu_array[$menu_loop]['title'] = $menu->post_title;
            $result = get_metadata('post', $menu->ID);
            $post_meta_data = array();
            foreach ($result as $key => $value) {
                $post_meta_data[$key] = empty($value[0]) ? "" : $value[0];
            }
            if (empty($post_meta_data['image'])) {
                $image = "";
            } else {
                $img_attr = wp_get_attachment_image_src($post_meta_data['image'], 'full');
                $image = empty($img_attr[0]) ? "" : $img_attr[0];
            }
            $menu_array[$menu_loop]['description'] = $post_meta_data['content'];
            $menu_array[$menu_loop]['image'] = $image;
            $menu_array[$menu_loop]['menu_count'] = count($post_data_array);
            $menu_array[$menu_loop]['menus'] = $post_data_array;
            $menu_loop++;
            $total_menu_count+= count($post_data_array);
        }
        $menu_array['total_menu_count'] = $total_menu_count;
        return $menu_array;
    }

    /**
     * Get all terms for a post type
     */
    public function get_list_events($type) {
        global $wpdb;
        $table = $wpdb->posts;
        $sql = "SELECT e.post_id, p.post_title FROM wp_events as e "
                . " INNER JOIN $table AS p ON p.ID=e.post_id "
                . " WHERE e.start_date = CURRENT_DATE() "
                . " ORDER BY e.start_date asc, e.start_time asc, e.end_time asc, p.post_title asc";
        $results = $wpdb->get_results($wpdb->prepare($sql, $type));
        $data = array();
        $i = 0;
        foreach ($results as $row) {
            $post_meta = get_metadata('post', $row->post_id);
            $data[$i]['title'] = empty($row->post_title) ? "" : $row->post_title;
            if (!empty($post_meta['start_time'][0]) && !empty($post_meta['end_time'][0])) {
                $data[$i]['time'] = date('h:i A', $post_meta['start_time'][0]) . ' - ' . date('h:i A', $post_meta['end_time'][0]);
            } else {
                $data[$i]['time'] = '';
            }
            $data[$i]['venue'] = empty($post_meta['venue'][0]) ? "" : $post_meta['venue'][0];
            $i++;
        }
        return $data;
    }

    /**
     * Get all terms for a post type
     */
    public function get_list_activity_guide($type) {
        global $wpdb;
        $month_arr = array(
            'January' => 'January',
            'February' => 'February',
            'March' => 'March',
            'April' => 'April',
            'May' => 'May',
            'June' => 'June',
            'July' => 'July',
            'August' => 'August',
            'September' => 'September',
            'October' => 'October',
            'November' => 'November',
            'December' => 'December',
        );
        $year_arr = array();
        $table = _get_meta_table('post');
        $sql = "SELECT distinct(m.meta_value) FROM " . $wpdb->posts . " as p "
                . " INNER JOIN  $table AS m ON p.ID=m.post_id and m.meta_key='year'"
                . " WHERE p.post_type LIKE %s ORDER BY m.meta_value ASC";

        //print $sql; exit();
        $results = $wpdb->get_results($wpdb->prepare($sql, $type));
        foreach ($results as $row) {
            $year_arr[] = $row->meta_value;
        }
        $data = array();
        //print_r($results);
        $k = 0;
        foreach ($year_arr as $year) {
            foreach ($month_arr as $month) {
                $activity_data = $this->activity_list($month, $year);
                if (count($activity_data['everyday']) > 0 || count($activity_data['weekdays']) > 0 || count($activity_data['weekends']) > 0) {
                    $data[$k]['monthandyear'] = $month . ' ' . $year;
                    $data[$k]['activity_data'] = $activity_data;
                    $k++;
                }
            }
        }
        return $data;
    }

    public function activity_list($month, $year) {
        global $wpdb;
        $table = _get_meta_table('post');
        //print $value;
        $sql1 = "SELECT m.post_id FROM "
                . " $table as m WHERE m.meta_key='monthandyear'"
                . " AND m.meta_value LIKE '%" . $month . "%' AND m.meta_value LIKE '%" . $year . "%'";
        //print $sql1;
        $results1 = $wpdb->get_results($sql1);
        $post_id = array();
        foreach ($results1 as $res) {
            $post_id[] = $res->post_id;
        }
        $data = $this->get_activity_list($post_id);
        return $data;
    }

    public function get_activity_list($post_id) {
        global $wpdb;
        $table = _get_meta_table('post');
        $count = count($day);
        $weekday = array('Everyday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $i = 0;
        $details = array();
        $details['everyday'] = array();
        $details['weekdays'] = array();
        $details['weekends'] = array();
        for ($i = 0; $i < count($weekday); $i++) {
            $sql1 = "SELECT p.ID, p.post_title FROM " . $table . " as m "
                    . " INNER JOIN  $wpdb->posts AS p ON p.ID=m.post_id "
                    . " WHERE m.post_id IN ('" . implode("','", $post_id) . "')"
                    . " AND m.meta_key='day' "
                    . " AND m.meta_value LIKE '%" . $weekday[$i] . "%' "
                    . " ORDER BY m.meta_value ASC, p.post_title ASC";
            $week = $weekday[$i];
            $results1 = $wpdb->get_results($sql1);
            if ($results1) {
                foreach ($results1 as $set_data) {
                    $flag = true;
                    foreach ($details['everyday'] as $epost_id) {
                        if ($epost_id['post_id'] == $set_data->ID) {
                            $flag = false;
                        }
                    }
                    if ($week == 'Everyday') {
                        $week_day = 'everyday';
                    } elseif ($week == 'Monday' || $week == 'Tuesday' || $week == 'Wednesday' || $week == 'Thursday') {
                        $week_day = 'weekdays';
                    } elseif ($week == 'Friday' || $week == 'Saturday' || $week == 'Sunday') {
                        $week_day = 'weekends';
                    }
                    if ($flag == true) {
                        array_push($details[$week_day], array('day' => $week, 'post_id' => $set_data->ID, 'title' => $set_data->post_title));
                    }
                }
            }
        }
        return $details;
    }

    public function get_banner_image_by_slug($slug) {
        $args = array(
            'name' => $slug,
            'post_type' => 'banner-image',
            'post_status' => 'publish',
            'showposts' => 1,
            'caller_get_posts' => 1
        );
        $my_posts = get_posts($args);
        $img_attr = wp_get_attachment_image_src($my_posts[0]->ID, 'full');
        $data = array();
        if (!empty($my_posts[0]->ID)) {
            $post_meta = get_metadata('post', $my_posts[0]->ID);
            //print_r($post_meta);exit();
            if (!empty($post_meta['banner_image'][0])) {
                $img_attr = wp_get_attachment_image_src($post_meta['banner_image'][0], 'full');
                $data['image'] = $img_attr[0];
            }
            if (!empty($post_meta['description'][0])) {
                $data['description'] = $post_meta['description'][0];
            } else {
                $data['description'] = "";
            }
        }
        return $data;
    }

}
