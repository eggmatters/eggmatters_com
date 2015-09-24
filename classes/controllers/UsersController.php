<?php

require_once '../classes/domains/Users.php';
require_once '../classes/domains/Challenge.php';
require_once '../classes/utilities/ErrorObject.php';
/**
 * Description of UsersController
 *
 * @author matthewe
 */
class UsersController {
  public function __construct() {
  }

  public static function loginUser($post) {
    $authStatus = Challenge::checkAuthenticationToken($post['token']);
    if (is_a($authStatus, 'ErrorObject')){
      return $authStatus;
    }
    $userName = $post['username'];
    $email = $post['email'];
    $userData = Users::getUser($userName, $email);
    if (is_null($userData)) {
      $userData = (object) Array('status' => 'not_found', 'username' => $userName, 'email' => $email);
    } else {
      if (!is_a($userData, 'ErrorObject')) {
        $latestUser = count($userData);
        $userData = $userData[$latestUser - 1];
        $userData->status = 'found';
        $emailHash = md5($email);
        if (isset($post['set-cookie'])) {
          setcookie('eggmatters_com', $emailHash, strtotime('+30 days'));
        } else {
          setcookie('eggmatters_com', $emailHash, 0);
        }
      }

    }
    return ($userData);
  }

  public function logoutUser() {
    setcookie('eggmatters_com', '', 0);
  }

  public static function registerNewUser($post) {
    $authStatus = Challenge::checkAuthenticationToken($post['token']);
    if (is_a($authStatus, 'ErrorObject')){
      return $authStatus;
    }
    $userName = $post['username'];
    $email = $post['email'];
    $emailHash = md5($email);
    $today = new DateTime('NOW');
    $newUser = new Users(null, $userName, Users::GUEST, $today->format('Y-m-d H:i'), $email, $emailHash);
    $userId = Users::setNewUser($newUser);
    if (is_a($userId, 'ErrorObject')){
      return $userId;
    }
    if (isset($post['set-cookie'])) {
      setcookie('eggmatters_com', $emailHash, strtotime('+30 days'));
    } else {
      setcookie('eggmatters_com', $emailHash, 0);
    }
    return $newUser;
  }
  
  public static function sendErrorMessage($post) {
    $authStatus = Challenge::checkAuthenticationToken($post['token']);
    if (is_a($authStatus, 'ErrorObject')){
      return $authStatus;
    }
    alertWhoops($post);
  }

  public static function registerFakeUser($post) {
    $userName = $post['username'];
    $email = $post['email'];
    $fakeUser = Users::getUser($userName, $email);
    setcookie('eggmatters_com', md5($email), 0);
    return $fakeUser[0];
  }
  
  private static function alertWhoops($post) {
    require_once '../classes/utilities/class.phpmailer.php';
    ob_start();
    var_dump($post);
    $data = ob_get_clean();
    $mail = new phpmailer();
    $mail->IsSendmail();
    $mail->FromName = "FuckUps";
    $mail->From = 'comment_notifier@eggmatters.com';
    $mail->Subject = "something fucked up";
    $mail->Host = $_SERVER['SERVER_NAME'];
    $mail->Body = $data;
    $mail->AddAddress("comment_notifier@eggmatters.com", "Comment Notifier");
    $mail->Send();
  }

}

?>
