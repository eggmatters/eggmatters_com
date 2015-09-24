<?php
require_once '../classes/domains/Categories.php';
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
            <div class="span12">
              <h2><i>About:</i></h2>
              <p>
                Eggmatters was developed by Matthew Eggers in 2013-2014. I will be expressing opinions,
                sharing things I've learned.
              </p>
              <p>
                The site is a work in progress and is a sandbox / testbed to develop or test out different
                ideas and technologies. There is no guarantee that everything will work for everybody.
                Please contact me with questions or issues you are having with the site. See disclaimers and support sections
                below for more details (It's the official cryptic jargon).
              </p>
              <legend id="disclaimer">Disclaimer</legend>
              <p>Participants and visitors to this site will be referred to as "users". Administrators and authors of this site wil be
              referred to as "the author(s)."
              </p>
              <p>
                You are free to disseminate, quote paraphrase any content on this site. Please provide a reference but, you are not
                legally obligated to do so. By viewing content on this site, you agree to release the author from <i>any</i> and <i>all</i>
                liabilities incurred while viewing this site. You implicitly agree to the following terms: 1) You agree to not pursue any
                civil or criminal complaint against the author based on the content of this site. 2) You agree not to pursue any retaliatory measures
                outside of the bounds of free speech based on your reaction to the content of this site. This includes (but is not limited to):
                garnishment of wages or termination of employment; liens against property; denial of goods and or services or other discriminatory acts.
              </p>
              <p>
                I will not be responsible for any damages or losses incurred by parties claiming to represent the authors of this site who
                successfully attempt to gather secure user data. I will not be responsible for any sensitive data a user posts on this site.
                I will not be responsible for losses incurred to users of this site due to piracy, sofware error or computer (server or otherwise) malfunction
                or other security failures with this site.
              </p>
              <p>
                I will not be responsible for any compromise of user data or content a malicious user posts on your behalf. I will do all in my power
                to restrict this scenario and remove any post a user reports to me as being falsely posted on their behalf.
              </p>
              <legend id="privacy">Privacy Policy</legend>
              <p>I will collect two pieces of data from you: a username and an email. The email need not be valid. A valid email
                will allow me to reply to direct inquiries. I will <i>only</i> send you an email in response to an email you send me.
                You will not receive any automated email, newsletter, registration confirmation, offers etc. from me or this site. Please
                inform me <i>immediately</i> if you receive an unsolicited email from someone claiming to be me or this site. Any information I need to share with my users will be
                done via the site itself.
              </p>
              <p>Please note that other data will be provided per request as part of the http protocol.</p>
              <p>
                I will not post your email address on this site. I will not share your email address.
              </p>
              <legend id="security">Security</legend>
              <p>
                This site is serverd over an open (http) connection. All data transmitted between this site and your computer is not secure
                and can be intercepted. DO NOT under any circumstances, post secure data which may compromise your finances or identity (SSN's, passwords,
                bank account #'s etc.)
              </p>
              <p>
                I only validate a username and email address. Anybody who knows these two pieces of information can post something on your behalf.
                Please let me know immediately if you feel somebody is posting as you and I will remove any content.
              </p>
              <p>
                While all data transfer is via http, I do take measures to authenticate http requests. I cannot guarantee that all security measures
                on this site or ironclad or 100% safe. I will do my best to keep the site secure, and keep my users informed of any significant events that would
                impact the security of this site.
              </p>
              <legend id="support">Browser Support</legend>
              <p>
                I will only support browsers which are compliant with all <i>current</i> <a href='http://www.w3.org/'>WW3</a> standards. You  must
                have a browser which supports html5, css3, and ecma script version 5 (java script.) I will not respond to requests to support
                browsers which do not adhere to the above.
              </p>
              <legend id="cookie-policy">Cookie Policy:</legend>
              <p>
                I will write up to 2 cookies on your computer. They will be named: "eggmaters_com" and "eggstok" Both cookies will contain a
                string of digits and characters. The cookie: eggmatters_com contains an encrypted string of your email address that I use to verify your
                identity on each page and assign content you post as you. The 'eggstok' cookie is an encrypted string that I use to ensure that requests
                to my server are from a valid source.
                You are welcome to tamper with or alter the values of these cookies (ask me how) but, once doing, the application will not be able to
                process your requests of verify your identity.
              </p>
              <p>
                The eggmatters_com cookie is written when you first sign up and is refreshed each time you visit this site. This cookie expires 30 days
                after it is either written or refreshed. This cookie allows me to authenticate and verify your identity from your login. If you are using a public
                or shared computer, make sure to not check the "remember me" checkbox and logout when you are finished with your session.
              </p>
              <p>
                The eggstok cookie is written after login and will expire at the end of your session (when you leave the site.) This cookie is renewed
                and rewritten each time you post. The value of this cookie helps the site to determine the validity of the source of the post.
              </p>
              <p>
                Disabling cookies, or tampering with or expiring the above cookies will not allow you to interact with this site as intended.
              </p>
              <legend id="software">Software Policies</legend>
              <p>Software refered to by this policy includes: 1) Files provided either in raw text, binary or other formats
              2) code snippets and step-by-step instructions. 3) Links to other software resources.
              Software referred to externally (not provided by the authors of this site) will have its own respective licensing and or restrictions apply.
              </p>
              <p>
                Software provided by the authors is "as is" with no warranty implied or otherwise. You agree to not hold the authors of this site and the authors of the
                software provided liable for any damages resulting in defects and or misuse of the software.
              </p>
              <p>
                <i>Any</i> Software posted on this site will be unrestricted and unlicensed, with only the terms and conditions of this section applicable and the folowing caveat:
              </p>
              <p>
                Software provided by this site <i>may not</i> be used for any malicious purposes. This includes but is not limited to:
                Hacking, piracy, security compromise, fraud and identity theft. Suspected misuse of software by the authors will be reported to
                the pertinent authories.
              </p>
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
        $('#about').addClass('active');
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
