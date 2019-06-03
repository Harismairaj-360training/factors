<?php
/* @var $this \yii\web\View */
/* @var $keyword string */
/* @var $spaces humhub\modules\space\models\Space[] */
/* @var $pagination yii\data\Pagination */

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\libs\Helpers;
use humhub\widgets\LinkPager;
use humhub\modules\space\widgets\FollowButton;
use humhub\modules\space\widgets\Image;
use humhub\modules\custom\widgets\directory\SpaceTagList;
?>
<div class="panel panel-default custom-spaces">

    <?php
    /*<div class="panel-heading">
        <?= Yii::t('DirectoryModule.base', '<strong>Space</strong> directory'); ?>
    </div>
    */
    ?>

    <div class="panel-body searchp-bar">
        <?= Html::beginForm(Url::to(['/directory/directory/spaces']), 'get', ['class' => 'form-search']); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group form-group-search">
                    <?= Html::textInput('keyword', $keyword, ['class' => 'form-control form-search', 'placeholder' => Yii::t('DirectoryModule.base', 'search for spaces')]); ?>
                    <?= Html::submitButton(Yii::t('DirectoryModule.base', ''), ['class' => 'btn btn-default btn-sm form-button-search fa fa-search']); ?>
                </div>
            </div>
        </div>
        <?= Html::endForm(); ?>

        <?php if (count($spaces) == 0): ?>
            <p><?= Yii::t('DirectoryModule.base', 'No spaces found!'); ?></p>
        <?php endif; ?>
    </div>

    <div class="panel-body">
      <ul class="media-list">
          <?php foreach ($spaces as $space) : ?>
              <li>
                  <div class="media">
                      <div class="pull-right">
                          <?=
                          FollowButton::widget([
                              'space' => $space,
                              'followOptions' => ['class' => 'btn btn-primary btn-sm'],
                              'unfollowOptions' => ['class' => 'btn btn-info btn-sm']
                          ]);
                          ?>
                      </div>

                      <?= Image::widget(['space' => $space, 'width' => 50, 'htmlOptions' => ['class' => 'default-icon media-object'], 'link' => true, 'linkOptions' => ['class' => 'pull-left']]); ?>

                      <?php /*
                      <?php if ($space->isMember()): ?>
                          <i class="fa fa-user space-member-sign tt" data-toggle="tooltip" data-placement="top" title=""
                             data-original-title="<?= Yii::t('DirectoryModule.base', 'You are a member of this space'); ?>"></i>
                         <?php endif; ?>
                      */ ?>

                      <div class="media-body">
                          <h4 class="media-heading"><a href="<?= $space->getUrl(); ?>"><?= Html::encode($space->name); ?></a>
                              <?php if ($space->isArchived()) : ?>
                                  <span class="label label-warning"><?= Yii::t('ContentModule.widgets_views_label', 'Archived'); ?></span>
                              <?php endif; ?>
                          </h4>

                          <a href="<?= $space->getUrl(); ?>"><h5><?= Html::encode(Helpers::truncateText($space->description, 300)); ?></h5></a>
                          <?= SpaceTagList::widget(['space' => $space]); ?>
                      </div>
                  </div>
              </li>
          <?php endforeach; ?>
      </ul>
    </div>
</div>

<div class="pagination-container">
    <?= LinkPager::widget(['pagination' => $pagination]); ?>
</div>
