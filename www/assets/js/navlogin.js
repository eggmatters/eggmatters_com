/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready( function() {
  setNavbarState();

  $('#navbar-login').click(function (e) {
      e.preventDefault();
      var navbarUser = $('#navbar-username').val();
      var navbarEmail = $('#navbar-email').val();
      if (!loggedIn) {
        $('#loginModal').modal('show');
        $('#login-username').val(navbarUser);
        $('#login-email').val(navbarEmail);
      }
  });
  //document.cookie = "name=value; expires=date; domain=domain; path=path; secure"
  $('#navbar-logout').click(function (e) {
    e.preventDefault();
    var url = 'routes.php';
    var data = { controller: "UsersController", method: "logoutUser" };
    $.post(url, data)
       .done( function (data) {
          loggedIn = false;
          userObj.userName = "";
          userObj.cookieHash = "";
          setNavbarState();
       });
  });

  $('#challenge-answer').popover({trigger: 'click'});

  $('#user-login').submit( function(e) {
    e.preventDefault();
    if (!validateUser()) {
      return true;
    }
    $('#user-login').append("<input type='hidden' name='controller' value='UsersController'>");
    $('#user-login').append("<input type='hidden' id='user-method' name='method' value='loginUser'>");
    var url  = 'routes.php';
    var data = $('#user-login').serialize();
    data = data.concat('&' + "token=" + document.cookie.replace(/(?:(?:^|.*;\s*)eggstok\s*\=\s*([^;]*).*$)|^.*$/, "$1"));
    $.post(url, data)
      .success(function(data) {
        var dataObj = JSON.parse(data);
        if (dataObj.status === 'not_found') {
          $('#loginModal').modal('hide');
          showConfirmSignupModal(dataObj);
        } else if (dataObj.status === 'found') {
          $('#loginModal').modal('hide');
          $('#logged-in-user').html(dataObj.userName);
          showSuccessModal(dataObj);
        }
      })
      .fail(function(data) {
        var dataObj = JSON.parse(data.responseText);
        if (dataObj.errNum && dataObj.errNum === 400) {
          $('#loginModal').modal('hide');
          $('#noAuthModal').modal('show');
          sendError(data);
        } else {
          alert("Something happened. Probably bad for me, but not so much for you.");
        }

      });
  });

  $('#user-confirm').submit( function(e) {
    e.preventDefault();
    var url  = 'routes.php';
    var data = $('#user-confirm').serialize();
    data = data.concat('&' + "token=" + document.cookie.replace(/(?:(?:^|.*;\s*)eggstok\s*\=\s*([^;]*).*$)|^.*$/, "$1"));
    $.post(url, data)
      .success(function( data ) {
        var dataObj = JSON.parse(data);
        $('#confirmModal').modal('hide');
        showSuccessModal(dataObj);
      })
      .fail(function (data) {
        var dataObj = JSON.parse(data.responseText);
        if (dataObj.errNum && dataObj.errNum === 400) {
          $('#confirmModal').modal('hide');
          $('#noAuthModal').modal('show');
          sendError(data);
        } else {
          alert("Something happened. Probably bad for me, but not so much for you.");
        }
      });
  });

});

function setNavbarState() {
  if (loggedIn) {
    $('.navbar-form').addClass('hidden');
    $('.user-logged-in').removeClass('hidden');
  } else {
    $('.navbar-form').removeClass('hidden');
    $('.user-logged-in').addClass('hidden');
  }
}

function showConfirmSignupModal(signupData) {
  if (signupData.username.length > 0 && signupData.email.length > 0) {
    $('.hidden-data').remove();
    $('#confirmModal').modal('show');
    $('#not-found-dialog').removeClass('hidden');
    $('#anonymous-dialog').addClass('hidden');
    $('#confirm-header').html("Confirm New User Account");
    $('#confirm-username').html('<p>' + signupData.username + '</p>');
    $('#confirm-email').html('<p>' + signupData.email + '</p>');
    $('#user-confirm').append("<input type='hidden' class='hidden-data' name='controller' value='UsersController'>");
    $('#user-confirm').append("<input type='hidden' class='hidden-data' name='method' value='registerNewUser'>");
    $('#user-confirm').append("<input type='hidden' class='hidden-data' name='username' id='submit-username' value='" + signupData.username + "'>");
    $('#user-confirm').append("<input type='hidden' class='hidden-data' name='email' id='submit-email' value='" + signupData.email + "'>");
  } else {
    $('.hidden-data').remove();
    $('#confirmModal').modal('show');
    $('#confirm-header').html("Post as a Guest User");
    $('#not-found-dialog').addClass('hidden');
    $('#anonymous-dialog').removeClass('hidden');
    $('#user-confirm').append("<input type='hidden' class='hidden-data' name='controller' value='UsersController'>");
    $('#user-confirm').append("<input type='hidden' class='hidden-data' name='method' value='registerFakeUser'>");
  }
  if ($('#set-cookie').prop('checked')) {
    $('#user-confirm').append("<input type='hidden' name='set-cookie' value='true'>");
  }
}

function showSuccessModal(loginData) {
  loggedIn = true;
  $('#successModal').modal('show');
  $('#success-username').html("<p><strong>" + loginData.userName + "</strong></p>");
  $('#comment-user').val(loginData.userName);
  $('#logged-in-user').html(loginData.userName);
  userObj.userName = loginData.userName;
  userObj.cookieHash = loginData.bindings.user_pw;
  setNavbarState();
}

function validateUser() {
  var userAnswer = $('#challenge-question').val();
  var challengeAnswer = $('#challenge-answer').attr('data-content');
  var userName = $('#login-username').val();
  var email = $('#login-email').val();
  if (userAnswer.length === 0) {
    showError("You didn't provide an answer to the question. (Hint: click on the \"Huh\" link and copy and paste the answer.)");
    return false;
  }
  userAnswer = userAnswer.trim().toUpperCase();
  challengeAnswer = challengeAnswer.trim().toUpperCase();
  if (userAnswer !== challengeAnswer) {
    showError("Your answer is incorrect. (Hint: click on the \"Huh?\" link and copy and paste the answer.)");
    return false;
  }
  if (userName.length === 0 && email.length !== 0) {
    showError("You provided an email but not a user name");
    return false;
  }
  if (userName.length !== 0 && email.length === 0) {
    showError("You provided a user name but not an email");
    return false;
  }
  return true;

}

function showError(msg) {
  $('#login-errors').append('<div class="alert alert-block alert-error fade in">'
          + '<button class="close" data-dismiss="alert" type="button">got it!</button>'
          + '<h4 class="alert-heading"></h4>'
          + '<p>' + msg + '</p>'
          + '</div>');
}

function sendError(formData) {
  var url = 'routes.php';
  var data = {
    controller: "UsersController",
    method: "sendErrorMessage",
    cookie: cookie,
    data: formData
  }
  $.post(url, data)
    .success(function(){})
    .fail(function(){});
}