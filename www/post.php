<?php
require_once '../classes/domains/Categories.php';
require_once '../classes/domains/Post.php';

$postId = isset($_GET["pid"]) ? $_GET['pid'] : null;
if (!is_null($postId)) {
  $postArray = Post::getPostById($postId);
  $post = $postArray[0];
} else {
  $post = new Post(0, '', '');
  //redirect back to where they came from.
}

$currentCategory = Categories::getCategoryById($post->categoryId);
$currentActiveString = $currentCategory[0]->category;
$showBottomLink = false;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once 'views/header.php'; ?>
    <link href="../assets/css/styles/tomorrow-night-eighties.css" rel="stylesheet">
    <style>
      fieldset.fieldset-override {
        padding: 20px 20px 20px 20px;
        background-color: #FFFFFF;
        border: 1px solid #DDDDDD;
        border-radius: 4px 4px 4px 4px;
        margin: 15px 0;

      }
      .well-override {
        margin-bottom: 5px;
      }
      .comments-offset {
        margin-left: 15px;
      }
      .comment-reply {
        margin-top: 5px;
        margin-bottom: 15px;
      }
      textarea.width-override {
        -webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;

	width: 100%;
      }
    </style>
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
            <div class="span9">
              <legend><?php echo $post->headline; ?></legend>
              <h6>Posted: <?php echo date('F jS, Y', strtotime($post->postDate)); ?></h6>
            </div>
          </div>
          <div class="row-fluid">
            <div class="span9" id="post">
              <div>
                <?php echo $post->body; ?>
              </div>
            </div><!--/span-->
          </div><!--/row-->
          <div class="row-fluid">
              <div class="span6">
                <legend>Comments</legend>
                <div class="comment-reply">
                  <a href="#comment-form" id="post-reply-top">Comment on this post</a>
                </div>
                <?php
                  //formatted comments, replies, submission and verification forms contained
                  //in commentCarousel.
                  ob_start();
                  require ('views/commentCarousel.php');
                  echo ob_get_clean();
                ?>
                <?php if($showBottomLink): ?>
                  <div class="comment-reply">
                    <a href="#comment-form" id="post-reply-bottom">Comment on this post</a>
                  </div>
                <?php endif; ?>
                <p>By posting here, you agree to the following
                  <a href="about.php#disclaimer">Terms and conditions</a></p>
              </div>
          </div><!--/row-->
        </div><!--/span-->
      </div><!--/row-->
      <div class="row-fluid">
        <div class="span12" id="errordiv">
          <?php if (!empty($errors)): ?>
          <pre>
            <?php print_r($errors); ?>
          </pre>
          <?php endif; ?>
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
    <script src="../assets/js/md5.js"></script>
    <script src="../assets/js/classes.js"></script>
    <script src="../assets/js/post.js"></script>
    <script src="../assets/js/bootstrap-tooltip.js"></script>
    <script src="../assets/js/navlogin.js"></script>
    <script src="../assets/js/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
  </body>
</html>

