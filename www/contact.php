<?php
//nobody has sent me anything.
require_once '../classes/domains/Categories.php';
require_once '../classes/domains/Challenge.php';
require_once '../classes/utilities/class.phpmailer.php';

$mailSend = false;

$emailChallenges = Challenge::issueChallenge();
$emailChallenge = $emailChallenges[0];

if (isset($_POST['Send'])) {
  $name = isset($_POST['inputName']) ? $_POST['inputName'] : "no name";
  $email = isset($_POST['inputEmail']) ? $_POST['inputEmail'] : "nobody@eggmatters.com";
  $subject = isset($_POST['inputSubject']) ? $_POST['inputSubject'] : "no subject";
  $data = isset($_POST['inputText']) ? $_POST['inputText'] : "no message";
  try {
    $success = sendMail($name, $subject, $data, $email);
  } catch (Exception $e ) {
    $success = false;
  }
  if ($success) {
    $mailSend = true;
  }

}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php require_once 'views/header.php';
        $name = $email = "";
        if (isset($userObj)) {
          $name = $userObj[0]->userName;
          $email = $userObj[0]->email;
        }
    ?>
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
            <div class="span12">
              <?php if (!$mailSend): ?>
              <h2>Drop me a line:</h2>
              <form class="form-horizontal" id="email-send" action="contact.php" method="post">
                <div class="input-error"></div>
                <div class="control-group">
                  <label class="control-label" for="inputName">Name</label>
                  <div class="controls">
                    <input type="text" id="inputName" name="inputName" value="<?php echo $name; ?>">
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="inputEmail">Email</label>
                  <div class="controls">
                    <input type="text" id="inputEmail" name="inputEmail" value="<?php echo $email; ?>">
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="inputSubject">Subject</label>
                  <div class="controls">
                    <input type="text" id="inputSubject" name="inputSubject" value="">
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="inputText">Message</label>
                  <div class="controls">
                    <textarea id="inputText" name="inputText" rows="10"></textarea>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="ChallengeQuestion"></label>
                  <div class="controls">
                      <label>Prove that you're not a viagra-bot:</label>
                      <label><i>(Click on Huh? for the specific answer, do a little ctrl-c ctrl-v and you're in!)</i></label>
                      <p><?php echo $emailChallenge->question; ?></p>
                      <input type="text" id="email-challenge-question" >
                      <a id="email-challenge-answer" title="" data-content="<?php echo $emailChallenge->answer; ?>"
                         data-placement="right"
                         data-original-title="Shhh. The answer is:">Huh?</a>
                    </div>
                </div>
                <div class="control-group">
                  <div class="controls">
                    <button type="submit" id="Send" class="btn" name="Send">Submit</button>
                  </div>
                </div>
              </form>
              <?php else: ?>
              <h2>Your mail has been sent! Thank you.</h2>
              <?php endif; ?>
            </div><!--/span-->
          </div><!--/row-->
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
        var sendattempts = 0;
        $('#contact').addClass('active');
        $('#email-challenge-answer').popover({trigger: 'click'});
        $('#email-send').submit( function() {
          sendattempts++;
          var retval = true;
          var userAnswer = $('#email-challenge-question').val().trim().toUpperCase();
          var challengeAnswer = $('#email-challenge-answer').attr('data-content').trim().toUpperCase();
          if (userAnswer !== challengeAnswer) {
            showSendError("You did not provide the correct answer.", sendattempts);
            retval = false;
          }
          if ($('#inputEmail').val().length === 0) {
            showSendError("You did not provide an email", sendattempts);
            retval = false;
          }
          if ($('#inputText').val().length === 0) {
            showSendError("You did not provide a message", sendattempts);
            retval = false;
          }
          if (sendattempts > 2 && !retval) {
            window.location = "index.php";
          }
          return retval;
        });
        function showSendError(msg, attempts) {
          if (attempts === 2) {
            msg = msg.concat(" This is your last attempt. If you don't see a confirmation message, your message did not send.");
          }
          $('.input-error').append('<div class="alert alert-block alert-error fade in">'
          + '<button class="close" data-dismiss="alert" type="button">got it!</button>'
          + '<h4 class="alert-heading"></h4>'
          + '<p>' + msg + '</p>'
          + '</div>');
        }

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
function sendMail($name, $subject, $data, $email) {
  $mail = new phpmailer();
  $mail->IsSendmail();
  $mailBody = "From: $name\nEmail: $email\n$data";
  $mail->FromName = $name;
  $mail->From = 'site_questions@eggmatters.com';
  $mail->Subject = $subject;
  $mail->Host = $_SERVER['SERVER_NAME'];
  $mail->Body = $mailBody;
  $mail->AddAddress("site_questions@eggmatters.com", "Site Questions");
  $status = $mail->Send();
  return $status;
}
