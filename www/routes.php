<?php
require_once '../classes/utilities/ErrorObject.php';
/*
 * All ajax calls will go through here.
 * This script is will parse the controller & perform the necessary requests.
 */

$controllerName = $_POST['controller'];

require_once "../classes/controllers/$controllerName.php";

$controllerAction = $_POST['method'];

$controllerResults = $controllerName::$controllerAction($_POST);

if (is_a($controllerResults, 'ErrorObject')){
  header("HTTP/1.1 422 Unprocessable Entity");
}

echo json_encode($controllerResults);
//$scriptUrl = $_SERVER['SCRIPT_URL']
//$today = date('Y-m-d', strtotime(time()));
//$hash = md5($sess->userId . $today . "I used to use perl but now I dont anymore");

?>
