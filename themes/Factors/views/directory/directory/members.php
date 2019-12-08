<?php
/* @var $this \yii\web\View */
/* @var $keyword string */
/* @var $group humhub\modules\user\models\Group */
/* @var $users humhub\modules\user\models\User[] */
/* @var $pagination yii\data\Pagination */

use yii\helpers\Html;
use humhub\modules\custom\widgets\Image;
use humhub\modules\directory\widgets\MemberActionsButton;
use humhub\modules\directory\widgets\UserTagList;
use humhub\modules\directory\widgets\UserGroupList;
?>
<div class="panel panel-default custom-users">

    <?php /*
    <div class="panel-heading">
        <?php if ($group === null) : ?>
            <?= Yii::t('DirectoryModule.base', '<strong>Member</strong> directory'); ?>
        <?php else: ?>
            <?= Yii::t('DirectoryModule.base', '<strong>Group</strong> members - {group}', ['{group}' => Html::encode($group->name)]); ?>
        <?php endif; ?>
    </div>
    */ ?>

    <div class="panel-body searchp-bar">
        <?= Html::beginForm('', 'get', ['class' => 'form-search']); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group form-group-search">
                    <?= Html::textInput("keyword", $keyword, ['class' => 'form-control form-search', 'placeholder' => Yii::t('DirectoryModule.base', 'search for members')]); ?>
                    <?= Html::submitButton(Yii::t('DirectoryModule.base', ''), ['class' => 'btn btn-default btn-sm form-button-search fa fa-search']); ?>
                </div>
            </div>
        </div>
        <?= Html::endForm(); ?>

        <?php if (count($users) == 0): ?>
            <p><?= Yii::t('DirectoryModule.base', 'No members found!'); ?></p>
        <?php endif; ?>
    </div>

    <div class="panel-body">
      <ul class="media-list">
          <?php foreach ($users as $user) :
            if(in_array("Administration", explode(",",$user->attributes['tags'])))
            {
              continue;
            }
            ?>
              <li>
                  <div class="media">
                      <div class="pull-right memberActions">
                          <?= MemberActionsButton::widget(['user' => $user]); ?>
                      </div>
                      <?php
                        $link = str_replace("/u/","/experts/",$user->getUrl());
                      ?>
                      <?= Image::widget(['user' => $user, 'htmlOptions' => ['class' => 'pull-left'], 'link'=> $link]); ?>
                      <div class="media-body">
                          <h4 class="media-heading">
                              <a href="<?= $link; ?>"><?= Html::encode($user->displayName); ?></a>
                              <?php /*<?= UserGroupList::widget(['user' => $user]); ?> */ ?>
                          </h4>
                          <a href="<?= $link; ?>"><h5><?= Html::encode($user->profile->title); ?></h5></a>
                          <?= UserTagList::widget(['user' => $user]); ?>
                      </div>
                  </div>
              </li>
          <?php endforeach; ?>
      </ul>
    </div>
</div>

<div class="pagination-container">
    <?= \humhub\widgets\LinkPager::widget(['pagination' => $pagination]); ?>
</div>
