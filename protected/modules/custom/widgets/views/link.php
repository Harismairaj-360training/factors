<?php

use yii\helpers\Html;
use yii\helpers\Url;

$commentCount = $this->context->getCommentsCount();
$hasComments = ($commentCount > 0);
$commentCountSpan = Html::tag('span', ' ('.$commentCount.')', [
    'class' => 'comment-count',
    'data-count' => $commentCount,
    'style' => ($hasComments) ? null : 'display:none'
]);

?>

<?= Html::a(Yii::t('CommentModule.widgets_views_link', "Comment").$commentCountSpan, "#",['onClick' => "$('#comment_" . $id . "').slideToggle('fast');$('#newCommentForm_" . $id . "').focus();return false;"]); ?>&nbsp;&middot;&nbsp;
