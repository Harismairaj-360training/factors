<?php
use yii\helpers\Html;
use yii\helpers\Url;
$commentInputId = 'newCommentForm_humhubmodulespostmodelsPost_'.$modelId;
?>

<div class="panel panel-default clearfix">
    <div class="panel-body">
        <div>
          <?=
          humhub\widgets\RichtextField::widget([
              'id' => $commentInputId,
              'placeholder' => Yii::t('CommentModule.widgets_views_form', 'What\'s in your mind?'),
              'name' => 'guestPost'
          ]);
          ?>
        </div>
        <div class="contentForm_options custom-comment-addons guest-hidden-section2" style="display: bock;">
            <div>
              <div class="pull-left2">
                <?=
                  \humhub\modules\custom\widgets\PostAsGuest::widget([
                      'sguid' => $sguid,
                      'postId' => $postId,
                      'modelId' => $modelId,
                      'isComment' => false
                  ]);
                ?>
              </div>
              <div class="pull-right2">
                  <a id="postSubmitBtn_<?= $modelId ?>" href="javascript:;" class="btn btn-info btn-comment-submit" onclick="guestUsers.post(<?= $modelId ?>,'<?= $guid ?>')">
                      <?= Yii::t('CommentModule.widgets_views_form', 'Send') ?>
                  </a>
              </div>
            </div>
        </div>
    </div>
</div>
<!-- script>
  $(document).ready(function()
  {
    $("#< ?php echo $commentInputId ?>").bind("focus",function()
    {
      $(".guest-hidden-section").fadeIn(300);
    });
  })
</script -->
