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
?>
