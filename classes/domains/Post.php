<?php
require_once '../classes/utilities/PdoMysql.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Post
 *
 * @author matthewe
 */
class Post {
  //put your code here

  /**
   *
   * @var int $id
   */
  public $id;

  /**
   *
   * @var string $headline
   */
  public $headline;

  /**
   *
   * @var string $body
   */
  public $body;

  /**
   *
   * @var boolean $online
   */
  public $online;

  /**
   *
   * @var int $categoryId
   */
  public $categoryId;

  /**
   *
   * @var DateTime|string $postDate
   */
  public $postDate;

  public $categoryName;

  private $bindings;

  public function __construct($id = 0
                              , $headline = null
                              , $body = null
                              , $online = null
                              , $postDate = null
                              , $categoryId = null
                              , $categoryName = null) {
    $this->id = $id;
    $this->headline = $headline;
    $this->body = $body;
    $this->online = $online;
    $this->categoryId = $categoryId;
    $this->categoryName = $categoryName;
    $date = new DateTime();
    if (!is_null($postDate)) {
      $date = new DateTime($postDate);
    }
    $this->postDate = $date->format(DateTime::ISO8601);
    $this->bindings = array('id' => $id
                           , 'headline' => is_null($headline) ? null :  $headline
                           , 'body' => is_null($body) ? null : $body
                           , 'online' => is_null($online) ? null : $online
                           , 'category_id' => is_null($categoryId) ? null : $categoryId
                           , 'post_date' => is_null($postDate) ? new BindingsEscape('func', 'NOW()') : $postDate);
  }

  /**
   * setNewPost
   * @param string $headline
   * @param string $body
   * @param int $categoryId
   * @param int $online
   * @return int
   */
  public static function setNewPost(Post $post) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "INSERT INTO post_data (`headline`
                                   , `body`
                                   , `category_id`
                                   , `online`
                                   , `post_date`)
                           VAlUES (:headline
                                   , :body
                                   , :category_id
                                   , :online
                                   , {$post->bindings['post_date']->value})";
    unset($post->bindings['post_date']);
    unset($post->bindings['id']);
    $rv = $pdoMysql->query($sql, $post->bindings);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return $pdoMysql->getLastInsertId();
  }

  /**
   * getPostById
   * @param int $postId
   * @return Post|ErrorObject
   */
  public static function getPostById($postId) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT post.id
                   , post.headline
                   , post.body
                   , post.category_id
                   , post.online
                   , post.post_date
                   , cat.id as category_id
                   , cat.category
            FROM post_data post, categories cat
            WHERE post.id=:postId
            AND cat.id = post.category_id";
    $bindValues = array('postId' => (int) $postId);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function getHeroPost() {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT post.id
                   , post.headline
                   , post.body
                   , post.category_id
                   , post.online
                   , post.post_date
                   , cat.id as category_id
                   , cat.category
            FROM post_data post, categories cat
            WHERE cat.id = post.category_id
            ORDER BY post.post_date DESC LIMIT 1";
    $rv = $pdoMysql->query($sql, array());
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function getPostsByCategoryId($categoryId) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT id
                   , headline
                   , body
                   , category_id
                   , online
                   , post_date
            FROM post_data
            WHERE category_id=:categoryId";
    $bindValues = array('categoryId' => $categoryId);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function getAllPostHeaders() {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT id
                   , headline
                   , category_id
                   , online
                   , post_date
            FROM post_data";
    $rv = $pdoMysql->query($sql, array());
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function getAllPostHeadersByCategoryId($categoryId) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT id
                   , headline
                   , category_id
                   , online
                   , post_date
            FROM post_data
            WHERE category_id = $categoryId";
    $rv = $pdoMysql->query($sql, array());
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function updatePost(Post $post) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();

    $sql = "UPDATE post_data SET ";
    $bindValues = array();
    foreach($post->bindings as $field => $value) {
      if (!is_null($value) && $field != 'post_id'&& $field != 'id') {
        if (is_a($value, 'BindingsEscape')) {
          $sql .= "$field=$value->value, ";
        } else {
          $sql .= "$field=:$field, ";
          $bindValues[$field] = $value;
        }
      }
    }
    $sql = substr($sql, 0, -2);
    $sql .= " WHERE id = $post->id";

    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return $pdoMysql->getNumRows();
  }

  private static function parseResults(array $rs) {
    $returnArray = array();
    if (count($rs) > 0) {
      foreach ($rs as $result) {
        $post = new Post($result['id']
                       , isset($result['headline']) ? $result['headline'] : null
                       , isset($result['body']) ? $result['body'] : null
                       , isset($result['online']) ? $result['online'] : null
                       , isset($result['post_date']) ? $result['post_date'] : null
                       , isset($result['category_id']) ? $result['category_id'] : null
                       , isset($result['category']) ? $result['category'] : null);
        $returnArray[] = $post;
      }
      return $returnArray;
    }
    return null;
  }

}

class BindingsEscape {
  public $type;
  public $value;

  public function __construct($type, $value) {
    $this->type = $type;
    $this->value = $value;
}
}

?>
