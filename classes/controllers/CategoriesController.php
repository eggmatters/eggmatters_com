<?php

require_once '../classes/domains/Categories.php';
require_once '../classes/domains/Challenge.php';
require_once '../classes/utilities/ErrorObject.php';
/**
 * The Router class receives and processes any json requests via the routes file.
 *
 * @author matthewe
 */
class CategoriesController {

  /**
   *
   * @param ''
   */
  public function __construct() {

  }

  public static function setCategories(array $post) {
    $authStatus = Challenge::checkAuthenticationToken($post['token']);
    if (is_a($authStatus, 'ErrorObject')){
      return $authStatus;
    }
    $categoryName = isset($post['category_name']) ? ($post['category_name']) : false;
    $categorySelected = isset($post['category_selection']) ? $post['category_selection'] : false;
    $insertId = 0;

    if ($categoryName) {
      $insertId = null;
      if (isset($post['add_sub']) && $categorySelected) {
        $insertId = Categories::setNewSubCategory($categoryName, $categorySelected);

      } else {
        $insertId = Categories::setNewCategory($categoryName);
      }
      if (is_a($insertId, 'ErrorObject')) {
        return $insertId;
      }
      return array('id' => $insertId
                   , 'name' => $categoryName);
    }
    return array();

  }

  public static function getSubCategories(array $post) {
    $categorySelected = isset($post['category-selection']) ? $post['category-selection'] : false;
    $categories = null;
    if ($categorySelected != 0) {
      $categories = Categories::getSubCategories($categorySelected);
    } else {
      $categories = Categories::getAllParents();
    }
    return (is_null($categories)) ? array() : $categories;
  }

}

?>
