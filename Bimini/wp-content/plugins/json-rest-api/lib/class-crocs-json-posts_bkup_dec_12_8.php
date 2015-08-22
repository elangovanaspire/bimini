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
            'cancel-wake-up-call', 'set-donot-disturb', 'cancel-donot-disturb', 'request-car', 'cancel-request-car', 'request-lugguage');
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
        if (in_array($data['type'], $post_type)) {
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
        if (empty($data['room_number']) || strlen($data['room_number']) > 5) {
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
                    if (empty($data['date_time']) || strlen($data['date_time']) > 20) {
                        $flag = false;
                        $message = "Invalid date time.";
                    }
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
            case 'request-lugguage':
                $message = 'Your luggage pickup has been notified' . $formatted_text;
                break;
        }
        return $message;
    }

    public function get_admin_message($data) {
        if (!empty($data['name'])) {
            $message.= '<p><label>Last Name : </label>' . $data['name'] . '</p>';
        }
        if (!empty($data['room_number'])) {
            $message = '<p><label>Room Number : </label>' . $data['room_number'] . '</p>';
        }
        if (!empty($data['date_time'])) {
            $message.= '<p><label>Date Time : </label>' . $data['date_time'] . '</p>';
        }
        if (!empty($data['pillows'])) {
            $message.= '<p><label>Pillows : </label>' . $data['pillows'] . '</p>';
        }
        if (!empty($data['email'])) {
            $message.= '<p><label>Email Address : </label>' . $data['email'] . '</p>';
        }
        if (!empty($data['message'])) {
            $message.= '<p><label>Remarks : </label>' . $data['message'] . '</p>';
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
            $sql.= "and meta_value > NOW() ";
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
        $data = array('count'=>count($struct), 
                      'screen_id'=>$screen_id,                       
                      'screen_title'=>$screen_attrs[$screen_id],
                      'screen_image'=>"",
                      'result'=>$struct);       
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
            $data['title'] = $post_data['title'];
        } else {
            $data['title'] = "";            
        }
        if (!empty($post_data['description'])) {
            $data['description'] = $post_data['description'];
            $data['short_description'] = substr(strip_tags($post_data['description']), 0, 100);
        } else {
            $data['description'] = "";
            $data['short_description'] = "";
        }
        if (!empty($post_data['banner_image'])) {
            $img_attr = wp_get_attachment_image_src($post_data['banner_image'], 'full');
            $data['banner_image'] = $img_attr[0];
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
            $data['map'] = $post_data['map'];
        } else {
            $data['map'] = "";
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
        return $data;
    }

    public function check_meta($timestamp) {
        global $wpdb;     
        
        if(empty($timestamp)) {
            $this->set_status(404);
            return array('message' => __('Required Timestamp.'));
        }
        $date_time = date('Y-m-d H:i:s', $timestamp);
        
        $screens = $this->get_screens();
        $sql = "SELECT post_date, post_type FROM `wp_posts` 
        WHERE post_type in ('" . implode("','", $screens) . "')  
        and post_date_gmt > %s     
        and (post_status = 'inherit' or post_status = 'publish') 
        order by id desc";
        //return array($sql);
        $results = $wpdb->get_results($wpdb->prepare($sql, $date_time));

        $meta = array('timestamp'=>(string)$timestamp, 'updates' => array());
        $i = 0;
        foreach ($results as $row) {
            if ($i==0) {            
                /*$meta['data']=(string)date('Y-m-d H:i');
                $meta['data_time']=(string)strtotime(date('Y-m-d H:i'));
                $meta['current_date'] = (string)$date_time;
                */
                $meta['timestamp'] = (string)strtotime($row->post_date);
            }
            $data = array_keys($screens, $row->post_type);
            if (!empty($data[0]) && !in_array($data[0], $meta['updates'])) {
                $meta['updates'][] = (string)$data[0];
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
            //'2100' => 'restaurants-and-bar',
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
            //'2100' => 'Restaurants and bars',
            //'1008' => 'email-template',
        );
    }
}
