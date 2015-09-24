<?php
require_once '../classes/domains/Users.php';
require_once '../classes/domains/Challenge.php';

$cookieHash = isset($_COOKIE['eggmatters_com']) ? $_COOKIE['eggmatters_com'] : null;
$userName = "";
Challenge::getAuthenticationToken();
if (!is_null($cookieHash)) {
  $userObj = Users::getUserByCookie($cookieHash);
  if (!is_a($userObj, 'ErrorObject')) {
    $userName = $userObj[0]->userName;
  }
}

if (!isset($userName) || $userName != "eggmatters") {
  $host  = $_SERVER['HTTP_HOST'];
  $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
  $extra = 'index.php';
  header("Location: http://$host$uri/$extra");
}
header('Cache-Control: private, no-cache, max-age=0, must-revalidate');
header('Expires: ' . date('r'));

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Eggmatters.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
        padding-left: 2%;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      fieldset.fieldset-override {
        padding: 40px 40px 40px 40px;
        background-color: #FFFFFF;
        border: 1px solid #DDDDDD;
        border-radius: 4px 4px 4px 4px;
        margin: 15px 0;
        position: relative;
      }
      textarea .width-override {
        width: 32.5531915%;
      }
      side-by-side {
        margin-left: 5px;
        padding: 14px 15px 15px;
        text-align: right;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
    </style>
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="index.php">Home</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

   <div class="row-fluid">
      <div class="span12">
        <div class="span4" id="category-selector">
          <legend>Actions</legend>

          <ul class="nav nav-tabs">
            <li class="active"><a href="#create-category" data-toggle="tab">Create Category</a></li>
            <li><a href="#edit-post" data-toggle="tab">Edit Post</a></li>
            <li><a href="#create-post" data-toggle="tab">Create Post</a></li>
            <li><a href="#view-comments" id="view-comments" data-toggle="tab">View Comments</a></li>
          </ul>
          <div class="tab-content">

            <div class="tab-pane active" id="create-category">
              <div class="category-create-error"></div>
              <form id="categories-form">
                <fieldset class="fieldset-override">
                  <label class="select">Categories:</label>
                  <div id="categories-create">

                  </div>
                  <label>New Category</label>
                  <input type="text" id="category-name">
                  <label class="checkbox">
                    <input type="checkbox" id="add-sub">Add as sub category
                  </label>
                  <br />
                  <button class="btn btn-primary" type="button" id="category-submit">Enter</button>
                </fieldset>
              </form>
            </div>
            <div class="tab-pane" id="edit-post">
              <form id="post-select">
                <fieldset class="fieldset-override">
                  <label class="select">Select Post Category:</label>
                  <div id="categories-edit">

                  </div>
                  <br>
                  <button class="btn btn-primary" type="button" id="category-post-submit">Enter</button>
                  <label style="padding-top: 5%;">Posts</label>
                  <div class="post-list">

                  </div>
                </fieldset>
              </form>
            </div>
            <div class="tab-pane" id="create-post">
              <form id="post-create">
                <fieldset class="fieldset-override">
                  <label class="select">New Post:</label>
                  <div id="categories-post">

                  </div>
                  <br />
                  <button class="btn btn-primary" type="button" id="post-create-submit">Enter</button>
                </fieldset>
              </form>
            </div>
          </div>
        </div>

        <!-- POST BODY -->
        <!-- <div class="span6 hidden" id="post-body"> -->
        <div class="span6">
          <div class="collapse" data-toggle="collapse" id="post-body">
            <legend>Post</legend>
            <div class="post-error"></div>
            <form id="post-form">
              <fieldset class="fieldset-override">
                <label id="post-category-label"></label>
                <input type="text" name="headline" style="width: 575px;"  id="headline" value="">
                <textarea style="width: 575px;" rows="20" id="post-text" name="post-text"></textarea>
                <br />
                <button class="btn btn-primary" type="button" id="post-preview-submit">Preview</button>
                <button class="btn btn-primary" type="button" id="post-preview-dismiss">Dismiss</button>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row-fluid collapse" data-toggle="collapse" id="post-preview">
      <div class="span12">
        <div class="span10">
          <legend>Post Preview</legend>
          <form id="post-view-form">
            <fieldset class="fieldset-override">
              <label id="post-category-list-label"></label>
              <div id="post-view">
                <label id="post-view-category"></label>
                <label id="post-view-headline"></label>
                <fieldset class="fieldset-override">
                  <div id="post-view-body"></div>
                </fieldset>
              </div>
              <div class="side-by-side">
                <button class="btn btn-primary" type="button" id="post-publish-submit">Publish</button>
                <button class="btn btn-primary" type="button" id="post-dismiss">Dismiss</button>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>

  <div class="row-fluid collapse" data-toggle="collapse" id="comments-approval">
    <div class="span10">
      <legend>Comments Awaiting Approval</legend>
      <table class="table table-bordered table-striped table-hover" id="comments-listing">
        <thead>
          <tr>
            <td>Id</td>
            <td>User</td>
            <td>Comment</td>
            <td></td>
            <td></td>
          </tr>
        </thead>
        <tbody id="comments-pending">

        </tbody>

      </table>
    </div>
  </div>

  <div class="row-fluid" id="errordiv"></div>
  <script src="../assets/js/jquery-1.10.2.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/admin/eggsmin.js"></script>
    <script src="../assets/js/classes.js"></script>
    <script src="../assets/js/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</body>
</html>