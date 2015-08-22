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
            '/cposts' => array(
                array(array($this, 'get_posts'), WP_JSON_Server::READABLE),
                array(array($this, 'new_post'), WP_JSON_Server::CREATABLE | WP_JSON_Server::ACCEPT_JSON),
            ),
            '/cposts/(?P<id>\d+)' => array(
                array(array($this, 'get_post'), WP_JSON_Server::READABLE),
                array(array($this, 'edit_post'), WP_JSON_Server::EDITABLE | WP_JSON_Server::ACCEPT_JSON),
                array(array($this, 'delete_post'), WP_JSON_Server::DELETABLE),
            ),
            '/cposts/(?P<id>\d+)/revisions' => array(
                array($this, 'get_revisions'), WP_JSON_Server::READABLE
            ),
            // Meta
            '/cposts/(?P<id>\d+)/meta' => array(
                array(array($this, 'get_all_meta'), WP_JSON_Server::READABLE),
                array(array($this, 'add_meta'), WP_JSON_Server::CREATABLE | WP_JSON_Server::ACCEPT_JSON),
            ),
            '/cposts/(?P<id>\d+)/meta/(?P<mid>\d+)' => array(
                array(array($this, 'get_meta'), WP_JSON_Server::READABLE),
                array(array($this, 'update_meta'), WP_JSON_Server::EDITABLE | WP_JSON_Server::ACCEPT_JSON),
                array(array($this, 'delete_meta'), WP_JSON_Server::DELETABLE),
            ),
            // Comments
            '/cposts/(?P<id>\d+)/comments' => array(
                array(array($this, 'get_comments'), WP_JSON_Server::READABLE),
            ),
            '/cposts/(?P<id>\d+)/comments/(?P<comment>\d+)' => array(
                array(array($this, 'get_comment'), WP_JSON_Server::READABLE),
                array(array($this, 'delete_comment'), WP_JSON_Server::DELETABLE),
            ),
            // Meta-post endpoints
            '/cposts/types' => array(
                array($this, 'get_post_types'), WP_JSON_Server::READABLE
            ),
            '/cposts/types/(?P<type>\w+)' => array(
                array($this, 'get_post_type'), WP_JSON_Server::READABLE
            ),
            '/cposts/statuses' => array(
                array($this, 'get_post_statuses'), WP_JSON_Server::READABLE
            ),
        );
        return array_merge($routes, $post_routes);
    }

    public function new_post($data) {
        $post = array();
        $post = $this->format($data);
        $post_id = $this->insert_post($post);
        $result_meta = $this->handle_post_meta_action($post_id, $post['post_meta']);
        $result_mail = $this->send_mail($data);
        return $post_id;
    }

    protected function insert_post($data) {
        $post_ID = wp_insert_post($data);
        return $post_ID;
    }

    protected function handle_post_meta_action($post_id, $data) {
        // $post_meta['meta_data']=$data['meta_data'];        
        //print "yes i'm here"; //exit();
        foreach ($data as $meta_array) {
            //print_r($meta_array); exit();
            if (empty($meta_array['ID'])) {
                // Creation
                $result = $this->add_meta($post_id, $meta_array);
            }
        }
        return true;
    }

    protected function format($data) {
        $post['post_type'] = $data['type'];
        $post['post_title'] = $data['message'] . $data['room_number'];
        $post['post_status'] = $data['status'];
        $post['post_meta'] = array();
        $i = 1;

        foreach ($data as $key => $value) {
            //print $i;
            $post['post_meta'][$i]['key'] = $key;
            $post['post_meta'][$i]['value'] = $value;
            $i++;
        }

        // print_r($post['post_meta']);
        //exit();
        //$post['post_meta'] = array($data);
        //print_r($post['post_meta']);
        //exit();
        return $post;
    }

    public function add_meta($id, $data) {
        $id = (int) $id;

        if (empty($id)) {
            return new WP_Error('json_post_invalid_id', __('Invalid post ID.'), array('status' => 404));
        }

        $post = get_post($id, ARRAY_A);

        if (empty($post['ID'])) {
            return new WP_Error('json_post_invalid_id', __('Invalid post ID.'), array('status' => 404));
        }

        /* if ( ! $this->check_edit_permission( $post ) ) {
          return new WP_Error( 'json_cannot_edit', __( 'Sorry, you cannot edit this post' ), array( 'status' => 403 ) );
          } */

        if (!array_key_exists('key', $data)) {
            return new WP_Error('json_post_missing_key', __('Missing meta key.'), array('status' => 400));
        }
        if (!array_key_exists('value', $data)) {
            return new WP_Error('json_post_missing_value', __('Missing meta value.'), array('status' => 400));
        }

        if (empty($data['key'])) {
            return new WP_Error('json_meta_invalid_key', __('Invalid meta key.'), array('status' => 400));
        }

        if (!$this->is_valid_meta_data($data['value'])) {
            // for now let's not allow updating of arrays, objects or serialized values.
            return new WP_Error('json_post_invalid_action', __('Invalid provided meta data for action.'), array('status' => 400));
        }

        if (is_protected_meta($data['key'])) {
            return new WP_Error('json_meta_protected', sprintf(__('%s is marked as a protected field.'), $data['key']), array('status' => 403));
        }

        $meta_key = wp_slash($data['key']);
        $value = wp_slash($data['value']);

        $result = add_post_meta($id, $meta_key, $value);

        if (!$result) {
            return new WP_Error('json_meta_could_not_add', __('Could not add post meta.'), array('status' => 400));
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
        //print_r($data); exit();
        $the_slug='request-make-up-room';
        $args=array(            
  'post_name' => 'request-make-up-room' ,
   'post_type' => 'email_template',
	'post_status' => 'publish'
	       
);
        //print_r($args);
        $temp=query_posts($args);
        //print_r($temp); exit();
        $email_temp = get_metadata('post', $temp);
        print_r($email_temp['to_emailaddress']['0']);       
        
        //exit();
        $to2 = $email_temp['to_emailaddress']['0'];
        $subject2=$email_temp['subject']['0'];
        $message2_header=$email_temp['message_header']['0'];
        $message2_footer=$email_temp['message_footer']['0'];
        $message2=$message2_header . "  " . $message2_footer;
        $result2 = wp_mail($to2, $subject2, $message2);
        
        $to1 = $data['email'];
        $subject1 = $data['message'] . "-" . $data['room_no'];
        $message1 = "testing";
        $result1 = wp_mail($to1, $subject1, $message1);
        return $result;
    }

    public function get_all_meta( $id ) {
		$id = (int) $id;

		if ( empty( $id ) ) {
			return new WP_Error( 'json_post_invalid_id', __( 'Invalid post ID.' ), array( 'status' => 404 ) );
		}

		$post = get_post( $id, ARRAY_A );

		if ( empty( $post['ID'] ) ) {
			return new WP_Error( 'json_post_invalid_id', __( 'Invalid post ID.' ), array( 'status' => 404 ) );
		}

		/*if ( ! $this->check_edit_permission( $post ) ) {
			return new WP_Error( 'json_cannot_edit', __( 'Sorry, you cannot edit this post' ), array( 'status' => 403 ) );
		}*/

		global $wpdb;
		$table = _get_meta_table( 'post' );
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT meta_id, meta_key, meta_value FROM $table WHERE post_id = %d", $id ) );

		$meta = array();

		foreach ( $results as $row ) {
			$value = $this->prepare_meta( $id, $row, true );

			if ( is_wp_error( $value ) ) {
				continue;
			}

			$meta[] = $value;
		}

		return apply_filters( 'json_prepare_meta', $meta, $id );
	}
    
        public function get_meta( $id, $mid ) {
		$id = (int) $id;

		if ( empty( $id ) ) {
			return new WP_Error( 'json_post_invalid_id', __( 'Invalid post ID.' ), array( 'status' => 404 ) );
		}

		$post = get_post( $id, ARRAY_A );

		if ( empty( $post['ID'] ) ) {
			return new WP_Error( 'json_post_invalid_id', __( 'Invalid post ID.' ), array( 'status' => 404 ) );
		}

		/*if ( ! $this->check_edit_permission( $post ) ) {
			return new WP_Error( 'json_cannot_edit', __( 'Sorry, you cannot edit this post' ), array( 'status' => 403 ) );
		}*/

		$meta = get_metadata_by_mid( 'post', $mid );

		if ( empty( $meta ) ) {
			return new WP_Error( 'json_meta_invalid_id', __( 'Invalid meta ID.' ), array( 'status' => 404 ) );
		}

		if ( absint( $meta->post_id ) !== $id ) {
			return new WP_Error( 'json_meta_post_mismatch', __( 'Meta does not belong to this post' ), array( 'status' => 400 ) );
		}

		return $this->prepare_meta( $id, $meta );
	}
}
