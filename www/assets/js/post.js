/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready( function() {
  $('#comment-user').val(userObj.userName);

  $('.comment-reply').click( function(e) {
    if (e.target.id.length) {
      var targetId = e.target.id.split('_');
      var commentId = targetId[1];
      if (commentId) {
        $('#comment-id').val(commentId);
      }
    }
    $('#comment-form').removeClass('hidden');
  });

  $('#comment-submit').click( function (e) {
    e.preventDefault();
    var type = 'new';
    if ($('#comment-id').val().length > 0) {
      type = 'reply';
    }
    var commentData = prepareCommentData(userObj.userName, userObj.cookieHash, $('#comment-text').val(), type, $('#comment-id').val());
    var url = "/routes.php";
    if (commentData.error) {
      handleCommentIssues(commentData);
      return true;
    }
    $.post(url, commentData)
      .success(function (data) {
        $('#comment-form').addClass('hidden');
        $('#comment-success').modal('show');
        return true;
      })
      .fail(function(jqXHR, status, err) {
        var dataObj = JSON.parse(jqXHR.responseText);
        if (dataObj.errNum && dataObj.errNum === 400) {
          $('#noAuthModal').modal('show');
        } else {
          alert("Something happened. Probably bad for me, but not so much for you.");
        }
      });
  });
});

function prepareCommentData(userName, userHash, commentData, commentType, commentId) {
  if (!(userName || userHash)) {
    return { error: 'no_user'};
  } if (commentData.length === 0) {
    return { error: 'no_data' };
  }
  return {
    "controller"  : "CommentsController",
    "method"      : (commentType === 'new') ? "setNewComment" : "setCommentReply",
    "userHash"    : userHash,
    "commentData" : commentData,
    "postId"      : $('#post-id').val(),
    "commentId"   : (commentId) ? commentId : "",
    "token"       : document.cookie.replace(/(?:(?:^|.*;\s*)eggstok\s*\=\s*([^;]*).*$)|^.*$/, "$1")
  };
}

function handleCommentIssues(userData) {
  if (userData.error === 'no_user') {
    $('#loginModal').modal('show');
    $('#login-username').val($('#comment-user').val());
    return;
  }
  if (userData.error === 'no_data') {
    showCommentError("You haven't written anything.")
  }
}

function showCommentError(msg) {
  $('#comment-errors').append('<div class="alert alert-block alert-error fade in">'
          + '<button class="close" data-dismiss="alert" type="button">got it!</button>'
          + '<h4 class="alert-heading"></h4>'
          + '<p>' + msg + '</p>'
          + '</div>');
}

