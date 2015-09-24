<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ErrorObject
 *
 * @author matthewe
 */
class ErrorObject {
  /**
   *
   * @var int $errNum
   */
  public $errNum;
  /**
   *
   * @var string $errMsg
   */
  public $errMsg;
  /**
   *
   * @var array $stackTrace.
   */
  public $stackTrace;

  /**
   *
   * @var string caller
   */
  public $caller;
/**
 * static object returned
 * @param int $errNum
 * @param string $errMsg
 * @param array $stackTrace
 */
  public function __construct($errNum = null
                              , $errMsg = null
                              , array $stackTrace = null
                              , $caller = null) {
    $this->errNum = $errNum;
    $this->errMsg = $errMsg;
    $this->stackTrace = $stackTrace;
    $this->caller = $caller;
  }
}

?>
