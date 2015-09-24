<?php
require_once '../classes/domains/Post.php';
require_once '../classes/utilities/ErrorObject.php';

/**
 * Description of PostController
 *
 * @author matthewe
 */
class PostController {

  public function __construct() {
  }

  public static function setNewPost(array $post) {
    $headline = $post['headline'];
    $body = $post['body'];
    $categoryId = $post['categoryId'];

    $postId = Post::setNewPost(new Post(0, $headline, $body, 0, null, $categoryId));

    if (is_a($postId, 'ErrorObject')) {
      return $postId;
    }
    $postData = Post::getPostById($postId);
    return $postData;
  }

  public static function updatePost(array $post) {
    $postId = $post['postId'];
    $headline = $post['headline'];
    $body = $post['body'];
    $updated = Post::updatePost(new Post($postId, $headline, $body, null, null, null));
    if (is_a($updated, 'ErrorObject')) {
      return $updated;
    }
    $postData = Post::getPostById($postId);
    return $postData;
  }

  public static function getPostsByCategory(array $post) {
    $categoryId = $post['categoryId'];
    $postData = Post::getPostsByCategoryId($categoryId);
    return $postData;
  }

  public static function getPostHeadersByCategory(array $post) {
    $categoryId = $post['categoryId'];
    $postData = Post::getAllPostHeadersByCategoryId($categoryId);
    return $postData;
  }

}

?>
