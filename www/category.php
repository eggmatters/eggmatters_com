<?php
//renders a category tree from level clicked.
require_once '../classes/domains/Categories.php';
require_once '../classes/domains/Post.php';
$user = "eggmatters";
$categoryId = isset($_GET["cid"]) ? $_GET['cid'] : null;
$categories = array();
$postsArray = array();
$postErr    = array();
if (!is_null($categoryId)) {
  $categories = Categories::getCategoryById($categoryId);
  $currentActiveString = $categories[0]->category;
  Categories::getHierarchy($categories);
  fetchPosts($categories, $postsArray, $postErr);
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once 'views/header.php'; ?>
    <script type="text/javascript">
      <?php
        require_once 'views/header.php';
        echo "\ncategories=".json_encode($categories).";\n";
        echo "posts=".json_encode($postsArray).";\n";
        echo "postErr=".json_encode($postErr).";\n";
        ?>
    </script>
  </head>

  <body>

    <?php require_once 'views/topNav.php'; ?>

    <div class="container-fluid">
      <div class="row-fluid">
        <!-- echo formulated nav -->
        <?php
          ob_start();
          require ('views/leftNav.php');
          echo ob_get_clean();
        ?>
        <div class="span9">

          <!-- 12 span units per row -->
          <div class="row-fluid">
            <div class="span12" id="category">

            </div><!--/span-->
          </div><!--/row-->
          <div class="row-fluid">
          </div><!--/row-->
        </div><!--/span-->
      </div><!--/row-->
      <div class="row-fluid">
        <div class="span12" id="errordiv">

        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; Eggmatters.com 2013</p>
      </footer>

    </div><!--/.fluid-container-->
    <?php
      ob_start();
      require ('views/loginModal.php');
      echo ob_get_clean();
    ?>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/classes.js"></script>
    <script src="../assets/js/categories.php.js"></script>
    <script src="../assets/js/navlogin.js"></script>
<!--     <script src="../assets/js/bootstrap-transition.js"></script>
    <script src="../assets/js/bootstrap-alert.js"></script>
    <script src="../assets/js/bootstrap-modal.js"></script>
    <script src="../assets/js/bootstrap-dropdown.js"></script>
    <script src="../assets/js/bootstrap-scrollspy.js"></script>
    <script src="../assets/js/bootstrap-tab.js"></script>
    <script src="../assets/js/bootstrap-tooltip.js"></script>
    <script src="../assets/js/bootstrap-popover.js"></script>
    <script src="../assets/js/bootstrap-button.js"></script>
    <script src="../assets/js/bootstrap-collapse.js"></script>
    <script src="../assets/js/bootstrap-carousel.js"></script>
    <script src="../assets/js/bootstrap-typeahead.js"></script> -->

  </body>
</html>

<?php
function fetchPosts(&$categories, &$postsArray, &$postErr) {
  foreach($categories as $category) {
    $postsTmp = Post::getAllPostHeadersByCategoryId($category->id);
    if (count($postsTmp) > 0) {
      if (is_a($postsTmp, 'ErrorObject')) {
        $postErr[] = $postsTmp;
      } else {
        $postsArray = array_merge($postsArray, $postsTmp);
      }
    }
    if (!is_null($category->subCategories)) {
      fetchPosts($category->subCategories, $postsArray, $postErr);
    }
  }
}
