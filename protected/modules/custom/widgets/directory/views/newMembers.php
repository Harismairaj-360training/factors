<?php

use yii\helpers\Url;
use yii\helpers\Html;
use humhub\widgets\PanelMenu;
use humhub\modules\custom\widgets\Image;
?>
<div class="panel panel-default members sidebar-custom-users" id="new-people-panel">
    <?= PanelMenu::widget(['id' => 'new-people-panel']); ?>

    <div class="panel-heading">
        <?= Yii::t('DirectoryModule.base', '<strong>New</strong> people'); ?>
    </div>
    <div class="panel-body">
        <ul class="media-list">
          <?php foreach ($newUsers->limit(10)->all() as $user) :
            if(in_array("Administration", explode(",",$user->attributes['tags'])))
            {
              continue;
            }
            $link = str_replace("/u/","/experts/",$user->getUrl());
            ?>
            <li>
              <div class="media">
                <?= Image::widget(['user' => $user, 'htmlOptions' => ['class' => 'pull-left'], 'link' => $link]); ?>
                <div class="media-body">
                    <h4 class="media-heading">
                        <a href="<?= $link; ?>"><?= Html::encode($user->displayName); ?></a>
                        <?php /*<?= UserGroupList::widget(['user' => $user]); ?> */ ?>
                    </h4>
                    <a href="<?= $link; ?>"><h5><?= Html::encode($user->profile->title); ?></h5></a>
                </div>
              </div>
              <?php /*<?= Image::widget(['user' => $user, 'width' => 40, 'showTooltip' => true]); ?> */ ?>
            </li>
          <?php endforeach; ?>
        </ul>
        <?php if ($showInviteButton || $showMoreButton): ?>
          <hr />
        <?php endif; ?>

        <?php if ($showInviteButton): ?>
            <?= Html::a('<i class="fa fa-paper-plane" aria-hidden="true"></i>&nbsp;&nbsp;' . Yii::t('DirectoryModule.base', 'Send invite'), Url::to(['/user/invite']), array('data-target' => '#globalModal')); ?>
        <?php endif; ?>
        <?php if ($showMoreButton): ?>
            <?= Html::a('<i class="fa fa-users" aria-hidden="true"></i>&nbsp;&nbsp;' . Yii::t('DirectoryModule.base', 'See All Experts'), Url::to(['/experts']), array('classx' => 'btn btn-xl btn-primary', 'class' => 'btn btn-default pull-right')); ?>
        <?php endif; ?>

    </div>
</div>
