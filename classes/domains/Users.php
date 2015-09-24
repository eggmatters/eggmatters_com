<?php
require_once '../classes/utilities/PdoMysql.php';

/**
 * Description of Users
 *
 * @author matthewe
 */
class Users {
  /**
   *
   * @var int userId
   */
  public $userId;

  /**
   *
   * @var string userName
   */
  public $userName;

  /**
   *
   * @var int userType
   */
  public $userType;

  /**
   *
   * @var DateTime|String
   */
  public $dateJoined;

  /**
   *
   * @var string email
   */
  public $email;

  /**
   *
   * @var array()
   */
  public $bindings;

  /**
   *
   * @var string
   */
  private $userPw;

  const EGGMATTERS = 1;

  const GUEST_POST = 2;

  const GUEST = 3;

  const ANONYMOUS = 4;

  /**
   *
   * @param int $id
   * @param string $userName
   * @param int $userType
   * @param DateTime $dateJoined
   * @param string $userPw
   */
  public function __construct($id = null
                              , $userName = null
                              , $userType = null
                              , $dateJoined = null
                              , $email = null
                              , $userPw = null) {
    $this->userId = $id;
    $this->userName = $userName;
    $this->userType = $userType;
    $this->dateJoined = $dateJoined;
    $this->userPw = $userPw;
    $this->email = $email;
    $this->bindings = array('id' => $this->userId
                            , 'user_name' => $this->userName
                            , 'user_type' => $this->userType
                            , 'date_joined' => $this->dateJoined
                            , 'email' => $this->email
                            , 'user_pw' => $this->userPw);
  }

  /**
   *
   * @param User $user
   * @return array
   */
  public static function getUser($userName, $email) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT id
                   , user_name
                   , user_type
                   , email
                   , user_pw
                   , date_joined
            FROM users
            WHERE user_name=:user_name
              AND email=:email";
    $bindValues = array('user_name' => $userName, 'email' => $email);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function getUserById($id) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT id
                   , user_name
                   , user_type
                   , email
                   , date_joined
                   , user_pw
            FROM users
            WHERE id=:id";
    $bindValues = array('id' => $id);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function getUserByCookie($cookieHash) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT id
                   , user_name
                   , email
                   , user_type
                   , date_joined
            FROM users
            WHERE user_pw=:userPw";
    $bindValues = array('userPw'=> $cookieHash);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function setNewUser(Users $newUser) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "INSERT INTO users (`user_name`
                               , `user_type`
                               , `email`
                               , `user_pw`
                               , `date_joined`)
                        VALUES (:user_name
                               , :user_type
                               , :email
                               , :user_pw
                               , :date_joined)";
    unset($newUser->bindings['id']);
    $rv = $pdoMysql->query($sql, $newUser->bindings);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return $pdoMysql->getLastInsertId();
  }
  public static function getAllFakeUsers() {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT id
                   , user_name
                   , user_type
                   , email
                   , user_pw
                   , date_joined
            FROM users
            WHERE user_type = 4";
    $rv = $pdoMysql->query($sql, array());
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    $rs = $pdoMysql->getResultsSet();
    return self::parseResults($rs);
  }

  public static function getFakeUser($postId = null) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    if (!is_null($postId)) {
      $sql = "SELECT id
                     , user_name
                     , user_type
                     , email
                     , user_pw
                     , date_joined
              FROM users
              WHERE user_type = 4
              AND id NOT IN (
                SELECT user_id FROM comments
                WHERE post_id = $postId)";
    } else {
      $sql = "SELECT id
                     , user_name
                     , user_type
                     , email
                     , user_pw
                     , date_joined
              FROM users
              WHERE user_type = 4";
    }
    $rv = $pdoMysql->query($sql, array());
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    $rs = $pdoMysql->getResultsSet();
    if (count($rs) <= 0) {
      return self::getAllFakeUsers();
    }
    return self::parseResults($rs);
  }

  private static function parseResults(array $rs) {
    $returnArray = array();
    if (count($rs) > 0) {
      foreach ($rs as $result) {
        $user = new Users(isset($result['id']) ? $result['id'] : null
                          , isset($result['user_name']) ? $result['user_name'] : null
                          , isset($result['user_type']) ? $result['user_type'] : null
                          , isset($result['date_joined']) ? $result['date_joined'] : null
                          , isset($result['email']) ? $result['email'] : null
                          , isset($result['user_pw']) ? $result['user_pw'] : null);
        $returnArray[] = $user;
      }
      return $returnArray;
    }
    return null;
  }
}

?>
