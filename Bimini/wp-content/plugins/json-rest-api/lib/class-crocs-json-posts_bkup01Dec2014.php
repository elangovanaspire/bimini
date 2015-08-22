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
                array(array($this, 'get_posts'), WP_JSON_Server::READABLE),
                array(array($this, 'new_post'), WP_JSON_Server::CREATABLE | WP_JSON_Server::ACCEPT_JSON),
            ),
            '/requests/(?P<id>\d+)' => array(
                array(array($this, 'get_post'), WP_JSON_Server::READABLE),
                array(array($this, 'edit_post'), WP_JSON_Server::EDITABLE | WP_JSON_Server::ACCEPT_JSON),
                array(array($this, 'delete_post'), WP_JSON_Server::DELETABLE),
            ),
            '/requests/(?P<id>\d+)/revisions' => array(
                array($this, 'get_revisions'), WP_JSON_Server::READABLE
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
            $post = $this->format($data);
            $post_id = $this->insert_post($post);
            $result_meta = $this->handle_post_meta_action($post_id, $post['post_meta']);
        }
        $result_mail = $this->send_mail($data);
        return array('message' => $this->get_customer_message($data));
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
            $subject1 = $data['message'] . "-" . $data['room_no'];
            $message1 = $this->get_customer_message($data);
            $result1 = wp_mail($to1, $subject1, $message1);
        }
        return true;
    }

    public function get_customer_message($data) {
        switch ($data['type']) {
            case 'make-up-room':
                $message = 'Housekeeping has been notified';
                break;
            case 'request-pillows':
                $message = 'Guest Service has been notified';
                break;
            case 'turn-down-room':
                $message = 'Housekeeping has been notified';
                break;
            case 'additional-amenities':
                $message = 'Guest Service has been notified';
                break;
            case 'set-wake-up-call':
                if (!empty($data['status']) && $data['status'] == 'cancel')
                    $message = 'Your Wakeup Call has been cancelled on ' . date($data['date_time']);
                else
                    $message = 'Your Wakeup Call has been set at ' . date($data['date_time']);
                break;
            case 'cancel-wake-up-call':
                $message = 'Your Wakeup Call has been cancelled on ' . date($data['date_time']);
                break;
            case 'set-donot-disturb':
                if (!empty($data['status']) && $data['status'] == 'cancel')
                    $message = 'Your Do not Disturb has been cancelled';
                else
                    $message = 'Your Do not Disturb has been set';
                break;
            case 'cancel-donot-disturb':
                $message = 'Your Do not Disturb has been cancelled';
                break;
            case 'request-car':
                if (!empty($data['status']) && $data['status'] == 'cancel')
                    $message = 'Your request for car has been cancelled on ' . date($data['date_time']);
                else
                    $message = 'Your request for car has been notified';
                break;
            case 'cancel-request-car':
                $message = 'Your request for car has been cancelled on ' . date($data['date_time']);
                break;
            case 'request-lugguage':
                $message = 'Your Luggage Pickup has been notified';
                break;
        }
        return $message;
    }

    public function get_admin_message($data) {
        if (!empty($data['status']) && $data['status'] == 'cancel') {
            global $wpdb;
            $table = _get_meta_table('post');
            $sql = "SELECT meta_key, meta_value FROM $table WHERE post_id = '" . $data['post_id'] . "'";
            $results = $wpdb->get_results($wpdb->prepare($sql, 'date_time'));

            foreach ($results as $row) {
                $data[$row->meta_key] = $data[$row->meta_value];
            }            
        }
        
        if (!empty($data['room_number'])) {
            $message = '<p><label>Room Number : </label>' . $data['room_number'] . '</p>';
        }
        if (!empty($data['name'])) {
            $message.= '<p><label>Name : </label>' . $data['name'] . '</p>';
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
        
        if($post['post_type']=='set-wake-up-call') {
            $post['post_type'] = 'cancel-wake-up-call';
        }
        if($post['post_type']=='set-donot-disturb') {
            $post['post_type'] = 'cancel-donot-disturb';
        }
        if($post['post_type']=='request-car') {
            $post['post_type'] = 'cancel-request-car';
        }
        $data = array('type' => $post['post_type'], 'status' => 'cancel', 'post_id' => $id);
        $result_mail = $this->send_mail($data);
        
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
        $sql = "SELECT post_id, meta_value FROM $table WHERE post_id in ('" . implode("','", $post_ids) . "') and meta_key LIKE %s order by meta_value asc limit 0, 5";
        $results = $wpdb->get_results($wpdb->prepare($sql, 'date_time'));

        $meta = array();

        foreach ($results as $row) {
            $meta[] = array('post_id' => $row->post_id, 'date_time' => $row->meta_value);
        }
        return array('count' => count($meta), 'result' => $meta);
    }

}
