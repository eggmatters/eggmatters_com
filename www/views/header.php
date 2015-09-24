<?php
//not included in eggsmin.php
require_once '../classes/utilities/auth.php';
header('Cache-Control: private, no-cache, max-age=0, must-revalidate');
header('Expires: ' . date('r'));
?>
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
      }
      .sidebar-nav {
        padding: 9px 0;
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
   <script>
   <?php if (!empty($userName)): ?>
     var loggedIn = true;
     var userName = "<?php echo $userName; ?>";
   <?php else: ?>
     var loggedIn = false;
     var userName = "";
   <?php endif; ?>
     var userObj = { "userName": "<?php echo is_null($userName) ? '' : $userName; ?>",
                     "cookieHash": "<?php echo is_null($cookieHash) ? '' : $cookieHash; ?>" };
   </script>
