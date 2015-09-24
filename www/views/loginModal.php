<?php
require_once '../classes/domains/Users.php';

$challenges = Challenge::issueChallenge();
$challenge = $challenges[0];

$fakeUsers = Users::getFakeUser();
$fakeUser = null;
if (is_a($fakeUser, 'ErrorObject')) {
  $fakemail = "usererror@eggmatters.com";
  $fakeUser = new Users(null, 'Ray Bradbury', 4, $_SERVER['REQUEST_TIME'], $fakemail, md5($fakemail));
} else {
  $idx = rand(0, count($fakeUsers) - 1);
  $fakeUser = $fakeUsers[$idx];
}
?>
<div id="loginModal" class="modal hide fade" role="dialog" aria-hidden="true">
  <form id="user-login">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
      <h3>Login / Signup</h3>
    </div>
    <div class="modal-body">
      <div id='login-errors'></div>
      <label>Name</label>
      <input type="text" id="login-username" name="username" value="">
      <label>Email</label>
      <input type="email" id="login-email" name="email" value="">
      <label>Prove that you're not a viagra-bot:</label>
      <label><i>(Click on Huh? for the specific answer, do a little ctrl-c ctrl-v and you're in!)</i></label>
      <p><?php echo $challenge->question; ?></p>
      <input type="text" id="challenge-question" name="challenge" value="">
      <a id="challenge-answer" title="" data-content="<?php echo $challenge->answer; ?>"
         data-placement="right"
         data-original-title="Shhh. The answer is:">Huh?</a>

      <label>Remember me</label>
      <input type="checkbox" name="set-cookie" id="set-cookie" checked>
      <p style="padding-top: 12px;">You agree to the following
        <a href="about.php#disclaimer">Terms and conditions</a>
      </p>

      <div class="modal-footer">
        <button id="login-user" class="btn btn-primary">Login</button>
      </div>
    </div>
  </form>
</div>

<div id="confirmModal" class="modal hide fade" role="dialog" aria-hidden="true">
  <form id="user-confirm">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
      <h3 id="confirm-header">Confirm New User Account</h3>
    </div>
    <div class="modal-body">
      <div id="not-found-dialog">
        <p style="padding-top: 12px;" id="confirm-dialog">
          The username and email you entered were not found. Click "Dismiss" to try again, or click "Continue" to register
          the following user:
        </p>
        <label>User Name:</label>
        <span id="confirm-username"></span>
        <label>Email:</label>
        <span id="confirm-email"></span>
      </div>
      <div id ="anonymous-dialog" class="hidden">
        <p style="padding-top: 12px;">
          You have not provided a username. I have picked one for you. This allows you to post to this thread. If you want to
          post to another thread anonymously, I will pick another name for that thread. This way, we don't wind up with a
          bunch of people posting as "Guest User" or "Anonymous" all saying different things.
        </p>
        <label>User Name:</label>
        <span id="anonymous-username"><?php echo $fakeUser->userName; ?></span>
        <input type="hidden" id="anonymous-user" name="username" value="<?php echo $fakeUser->userName; ?>" />
        <input type="hidden" id="anonymous-email" name="email" value="<?php echo $fakeUser->email; ?>" />
      </div>

      <div class="modal-footer">
        <button id="confirm-dismiss" class="btn" data-dismiss="modal" aria-hidden="true">Dismiss</button>
        <button id="confirm-continue" class="btn btn-primary">Continue</button>
      </div>
    </div>
  </form>
</div>

<div id="successModal" class="modal hide fade" role="dialog" aria-hidden="true">
  <form id="login-success">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
      <h3 id="success-header">Success! (never doubted it for a minute)</h3>
    </div>
    <div class="modal-body">
      <p style="padding-top: 12px;" id="confirm-dialog">
        You are now logged in as the following user:
      </p>
      <label>User Name:</label>
      <span id="success-username"></span>
    </div>
    <div class="modal-footer">
      <button id="success-continue" data-dismiss="modal" class="btn btn-primary">Continue</button>
    </div>
  </form>
</div>

<div id="noAuthModal" class="modal hide fade" role="dialog" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
      <h3 id="noauth-header">Aaaaaand something got screwed up.</h3>
    </div>
    <div class="modal-body">
      <p style="padding-top: 12px;" id="confirm-dialog">
        Please check and see if cookies are enabled for this site. This site needs to be able to write
        cookies to your browser in order to maintain security.
      </p>
    </div>
    <div class="modal-footer">

    </div>
</div>