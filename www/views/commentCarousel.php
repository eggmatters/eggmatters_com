<?php
//comment form and reply form
require_once '../classes/domains/Comments.php';

$comments = Comments::getCommentsByPostId($postId);

if (!is_null($comments)) {
  Comments::getAllCommentReplies($comments);
  $commentsBlock = "";
  showComments($comments, $commentsBlock);
}



?>
<div id="comments" class="comments-offset">
<?php if (count($comments) > 0) {
  echo $commentsBlock;
  $showBottomLink = true;
}
?>
</div>

<form id="comment-form" class="hidden">
  <input type="hidden" id="post-id" name="post-id" value="<?php echo $postId; ?>">
  <input type="hidden" id="comment-id" name="comment-id" value="">
  <div class="span12">
    <fieldset class="fieldset-override">
      <div id="comment-errors"></div>
      <?php if (empty($userName)): ?>
        <label id="comment-label">Name</label>
        <input type="text" name="comment-user" id="comment-user" value="">
      <?php else: ?>
        <label>Posting as: <strong><?php echo $userName; ?></strong></label>
      <?php endif; ?>
      <label id="comment-label">Comment: </label>
      <div>
        <textarea class="width-override" rows="10" id="comment-text" name="comment-text"></textarea>
      </div>
      <button class="btn btn-primary" type="button" id="comment-submit">Submit</button>
    </fieldset>
  </div>
</form>

<div id="comment-success" class="modal hide fade" role="dialog" aria-hidden="true">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
      <h3 id="success-header">Success!</h3>
    </div>
    <div class="modal-body">
      <p style="padding-top: 12px;" id="confirm-dialog">
        Your input is (hopefully) greatly appreciated. I will review your comment to make sure you're not
        hawking exercise pills, Refinancing loans or attempting a cross-site scripting attack and will
        approve your post with haste.
      </p>
      <p>Thank you for posting your input on eggmatters.com and have a Terrific Day!</p>
    </div>
    <div class="modal-footer">
      <button id="success-continue" data-dismiss="modal" class="btn btn-primary">Continue</button>
    </div>
</div>
<?php
function showComments($comments, &$rv, $pass = null) {
  if (!is_null($pass) && $pass == 0) {
    $pass++;
  }
  foreach ($comments as $comment) {
    $commentDate = new DateTime($comment->datePosted);
    $rv .= "<div id=\"comment-id_$comment->id\">";
    $rv .= "<div class=\"well well-override\">";
    $rv .= "<label>Posted By: <strong>$comment->userName</strong><span class=\"pull-right\">{$commentDate->format('Y-m-d')}</span></label>";
    $rv .= $comment->displayBody;
    $rv .= "<div class=\"comment-reply\"><a id=\"comment-reply_$comment->id\" href=\"#comment-form\">Reply</a></div>";
    if (!is_null($comment->replies)) {
      $pass = (is_null($pass)) ? $pass = 0 : $pass++;
      $rv .= showComments($comment->replies, $rv, $pass);
    }
    $rv .= "</div></div>";
    if ($pass == 0 || is_null($pass)) {
      $rv .= "<hr>";
    }
  }
}

?>