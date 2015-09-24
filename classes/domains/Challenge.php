<?php
require_once '../classes/utilities/PdoMysql.php';
/**
 * Description of Challenge
 *
 * @author matthewe
 */
class Challenge {
  /**
   *
   * @var int
   */
  public $challengeId;
  /**
   *
   * @var string
   */
  public $question;

  /**
   *
   * @var string
   */
  public $answer;

  const AUTH_FAILURE = 400;
  /**
   *
   * @param int $challengeId
   * @param string $question
   * @param string $answer
   */
  public function __construct($challengeId = null, $question = null, $answer = null) {
    $this->challengeId = $challengeId;
    $this->question = $question;
    $this->answer = $answer;
    $this->bindings = array('id' => $this->challengeId, 'question' => $this->question, 'answer' => $this->answer);
  }

  /**
   *
   * @return string
   */
  public static function issueChallenge() {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT count(*) FROM challenges";
    $rv = $pdoMysql->query($sql, array());
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    $rs = $pdoMysql->getResultsSet();
    $numChallenges = $rs[0]['count(*)'];
    $challengeToIssue = rand(1, $numChallenges);
    $pdoMysql->conn();
    $sql = "SELECT * FROM challenges where id = $challengeToIssue";
    $rv = $pdoMysql->query($sql, array());
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  /**
   *
   * @return boolean
   */
  public static function getAuthenticationToken($bypass = false) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    if (isset($_COOKIE['eggstok']) && !$bypass) {
      return $_COOKIE['eggstok'];
    }
    $ch = self::issueChallenge();
    $tkn = md5($ch[0]->question.$ch[0]->answer.microtime());
    $sql = "INSERT INTO session_tokens (token, date_created)
            VALUES ('$tkn', NOW())";
    $rv = $pdoMysql->query($sql, array());
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return setcookie('eggstok', $tkn);
  }

  /**
   *
   * @return int
   */
  public static function checkAuthenticationToken($token) {
    $tokenFailure = new ErrorObject(self::AUTH_FAILURE, 'Auth Token Failure');
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $tkn = isset($_COOKIE['eggstok']) ? $_COOKIE['eggstok'] : false;
    if (!$tkn || $tkn != $token) {
      return $tokenFailure;
    }
    $sql = "SELECT * FROM session_tokens WHERE token = '$token'";
    $rv = $pdoMysql->query($sql, array());
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $tokenFailure;
    }
    if (count($pdoMysql->getResultsSet()) <= 0) {
      return $tokenFailure;
    }
    self::deleteToken($token);
    self::getAuthenticationToken(true);
    return 200;
  }

  private static function deleteToken($token) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "DELETE FROM session_tokens WHERE token='$token'";
    $rv = $pdoMysql->query($sql, array());
  }

  /**
   *
   * @param array $rs
   * @return \Challenge|null
   */
  private static function parseResults(array $rs) {
    $returnArray = array();
    if (count($rs) > 0) {
      foreach ($rs as $result) {
        $challenge = new Challenge(isset($result['id']) ? $result['id'] : null
                          , isset($result['question']) ? $result['question'] : null
                          , isset($result['answer']) ? $result['answer'] : null);
        $returnArray[] = $challenge;
      }
      return $returnArray;
    }
    return null;
  }
}

?>
