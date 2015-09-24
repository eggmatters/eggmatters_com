<?php
require_once '../classes/domains/Comments.php';
require_once '../classes/domains/Challenge.php';
require_once '../classes/domains/Users.php';
require_once '../classes/utilities/ErrorObject.php';
/**
 * Description of CommentsController
 *
 * @author matthewe
 */
class CommentsController {
  //put your code here
  public function __construct() {
  }
  public static function getAllPostComments(array $post) {
    $postId = $post['post_id'];
    $comments = Comments::getCommentsByPostId($postId);
    if (is_null($comments)) {
      return array();
    } else {
      $comments = array();
      Comments::getAllCommentReplies($comments);
      return $comments;
    }
  }
  public static function getCommentsForApproval(array $post) {
    $comments = Comments::getCommentsForApproval();
    if (is_null($comments)) {
      return array();
    } else {
      return $comments;
    }
  }
  public static function setNewComment(array $post) {
    $authStatus = Challenge::checkAuthenticationToken($post['token']);
    if (is_a($authStatus, 'ErrorObject')){
      return $authStatus;
    }
    $userResults = Users::getUserByCookie($post['userHash']);
    $userAuth    = $userResults[0];
    if (is_a($userAuth, 'ErrorObject')) {
      return $userAuth;
    }
    $comment = new Comments(null, $userAuth->userId, $userAuth->userName, $post['commentData'], $post['postId'], 'NOW()');
    $retval = Comments::setNewComment($comment);
    return $retval;
  }
  public static function setCommentReply(array $post) {
    $authStatus = Challenge::checkAuthenticationToken($post['token']);
    if (is_a($authStatus, 'ErrorObject')){
      return $authStatus;
    }
    $userResults = Users::getUserByCookie($post['userHash']);
    $userAuth    = $userResults[0];
    if (is_a($userAuth, 'ErrorObject')) {
      return $userAuth;
    }
    $comment = new Comments(null, $userAuth->userId, $userAuth->userName, $post['commentData'], $post['postId'], 'NOW()');
    $retval = Comments::setNewReply($comment, $post['commentId']);
    return $retval;
  }
  public static function approveComment(array $post) {
    $authStatus = Challenge::checkAuthenticationToken($post['token']);
    if (is_a($authStatus, 'ErrorObject')){
      return $authStatus;
    }
    return Comments::approveComment($post['commentId']);
  }
}

?>
