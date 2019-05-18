<?php

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\widgets\PanelMenu;
use humhub\modules\qs\widgets\Image;
use humhub\modules\topics\helpers\Seo as SeoHelper;
?>
<div class="panel panel-default members" id="new-people-panel">
    <?= PanelMenu::widget(['id' => 'new-people-panel']); ?>

    <div class="panel-heading">
        <?= Yii::t('DirectoryModule.base', '<strong>New</strong> people'); ?>
    </div>
    <div class="panel-body">
        <?php foreach ($newUsers->limit(10)->all() as $user) : ?>
            <?= Image::widget(['user' => $user, 'width' => 40, 'showTooltip' => true, 'link'=>SeoHelper::createProfilePageURL(Url::base(true).'/profile/',$user->profile->user_id,$user->profile->firstname.' '.$user->profile->lastname)]); ?>
        <?php endforeach; ?>

        <?php if ($showInviteButton || $showMoreButton): ?>
            <hr />
        <?php endif; ?>

        <?php if ($showInviteButton): ?>
            <?= Html::a('<i class="fa fa-paper-plane" aria-hidden="true"></i>&nbsp;&nbsp;' . Yii::t('DirectoryModule.base', 'Send invite'), Url::to(['/user/invite']), array('data-target' => '#globalModal')); ?>
        <?php endif; ?>
        <?php if ($showMoreButton): ?>
            <?= Html::a('<i class="fa fa-list-ul" aria-hidden="true"></i>&nbsp;&nbsp;' . Yii::t('DirectoryModule.base', 'See all'), Url::to(['/directory/directory/members']), array('classx' => 'btn btn-xl btn-primary', 'class' => 'pull-right')); ?>
        <?php endif; ?>

    </div>
</div>
