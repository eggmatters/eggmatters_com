<?php
//renders sitemap of categories.
require_once '../classes/domains/Categories.php';
require_once '../classes/domains/Post.php';

$categories = array();
Categories::getHierarchy($categories);
$postHeaders = Post::getAllPostHeaders();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once 'views/header.php'; ?>
    <script type="text/javascript">
      <?php echo "AllCategories=".json_encode($categories).";\n";
            echo "AllPosts=".json_encode($postHeaders).";\n";
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
            <div class="span12" id="category-tree">

            </div><!--/span-->
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
    <script src="../assets/js/postTree.js"></script>
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