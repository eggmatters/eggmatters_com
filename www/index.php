<?php
require_once '../classes/domains/Categories.php';
require_once '../classes/domains/Post.php';

$rs = Post::getHeroPost();
$heroPost = $rs[0];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once 'views/header.php'; ?>

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
            <div class="span6">
              <h2><i>Eggmatters.com</i></h2>
              <p>
                Welcome to eggmatters.com! This is my "internet journal" or "web log." Don't get me wrong, I like the
                term blog. It kind of sounds like 'blargh" which kind of sounds like someone vomiting. Which, if you think
                about it, that's basically what most blogs are. A massive regurgitation on the internet. This blog is no different.
                I started this because I didn't take the hint from twitter or facebook that said "Nobody wants to read your long-winded
                blog entries." That shouldn't mean that I don't get to write them. There's gotta be some poor sap out there who
                would read something of mine and say to themselves, "Why yes, I <i>did</i> receive a modicum of entertainment from this"
              </p><p>At any rate, read on and enjoy and for god's sake interact! Leave me comments, send me a message with ideas, or suggesstions
                or constructive criticism veiled with sappy compliments. Because I don't take negative feedback very well.
                I will be adding features, expanding functionality and slaying haters, so stay tuned, check in and have a good time.
                Thanks!</p><p>Matthew  Eggers (eggmatters)</p>
              </p>
            </div><!--/span-->
          </div><!--/row-->
          <div class="hero-unit-medium span6">
            <h3>Recently posted in: <?php echo $heroPost->categoryName; ?></h3>
            <h4><i><?php echo $heroPost->headline?></i></h4>
            <p><?php echo $heroPost->body; ?></p>
            <p><a href="post.php?pid=<?php echo $heroPost->id; ?>" class="btn btn-primary btn-medium">Comment on this Post &raquo;</a></p>
          </div>
        </div><!--/span-->
      </div><!--/row-->

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
    <script src="../assets/js/navlogin.js"></script>
    <script>
      $(document).ready( function() {
        $('#home').addClass('active');
      });
    </script>
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

function getHeroPost() {

}
?>
