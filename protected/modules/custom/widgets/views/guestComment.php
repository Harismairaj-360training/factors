<div class="wall-entry-controls">
    <?= humhub\modules\custom\widgets\CommentLink::widget(['object' => $object]); ?>
    <?= humhub\modules\like\widgets\LikeLink::widget(['object' => $object]); ?>
</div>
<div>
    <?= humhub\modules\custom\widgets\Comments::widget(['object' => $object]); ?>
</div>
