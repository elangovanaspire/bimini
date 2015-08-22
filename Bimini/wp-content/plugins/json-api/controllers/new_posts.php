<?php
/*
Controller name: My JSON Posts
Controller description: Data manipulation methods for posts
*/

class JSON_API_New_Posts_Controller {

    public function make_up_room() {
     global $json_api;
    if (!current_user_can('edit_posts')) {
      $json_api->error("You need to login with a user that has 'edit_posts' capacity.");
    }
    
    //$nonce_id = $json_api->get_nonce_id('posts', 'create_post');
   
    nocache_headers();
    $post = new JSON_API_Post();
    //print_r($_REQUEST);
    //exit();
    $id = $post->create($_REQUEST);
    if (empty($id)) {
      $json_api->error("Could not create post.");
    }
    return array(
      'post' => $post
    );
    }
  
    public function get_make_up_room(){
    global $json_api;
    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array(
      'ignore_sticky_posts' => true
    );
    $query = wp_parse_args($url['query']);
    unset($query['json']);
    unset($query['post_status']);
    $query = array_merge($defaults, $query);
    $posts = $json_api->introspector->get_posts($query);
    $result = $this->posts_result($posts);
    $result['query'] = $query;
    return $result;
  }
  
  protected function posts_result($posts) {
    global $wp_query;
    return array(
      'count' => count($posts),
      'count_total' => (int) $wp_query->found_posts,
      'pages' => $wp_query->max_num_pages,
      'posts' => $posts
    );
  }
    
}

?>
