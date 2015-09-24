<?php
require_once 'ErrorObject.php';
/**
 * Description of mysql
 *
 * @author matthewe
 */
class PdoMysql {
  /**
   *
   * @var PDO $pdoConn
   */
  private $pdoConn;
  private $host = 'localhost';
  private $dbName;
  private $user;
  private $pass;
  /**
   *
   * @var PDOStatement $stmt
   */
  private $stmt;
  /**
   *
   * @var int $lastInserted
   */
  private $lastInserted;
  /**
   *
   * @var array $resultSet
   */
  private $resultSet;

  /**
   *
   * @var int $numRows
   */
  private $numRows;

  public function conn() {
    $this->setConnStrings();
    try {
      $this->pdoConn = new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->user, $this->pass);
    } catch (Exception $e) {
      trigger_error($e->getMessage() ,E_USER_ERROR);
    }
    $this->pdoConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  /**
   * returns sets result set from query
   * @param string $sql
   * @param array $bindValues
   * @return \ErrorObject|boolean
   */
  public function query($sql, array $bindValues) {

    $this->stmt = $this->pdoConn->prepare($sql);
    try {
      $this->stmt->execute($bindValues);
    } catch (PDOException $e) {
      $errorObject = new ErrorObject($e->getCode()
                                     , $e->getMessage()
                                     , $e->getTrace()
                                     , $_SERVER['SCRIPT_FILENAME']);
      return $errorObject;
    }
    if ($this->stmt->columnCount() > 0) {
      $this->resultSet = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
      $this->lastInserted = $this->pdoConn->lastInsertId();
      $this->numRows = $this->stmt->rowCount();
    }
    unset($this->stmt);
    unset($this->pdoConn);
    return true;
  }

  public function getResultsSet() {
    if (isset($this->resultSet)) {
      return $this->resultSet;
    } else {
      return null;
    }
  }

  public function getLastInsertId() {
    if (isset($this->lastInserted)) {
      return $this->lastInserted;
    } else {
      return null;
    }
  }

  public function getNumRows() {
    if (isset($this->numRows)) {
      return $this->numRows;
    } else {
      return null;
    }
  }

  private function setConnStrings() {
    if ($_SERVER['SERVER_NAME'] == 'localhost') {
      $this->dbName = 'eggmatters_com';
      $this->user = 'eggmatters_user';
      $this->pass = '';
    } else {
      $this->dbName = 'eggmatters_com';
      $this->user = 'eggmatters_user';
      $this->pass = '';
    }
  }

}

?>
