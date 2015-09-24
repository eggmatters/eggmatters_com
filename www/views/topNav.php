<?php

?>
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <div class="nav-collapse collapse">
        <div class="navbar-text pull-right user-logged-in">
          <ul class="nav">
            <li><a href="#">Logged in as <span id="logged-in-user"><?php echo $userName; ?></span></a></li>
            <li><a href="#" id="navbar-logout">&nbsp;&nbsp;Logout</a></li>
          </ul>
        </div>
        <form name="navbar_login" class="navbar-form pull-right" id="navbar-login-form">
          <span class="navbar-text">
            Login / Signup Username:&nbsp;
          <input type="input" class="input-small" id="navbar-username" name="username" value="">
            Email:&nbsp;
          <input type="input" class="input-small" id="navbar-email" name="email" value="">
          &nbsp;<button type="submit" id="navbar-login" class="btn btn-inverse btn-small">Go</button>
          </span>
        </form>
        <ul class="nav">
          <li id="home"><a href="index.php">Home</a></li>
          <li id="about"><a href="about.php">Mumbo Jumbo</a></li>
	  <li id="contact"><a href="contact.php">Contact</a></li>
          <li id="tree"><a href="categoryTree.php">View Categories</a></li>
          <?php if (isset($currentActiveString)): ?>
            <li class="active"><a href="#"><?php echo $currentActiveString; ?></a></li>
          <?php endif; ?>
          <?php if ($userName == "eggmatters"): ?>
          <li><a href="eggsmin.php">eggsmin</a>
          <?php endif; ?>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

