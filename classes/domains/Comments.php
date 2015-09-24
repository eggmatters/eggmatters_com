<?php

/**
 * Description of Comments
 *
 * @author matthewe
 */
class Comments {
  /**
   *
   * @var int
   */
  public $id;

  /**
   *
   * @var int
   */
  public $userId;

  /**
   *
   * @var string
   */
  public $userName;

  /**
   *
   * @var string
   */
  public $body;

  /**
   *
   * @var int
   */
  public $postId;

  /**
   *
   * @var DateTime|String
   */
  public $datePosted;

  /**
   *
   * @var array
   */
  public $replies;

  public $bindings;

  public function __construct($id = null
                              , $userId = null
                              , $userName = null
                              , $body = null
                              , $postId = null
                              , $datePosted = null
                              , $replies = null) {
    $this->id = $id;
    $this->userId = $userId;
    $this->userName = $userName;
    $this->body = $body;
    $this->postId = $postId;
    $this->datePosted = $datePosted;
    $this->replies = $replies;
    $this->displayBody = nl2br($this->body);
    $this->bindings = array('id' => $this->id
                            , 'user_id' => $this->userId
                            , 'body' => $this->body
                            , 'post_id' => $this->postId
                            , 'date_posted' => $this->datePosted);
  }

  public static function getCommentById($commentId) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT comment.id
                   , comments.user_id
                   , comments.body
                   , comments.post_id
                   , comments.date_posted
            FROM comments
            WHERE comments.id=:commentId";
    $bindValues = array('commentId' => (int) $commentId);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function setNewComment(Comments $comment) {
    $comment->bindings['body'] = htmlspecialchars($comment->body, ENT_QUOTES);
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "INSERT INTO comments ( user_id
                                   , body
                                   , post_id
                                   , date_posted )
                          VALUES ( :user_id
                                   , :body
                                   , :post_id
                                   , NOW());";
    $bindValues = $comment->bindings;
    unset($bindValues['id']);
    unset($bindValues['date_posted']);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    self::alertComment($comment);
    return $pdoMysql->getLastInsertId();
  }

  public static function setNewReply(Comments $comment, $parentId) {
    $commentId = self::setNewComment($comment);
    if (is_a($commentId, 'ErrorObject')) {
      $commentId->caller = $_SERVER['SCRIPT_FILENAME'];
      return $commentId;
    }
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "INSERT INTO replies ( comment_id, parent_id)
                         VALUES ( :commentId, :parentId)";
    $bindValues = array('commentId' => $commentId, 'parentId' => $parentId);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    self::alertComment($comment);
    return $pdoMysql->getLastInsertId();
  }

  public static function getCommentsByPostId($postId) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT comments.id
                   , comments.user_id
                   , comments.body
                   , comments.post_id
                   , comments.date_posted
                   , users.user_name
            FROM comments, users
            WHERE comments.approved = 1
            AND comments.post_id=:postId
            AND users.id = comments.user_id
            AND comments.id NOT IN (SELECT comment_id FROM replies)";
    $bindValues = array('postId' => (int) $postId);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function getCommentsForApproval() {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT comments.id
                   , comments.user_id
                   , comments.body
                   , comments.post_id
                   , users.user_name
            FROM comments, users
            WHERE comments.approved = 0
            AND users.id = comments.user_id";
    $bindValues = array();
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function getAllCommentReplies(array &$commentReplies, $depth = null) {
    foreach($commentReplies as $comment) {
      $subReplies = ($comment->id) ? self::getRepliesByParentId($comment->id) : null;
      if (!is_null($subReplies)) {
        $comment->replies = $subReplies;
        if (!is_null($depth)) {
          if ($depth == 0) {
            continue;
          } else {
            $depth--;
          }
        }
        self::getAllCommentReplies($comment->replies, $depth);
      }
    }
  }



  public static function getRepliesByParentId($parentId){
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT comments.id
                   , comments.user_id
                   , comments.body
                   , comments.post_id
                   , comments.date_posted
                   , users.user_name
            FROM comments, replies, users
            WHERE comments.approved != 0
            AND comments.id = replies.comment_id
            AND replies.parent_id=:parentId
            AND users.id = comments.user_id";
    $bindValues = array('parentId' => (int)$parentId);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function approveComment($commentId) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "UPDATE comments SET approved = 1 WHERE id=$commentId";
    $bindValues = array();
    $rs = $pdoMysql->query($sql, $bindValues);
    if (is_a($rs, 'ErrorObject')) {
      $rs->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rs;
    }
    return $rs;
  }

  private static function parseResults(array $rs) {
    $returnArray = array();
    if (count($rs) > 0) {
      foreach ($rs as $result) {
        $post = new Comments($result['id']
                       , isset($result['user_id']) ? $result['user_id'] : null
                       , isset($result['user_name']) ? $result['user_name'] : null
                       , isset($result['body']) ? $result['body'] : null
                       , isset($result['post_id']) ? $result['post_id'] : null
                       , isset($result['date_posted']) ? $result['date_posted'] : null);
        $returnArray[] = $post;
      }
      return $returnArray;
    }
    return null;
  }

  private static function alertComment(Comments $comment) {
    require_once '../classes/utilities/class.phpmailer.php';
    $mail = new phpmailer();
    $mail->IsSendmail();
    $mail->FromName = "Comments";
    $mail->From = 'comment_notifier@eggmatters.com';
    $mail->Subject = "Comment by $comment->userName awaiting approval.";
    $mail->Host = $_SERVER['SERVER_NAME'];
    $mail->Body = "Comment Body:\n".$comment->body;
    $mail->AddAddress("comment_notifier@eggmatters.com", "Comment Notifier");
    $mail->Send();
  }

}

?>
