<?php
require_once '../classes/utilities/PdoMysql.php';
require_once '../classes/utilities/ErrorObject.php';

/**
 * Description of categories
 *
 * @author matthewe
 */
class Categories {
  /**
   *
   * @var int
   */
  public $id;
  /**
   *
   * @var string
   */
  public $category;
  /**
   *
   * @var array
   */
  public $subCategories;

  /**
   *
   * @param int $id
   * @param string $category
   * @param array $subCategories
   */
  public function __construct($id, $category, $subCategories = null ) {
    $this->id = $id;
    $this->category = $category;
    $this->subCategories = $subCategories;
  }

  public static function getAll($orderBy = '') {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT * FROM categories $orderBy";
    $rv = $pdoMysql->query($sql, array());
    if (is_a($rv, 'ErrorObject')){
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function getAllParents() {
    $pdoMysql= new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT cat.* FROM categories cat
            WHERE cat.id NOT IN (
              SELECT category_id FROM sub_categories)
            ORDER BY id";
    $rv = $pdoMysql->query($sql, array());
    if (is_a($rv, 'ErrorObject')){
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());

  }

  public static function getHierarchy(array &$categories, $depth = null) {
    if (count($categories) == 0) {
      $categories = self::getAllParents();
    }
    foreach($categories as $category) {
      $subCategories = ($category->id) ? self::getSubCategories($category->id):  null;
      if (!is_null($subCategories)) {
        $category->subCategories = $subCategories;
        if (!is_null($depth)) {
          if ($depth == 0) {
            continue;
          } else {
            $depth--;
          }
        }
        self::getHierarchy($category->subCategories, $depth);
      }
    }

  }

  public static function getCategoryById($id) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT * from categories WHERE id=:id";
    $bindValues = array('id' => $id);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')){
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function getSubCategories($parentId) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "SELECT cat.* from categories cat, sub_categories sub
            WHERE cat.id = sub.category_id
            AND sub.parent_id=:parentId";
    $bindValues = array('parentId' => $parentId);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')){
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return self::parseResults($pdoMysql->getResultsSet());
  }

  public static function setNewCategory($newCategory) {
    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $sql = "INSERT INTO categories (`category`) VALUES ( :newCategory )";
    $bindValues = array('newCategory' => $newCategory);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')){
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return $pdoMysql->getLastInsertId();
  }

  public static function setNewSubCategory($categoryName, $parentId) {

    $pdoMysql = new PdoMysql();
    $pdoMysql->conn();
    $categoryId = self::setNewCategory($categoryName);
    if (is_a($categoryId, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $categoryId;
    }
    $sql = "INSERT INTO sub_categories
              (`category_id`, `parent_id`) VALUES
              (:categoryId, :parentId)";
    $bindValues = array('categoryId' => $categoryId, 'parentId' => $parentId);
    $rv = $pdoMysql->query($sql, $bindValues);
    if (is_a($rv, 'ErrorObject')) {
      $rv->caller = $_SERVER['SCRIPT_FILENAME'];
      return $rv;
    }
    return $categoryId;
  }

  private static function parseResults(array $rs) {
    $returnArray = array();
    if (is_null($rs)) {
      return self::setError();
    }
    if (count($rs) > 0) {
      foreach ($rs as $result) {
        $category = new Categories($result['id'], $result['category']);
        $returnArray[] = $category;
      }
      return $returnArray;
    }
    return null;
  }
  private static function setError() {
    $err = new ErrorObject();
    $err->caller($_SERVER['SCRIPT_FILENAME']);
    $err->errMsg = "The results set was not set properly";
    $err->errNum = 0;
    $err->stackTrace = '';
    return $err;
  }
}

?>
