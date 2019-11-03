<?php

use yii\helpers\Html;
use yii\helpers\Url;

$guid = $object->container->attributes['guid'];
$sguid = (!empty($object->container->attributes['url'])?$object->container->attributes['url']:"all-post");
?>

<div id="comment_create_form_<?= $id; ?>" class="comment_create" data-ui-widget="comment.Form">

    <?= Html::hiddenInput('contentModel', $modelName); ?>
    <?= Html::hiddenInput('contentId', $modelId); ?>

    <?=
    humhub\widgets\RichtextField::widget([
        'id' => 'newCommentForm_' . $id,
        'placeholder' => Yii::t('CommentModule.widgets_views_form', 'Write a new comment...'),
        'name' => 'message'
    ]);
    ?>
    <div class="comment-buttons">
        <a href="javascript:;" id="commentSubmitBtn_<?= $modelId ?>" class="btn btn-sm btn-default btn-comment-submit pull-left" onclick="guestUsers.comment(<?= $postId ?>,<?= $modelId ?>,'<?= $guid ?>')">
            <?= Yii::t('CommentModule.widgets_views_form', 'Send') ?>
        </a>
    </div>

    <?=
    \humhub\modules\file\widgets\FilePreview::widget([
        'id' => 'comment_create_upload_preview_' . $id,
        'options' => ['style' => 'margin-top:10px'],
        'edit' => true
    ]);
    ?>
    <div class="custom-comment-addons">
      <?=
        \humhub\modules\custom\widgets\PostAsGuest::widget([
            'sguid' => $sguid,
            'postId' => $postId,
            'modelId' => $modelId,
            'isComment' => true
        ]);
      ?>
    </div>
</div>
