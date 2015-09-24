<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PDOBindings
 * This file does nothing.
 *
 * @author matthewe
 */
class PDOBindings {
  public $name;
  public $type;
  public $values;

  const INVALID_TYPE = -1;

  public function __construct($name, $type, $values) {
    $this->name = $name;
    $this->type = $type;
  }

  public static function getType($bindValue) {
    $itsType = gettype($bindValue);
    switch ($itsType) {
      case "string":
        return PDO::PARAM_STR;
        break;
      case "integer":
        return PDO::PARAM_INT;
        break;
      case "boolean":
        return PDO::PARAM_BOOL;
        break;
      default :
        return PDO::PARAM_STR;
    }
  }



  private static function removeSqlBinding($sql, $bindingValue) {

  }
}

?>
