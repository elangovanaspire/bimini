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

    /**
     * This function used to post service. It provides the post service for housekeeping, set donot disturb, wakeup call, in room ordering, etc.
     * This function fetch the data from payload usng @param $data.
     * Insert the data into appropriate post type.
     * Sends the email to customer and admin.
     * Send the response which same like as customer message.
     */
    public function new_post($data) {
        //Validate the date time format
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
        //Validate the post type
        if (!in_array($data['type'], $post_types)) {
            $this->set_status(404);
            return array('message' => __('Invalid Type.'));
        }

        $validation = $this->validations($data);
        if (!$validation['flag']) {
            $this->set_status(404);
            return array('message' => $validation['message']);
        }
        //Check the data to insert. If the post type is in below array, need to store. 
        //Otherwise, just need to send the email to customer and alert message.
        $post_type = array("request-car", "set-wake-up-call", "set-donot-disturb");
        //insert data
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
        //Call send email function
        $result_mail = $this->send_mail($data);
        //return response
        return array('message' => $this->get_customer_message($data));
    }

    /*
     * This function check the duplicate data using the @param $data.
     */

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

    /*
     * insert post
     */

    protected function insert_post($data) {
        $post_ID = wp_insert_post($data);
        return $post_ID;
    }

    /*
     * Default service - hanndle post meta action 
     */

    protected function handle_post_meta_action($post_id, $data) {
        foreach ($data as $meta_array) {
            if (empty($meta_array['ID'])) {
                // Creation
                $result = $this->add_meta($post_id, $meta_array);
            }
        }
        return true;
    }

    /*
     * This function format the data and send to new post to insert data. Change payload to wordpress post type
     */

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

    /*
     * This function used to Validate the fields like room_number, name, email, etc based on the post type.
     * This function call from new post. Based on this insert will happen.
     */

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

    /*
     * Default service to add meta
     */

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

    /*
     * This function used to send email to customer and admin. It call from new_post function.
     */

    public function send_mail($data) {
        global $wpdb;
        $table = $wpdb->posts;

        //Fetch email template post id
        $sql = "SELECT p.ID FROM " . $wpdb->posts . " as p "
                . " WHERE p.post_type LIKE %s AND post_name LIKE '" . $data['type'] . "' ";
        $results = $wpdb->get_results($wpdb->prepare($sql, 'email-template'));

        $post_id = 0;
        foreach ($results as $row) {
            $post_id = $row->ID;
        }
        //Based on the email template post id, fetch the meta data.
        $email_temp = get_metadata('post', $post_id);

        $to2 = $email_temp['to_emailaddress']['0'];
        $subject2 = $email_temp['subject']['0'];        
        $message2 = str_replace("\r\n",'<br \>',trim($email_temp['message_header']['0']));        
        $temp_message = $this->get_admin_message($data);
        $message2 .= $temp_message;        
        $message2 .= str_replace("\r\n",'<br \>',trim($email_temp['message_footer']['0']));
        //echo strip_tags($email_temp['message_footer']['0'],'\r\n');
        //Call wp_mail and send email to admin
        add_filter('wp_mail_content_type', create_function('', 'return "text/html"; '));
        $result2 = wp_mail($to2, $subject2, $message2);

        //Call wp_mail and send email to customer
        if (!empty($data['email'])) {
            $to1 = $data['email'];
            $subject1 = $data['message'] . " " . $data['room_no'];
            $customer_name = (empty($data['name'])) ? 'Customer' : $data['name'];
            $message1 = "Dear ".$customer_name.",<br><br>";
            $message1 .= $this->get_customer_message($data).". Please find the below details<br>";
            $message1 .= $temp_message;
            $message1 .= "Thank you for requesting the service, we will get back to you shortly.<br><br>";
            $message1 .= "Regards,<br>Crocs Admin";
            $result1 = wp_mail($to1, $subject1, $message1);
        }
        return true;
    }

    /*
     * This function which return the customer message depends on the post type or post service.
     * This function call from new_post and delete_post. This message only send to the customer's email and response for the service.
     */

    public function get_customer_message($data) {
         $formatted_text = "";
        if (!empty($data['formatted_date_time'])) {
            $formatted_date = mysql2date('m-d-Y', $data['formatted_date_time'], 0);
            $formatted_time = mysql2date('h:i A', $data['formatted_date_time'], 0);
            $formatted_text = $formatted_date . ' at ' . $formatted_time;
        }
        switch ($data['type']) {
            case 'make-up-room':
                $message = 'Housekeeping has been notified';
                break;
            case 'request-pillows':
                $message = 'Guest Services has been notified';
                break;
            case 'turn-down-room':
                $message = 'Housekeeping has been notified';
                break;
            case 'additional-amenities':
                $message = 'Guest Services has been notified';
                break;
            case 'set-wake-up-call':
                if (!empty($data['status']) && $data['status'] == 'cancel')
                    $message = 'Your wake up call has been cancelled for ' . $formatted_text;
                else
                    $message = 'Your wake up call has been set for ' . $formatted_text;
                break;
            case 'cancel-wake-up-call':
                $message = 'Your wake up call has been cancelled for ' . $formatted_text;
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
                    $message = 'Your car request has been cancelled on ' . $formatted_text;
                else
                {
                    if(!empty($formatted_text))
                    $message = 'Your car request has been submitted for ' . $formatted_text;
                    else
                        $message = 'Your car request has been submitted on '. date('m-d-Y') . ' at ' . date('h:i A');
                }
                break;
            case 'cancel-request-car':
                $message = 'Your car request has been cancelled on ' . $formatted_text;
                break;
            case 'dining-tray':
                $message = 'Your dining tray pick up has been requested';
                break;
            case 'in-room-ordering':
                $message = 'Your in-room dining order has been placed';
                break;
            case 'request-lugguage':
                if(!empty($formatted_text))
                    $message = 'Your luggage pick up has been scheduled for ' . $formatted_text;
                else
                    $message = 'Your luggage pick up request has been sent on '. date('m-d-Y') . ' at ' . date('h:i A');
                break;
        }
        return $message;
    }

    /*
     * This function used to format admin message and it is call from new_post and delete_post.
     */

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
        $message.='<br>';
        return $message;
    }

    /*
     * Default service function
     */

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

    /*
     * Default service function
     */

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
     * @is_delete_all boolean - which deletes all the reminders.
     * @uid string unique id of device.
     * @return true on success
     */
    public function delete_post($id, $force = false, $is_delete_all = false, $uid = "") {
        $id = (int) $id;
        $meta = array();
        global $wpdb;
        if ($is_delete_all == true) {
            $table = _get_meta_table('post');
            $sql = "SELECT distinct(post_id) FROM $table "
                    . " INNER JOIN $wpdb->posts ON ID=post_id AND post_type='set-donot-disturb' "
                    . " WHERE meta_key = 'uid' AND meta_value=%s";
            $results = $wpdb->get_results($wpdb->prepare($sql, $uid));
            foreach ($results as $row) {
                $meta[] = $row->post_id;
            }
            //return $meta;
            $id = count($meta) > 0 ? $meta[0] : 0;
        }

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
        if ($is_delete_all == true && count($meta) > 0) {
            foreach ($meta as $value) {
                $result = wp_delete_post($value, $force);
            }
        } else {
            $result = wp_delete_post($id, $force);
        }

        //mail function
        if (!$result) {
            $this->set_status(500);
            return array('message' => __('The post cannot be deleted.'));
        }
        return array('message' => $this->get_customer_message($data));
    }

    /**
     * Retrieve custom fields for post.
     *
     * @param int $id Post ID
     * @param uid string Which used to find the unique device uid.
     * @param type string Post Type
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
        //Block screen ids
        $internal_menu_screens = array(
            "100",
            "1100",
            "1200",
            "1300",
            "1400",
            "1500",
            "2100",
            "5200"
        );
        //swap type to screen id and screen_id to type
        if ($type != 'posts') {
            $screens = $this->get_screens();
            $screen_id = $type;
            $type = $screens[$type];
        } else {
            $this->set_status(404);
            return array('message' => __('Invalid Type Param.'));
        }

        //Redirect to other function and return weather api service
        if ($type == 'weather-api') {
            $screen_attrs = $this->get_screen_attrs();
            $struct = $this->get_weather($type);
            $data = array('count' => count($struct),
                'screen_id' => $screen_id,
                'screen_title' => $screen_attrs[$screen_id],
                'screen_image' => "",
                'result' => $struct
            );
            return $data;
        }

        //Redirect to other function and return dashboard and screen service
        if (in_array($screen_id, $internal_menu_screens)) {
            /* if ($parent == '9301') {
              return $this->get_screens_data();
              } */
            $screen_attrs = $this->get_screen_attrs();
            $screen_image = "";

            //$page_id = $this->get_ID_by_page_slug('dashboard');

            $page_id = $this->get_post_id_by_screen_id($screen_id);
            $screen_image_data = get_field('screen_image', $page_id);
            $screen_image = (empty($screen_image_data['url']) || $screen_image_data['url'] == 'null' || $screen_image_data['url'] == null) ? "" : $screen_image_data['url'];
//            if($screen_id=='2100' || $screen_id=='1500') {
//                return array($page_id);
//            }
            $struct = $this->get_screen_content($page_id);
            $data = array('count' => count($struct),
                'screen_id' => $screen_id,
                'screen_title' => $screen_attrs[$screen_id],
                'screen_image' => $screen_image,
                'result' => $struct
            );
            return $data;
        }

        //Redirect to other function and return in room ordering service
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

        //Redirect to other function and return todays events service
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

        //Redirect to other function and return activity guide service
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
        $query['posts_per_page'] = -1;
        $post_query = new WP_Query();
        $posts_list = $post_query->query($query);
        $response = new WP_JSON_Response();
        $response->query_navigation_headers($post_query);

        /* if (!$posts_list) {
          $response->set_data(array());
          return $response;
          } */

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
            $post_data['tag_name'] = $post['post_name'];
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

    /*
     * Get post id by using @param $screen_id
     */

    public function get_post_id_by_screen_id($screen_id) {
        global $wpdb;
        $table = _get_meta_table('post');
        $results = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $table WHERE meta_key = 'screen_id' and meta_value = %d ORDER BY post_id ASC LIMIT 0,1", $screen_id));
        $post_id = "";
        foreach ($results as $row) {
            $post_id = $row->post_id;
        }
        return $post_id;
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

    /*
     * Format get response. This function calls from list posts.
     */

    public function format_get_data($post_data, $id) {
        $upload_dir = wp_upload_dir();
        if ($post_data['type'] == 'frames') { //Redirect to other function and format the frame post type and return.
            return $this->format_generic_data($id);
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
            $noimage = $upload_dir['baseurl'] . "/noimage/noimage_1441_450.png";
            $img_attr = wp_get_attachment_image_src($post_data['banner_image'], 'full');
            $data['banner_image'] = (empty($img_attr[0]) || $img_attr[0] == 'null' || $img_attr[0] == null) ? $noimage : $img_attr[0];
        } else {
            $data['banner_image'] = "";
        }
        if (!empty($post_data['front_banner_image'])) {
            if ($post_data['type'] == 'promotions') {
                $noimage = $upload_dir['baseurl'] . "/noimage/noimage_468_282.png";
            } else if ($post_data['type'] == 'area-attractions') {
                $noimage = $upload_dir['baseurl'] . "/noimage/noimage_340_276.png";
            } else {
                $noimage = "";
            }
            $img_attr = wp_get_attachment_image_src($post_data['front_banner_image'], 'full');
            $data['front_banner_image'] = (empty($img_attr[0]) || $img_attr[0] == 'null' || $img_attr[0] == null) ? $noimage : $img_attr[0];
        } else {            
            $data['front_banner_image'] = "";
            
        }
        if (!empty($post_data['logo'])) {
            $noimage = $upload_dir['baseurl'] . "/noimage/noimage_468_228.png";
            $img_attr = wp_get_attachment_image_src($post_data['logo'], 'full');
            $data['logo'] = (empty($img_attr[0]) || $img_attr[0] == 'null' || $img_attr[0] == null) ? $noimage : $img_attr[0];
        } else {
            $data['logo'] = "";
        }
        if (!empty($post_data['external_link'])) {
            if (strpos($post_data['external_link'], 'http://') !== false || strpos($post_data['external_link'], 'https://') !== false) {
                $data['external_link'] = $post_data['external_link'];
            } else {
                $data['external_link'] = 'http://' . $post_data['external_link'];
            }
        } else {
            $data['external_link'] = "";
        }

        if (!empty($post_data['map'])) {
            $noimage = $upload_dir['baseurl'] . "/noimage/noimage_656_969.png";
            $img_attr = wp_get_attachment_image_src($post_data['map'], 'full');
            $data['map'] = (empty($img_attr[0]) || $img_attr[0] == 'null' || $img_attr[0] == null) ? $noimage : $img_attr[0];
        } else {
            $data['map'] = "";
        }
        if (!empty($post_data['menu'])) {
            $noimage = $upload_dir['baseurl'] . "/noimage/noimage_656_969.png";
            $img_attr = wp_get_attachment_image_src($post_data['menu'], 'full');
            $data['menu'] = (empty($img_attr[0]) || $img_attr[0] == 'null' || $img_attr[0] == null) ? $noimage : $img_attr[0];
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
            $data['price'] = number_format_i18n($post_data['price'], 2 );
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
        if (!empty($post_data['tag_name'])) {
            $data['tag_name'] = $post_data['tag_name'];
        } else {
            $data['tag_name'] = "";
        }
        return $data;
    }

    /*
     * This function used to format the frames depends on the @param $id int.
     */

    public function format_generic_data($id) {
        $fields_array = get_fields($id);
        if ($fields_array) {
            foreach ($fields_array as $field_name => $value) {
                $fields_array[$field_name] = (empty($value['url']) || $value['url'] == 'null' || $value['url'] == null) ? "" : $value['url'];
            }
        }
        return $fields_array;
    }

    /*
     * This function used to return the page hierarchy of dashboard service.
     * 
     * @Param $parent_page int Provide parent id of page.
     * @return dashboard hierarchy
     */

    public function get_screen_content($parent_page) {
        $pages = $this->get_pages_list($parent_page);
        $data = array();
        foreach ($pages as $page) {
            $page['child'] = $this->get_pages_list($page['post_id']);
            $data[] = $page;
        }
        return $data;
    }

    /*
     * This function which call from get_screen_content. It return the single level hierarchy of page. Based on this multi level will be generate.
     */

    public function get_pages_list($parent_page) {
        $args = array(
            'sort_order' => 'ASC',
            'sort_column' => 'menu_order',
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'meta_key' => '',
            'meta_value' => '',
            'authors' => '',
            'child_of' => 0,
            'parent' => $parent_page,
            'exclude_tree' => '',
            'number' => '',
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        $data = array();
        $i = 0;
        foreach ($pages as $page) {
            $data[$i]['post_id'] = $page->ID;
            $data[$i]['title'] = $page->post_title;
            $data[$i]['tag_name'] = $page->post_name;
            $fields = get_fields($page->ID);
            foreach ($fields as $field => $value) {
                if ($field != 'screen_id' && $field != 'ios_tag_name') {
                    if (!empty($field)) {
                        $data[$i][$field] = (empty($value['url']) || $value['url'] == 'null' || $value['url'] == null) ? "" : $value['url'];
                    }
                } else {
                    $data[$i][$field] = $value;
                }
            }
            if (!array_key_exists('carousel_image', $data[$i])) {
                $data[$i]['carousel_image'] = "";
            }
            if (!array_key_exists('slider_image', $data[$i])) {
                $data[$i]['slider_image'] = "";
            }
            if (!array_key_exists('screen_image', $data[$i])) {
                $data[$i]['screen_image'] = "";
            }
            $i++;
        }
        return $data;
    }

    /*
     * Using page slug fetch the page id.
     * @Param page_slug string i.e post slug
     * @return page id
     */

    public function get_ID_by_page_slug($page_slug) {
        $page = get_page_by_path($page_slug);
        if ($page) {
            return $page->ID;
        } else {
            return -1;
        }
    }

    /*
     * This function checks all the service posts based on the timestamp. If the post timestamp is greater than this timestamp. 
     * It returns the screen id of all post types.
     * This function used to send the status of post update. Based on this, auto sync will work in mobile.
     */

    public function check_meta($timestamp) {
        global $wpdb;

        if (empty($timestamp)) {
            $this->set_status(404);
            return array('message' => __('Required Timestamp.'));
        }
        $date_time = date('Y-m-d H:i:s', $timestamp);

        $screens = $this->get_screens();
        $screens['100'] = 'page';
        $sql = "SELECT post_modified_gmt, post_type FROM " . $wpdb->posts . "  
        WHERE (post_type in ('" . implode("','", $screens) . "') or post_type in ('banner-image'))   
        and post_modified_gmt > %s     
        and (post_status = 'inherit' or post_status = 'publish' or post_status = 'trash' or post_status = 'pending') 
        order by post_modified_gmt desc";
        $results = $wpdb->get_results($wpdb->prepare($sql, $date_time));

        $meta = array('timestamp' => (string) $timestamp, 'updates' => array());
        $i = 0;
        foreach ($results as $row) {
            //Weather api is directly retrieves the data from service. Not working inn offline mode.
            if ($row->post_type == 'weather-api') {
                continue;
            }
            if ($i == 0) {
                /* $meta['data']=(string)date('Y-m-d H:i');
                  $meta['data_time']=(string)strtotime(date('Y-m-d H:i'));
                  $meta['current_date'] = (string)$date_time;
                 */
                //$meta['date_time'] = $date_time;
                $meta['timestamp'] = (string) strtotime($row->post_modified_gmt);
            }

            $data = array_keys($screens, $row->post_type);
            if (!empty($data[0]) && !in_array((string) $data[0], $meta['updates']) && !in_array($data[0], $meta['updates'])) {
                $meta['updates'][] = (string) $data[0];
                if ($data[0] == "100" || $data[0] == 100) {
                    $meta['updates'][] = "1100";
                    $meta['updates'][] = "1200";
                    $meta['updates'][] = "1300";
                    $meta['updates'][] = "1400";
                    $meta['updates'][] = "1500";
                    $meta['updates'][] = "2100";
                    if (in_array(5200, $meta['updates']) || in_array("5200", $meta['updates'])) {
                        
                    } else {
                        $meta['updates'][] = "5200";
                    }
                    //$meta['updates'][] = "5200";
                }
            }
            if ($row->post_type == 'banner-image') {
                if (in_array(5200, $meta['updates']) || in_array("5200", $meta['updates'])) {
                    
                } else {
                    $meta['updates'][] = "5200";
                }
                if (in_array(5300, $meta['updates']) || in_array("5300", $meta['updates'])) {
                    
                } else {
                    $meta['updates'][] = "5300";
                }
            }
            $i++;
        }
        //In initial stage app installation. if there is no record, we need to send the service for that.
        $check_list_screens = array("2120","2200","3100", "3200", "3300", "3400", "3500", "3600", "3700","3800","5220","5300");
        foreach ($check_list_screens as $key => $value) {
            $sql = "SELECT ID FROM " . $wpdb->posts . " WHERE post_type = '" . $screens[$value] . "' "
                    . "AND post_status NOT LIKE '%draft%' "
                    . "AND post_status NOT LIKE '%trash%'";            
            $results = $wpdb->get_results($sql);                        
            if (count($results) == 0) {
                if(!in_array($value, $meta['updates'])) {
                    $meta['updates'][] = $value;
                }                
            }
        }
        
         //Track delete permanently
        
        $sql = "SELECT * FROM wp_track_deleted WHERE post_deleted_date > '".$date_time."' ";              
        $results = $wpdb->get_results($sql);            
        $i=0;
        foreach ($results as $row) {
            /*if($i==0) {
                $meta['timestamp'] = (string) strtotime($row->post_deleted_date);
            }*/
            if (!in_array($row->post_type_screen_id, $meta['updates'])) {
                $meta['updates'][] = $row->post_type_screen_id;
            }   
            $i++;
        }
        return $meta;
    }

    /*
     * This function return the page screens. Key as screen id and post type slug as Value
     */

    public function get_screens() {
        $args = array(
            'sort_order' => 'ASC',
            'sort_column' => 'menu_order',
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'meta_key' => '',
            'meta_value' => '',
            'authors' => '',
            'child_of' => 0,
            'parent' => -1,
            'exclude_tree' => '',
            'number' => '',
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        $data = array();
        foreach ($pages as $page) {
            $screen_id = get_field('screen_id', $page->ID);
            //$screen_id = get_post_meta($page->ID, 'screen_id', true);
            $data[$screen_id] = $page->post_name;
        }
        $data['200'] = 'weather-api';
        $data['5110'] = 'social-sharing';
        return $data;
    }

    /*
     * This function return the page screens. Key as screen id and post title as Value
     */

    public function get_screen_attrs() {
        $args = array(
            'sort_order' => 'ASC',
            'sort_column' => 'menu_order',
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'meta_key' => '',
            'meta_value' => '',
            'authors' => '',
            'child_of' => 0,
            'parent' => -1,
            'exclude_tree' => '',
            'number' => '',
            'offset' => 0,
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        $data = array();
        foreach ($pages as $page) {
            $screen_id = get_field('screen_id', $page->ID);
            $data[$screen_id] = $page->post_title;
        }
        $data['200'] = 'Weather API';
        $data['5110'] = 'Social Sharing';
        return $data;
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
     * The menu category which used to fetch the data for in room ordering.
     * Based on the menu category, it will display the menu in hierarchical order.
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
                    . " INNER JOIN $table AS p ON p.ID=pm.post_id AND p.post_status='publish'"
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
                $temp_post_data = $this->format_get_data($post_data, $post->post_id);
                $temp_post_data['description'] = strip_tags($temp_post_data['description']);
                $post_data_array[] = $temp_post_data;
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
            $menu_array[$menu_loop]['description'] = strip_tags($post_meta_data['content']);
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
     * Return todays events data to list posts function.
     */
    public function get_list_events($type) {
        global $wpdb;
        $table = $wpdb->posts;
        $sql = "SELECT e.post_id, p.post_title FROM wp_events as e "
                . " INNER JOIN $table AS p ON p.ID=e.post_id AND p.post_status='publish'"
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
     * Return Activity guide data with mothwise hierarchical order.
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
                . " WHERE p.post_type LIKE %s AND p.post_status='publish' ORDER BY m.meta_value ASC";

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

    /*
     * Return the month and year record.
     */

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

    /*
     * For internal activity screens, display the activity in week wise order.
     */

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
                    . " INNER JOIN  $wpdb->posts AS p ON p.ID=m.post_id AND p.post_status='publish'"
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

    /*
     * For activity guide and todays events, return the banner image and description. 
     */

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

    /*
     * This function call the weather details, format and return to the list_posts.
     */

    public function get_weather($type) {
        global $wpdb;
        $table = _get_meta_table('post');
        $sql = "SELECT p.ID,p.post_title FROM " . $wpdb->posts . " as p "
                . " WHERE p.post_type LIKE %s";
        $results = $wpdb->get_results($wpdb->prepare($sql, $type));
        foreach ($results as $post) {
            $post_id = $post->ID;
        }
        $meta_data = get_metadata('post', $post_id);

        $data['post_id'] = $post_id;
        $data['weather_url'] = $meta_data['weather_url']['0'];
        $data['location'] = $meta_data['location']['0'];
        $data['weather_data'] = $this->get_weather_details($data);
        return $data;
    }

    /*
     * This function call from get_weather. Call the open weather api, format and returns the weather data.
     * Sahal Ansari was here 2015-07-06
     */

    public function get_weather_details($data) {
        $url = $data['weather_url'] . "?q=" . rawurlencode( $data['location']) . "&mode=json";
        $response = wp_remote_get($url, array (
		'timeout' => 7,
		'user-agent' => ' Mozilla/5.0 (Windows NT 6.1; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0'
	));
	/* debugging 
	print_r ($url);
	print_r ($response); */
        $response_code = wp_remote_retrieve_response_code($response);
        $response_data = array();
        $response_data['status'] = $response_code;
        if ($response_code == 200) {
            $response_data['message'] = "success";
            $data = json_decode($response['body']);
            $response_data['weather'] = $data->list;
            //$response_data['report'] = $data->list[0]->main->temp;
        } else {
            $response_data['message'] = "failure";
            $response_data['weather'] = array();
        }

        return $response_data;
    }

}
