<?php

use humhub\libs\Html;
use yii\helpers\Url;
use humhub\modules\custom\libs\Html as QsHtml;
use humhub\widgets\TimeAgo;
use humhub\modules\space\models\Space;
//use humhub\modules\custom\widgets\Image as UserImage;
use humhub\modules\user\widgets\Image as UserImage;
use humhub\modules\content\widgets\WallEntryControls;
//use humhub\modules\questions\widgets\content\WallEntryControls;
use humhub\modules\space\widgets\Image as SpaceImage;
use humhub\modules\content\widgets\WallEntryAddons;
use humhub\modules\content\widgets\WallEntryLabels;
//use humhub\modules\topics\helpers\Seo as SeoHelper;
?>

<div class="panel panel-default wall_<?= $object->getUniqueId(); ?>">
    <div class="panel-body">

        <div class="media">
            <!-- since v1.2 -->
            <div class="stream-entry-loader"></div>

            <!-- start: show wall entry options -->
            <ul class="nav nav-pills preferences">
                <li class="dropdown ">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-label="<?= Yii::t('base', 'Toggle stream entry menu'); ?>" aria-haspopup="true">
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <?= WallEntryControls::widget(['object' => $object, 'wallEntryWidget' => $wallEntryWidget]); ?>
                    </ul>
                </li>
            </ul>
            <!-- end: show wall entry options -->

            <?=
            UserImage::widget([
                'user' => $user,
                'width' => 40,
                'htmlOptions' => ['class' => 'pull-left']/*,
                'link'=> SeoHelper::createProfilePageURL(Url::base(true).'/profile/',$user->profile->user_id,$user->profile->firstname.' '.$user->profile->lastname)*/
            ]);
            ?>

            <?php if ($showContentContainer && $container instanceof Space): ?>
                <?=
                SpaceImage::widget([
                    'space' => $container,
                    'width' => 20,
                    'htmlOptions' => ['class' => 'img-space'],
                    'link' => 'true',
                    'linkOptions' => ['class' => 'pull-left'],
                ]);
                ?>
            <?php endif; ?>
            <div class="media-body">
                <div class="media-heading">
                    <!-- ?php if($object->title){ ?>
                      <strong><a style="text-transform: capitalize;" href="< ?= SeoHelper::createQuestionPageURL(Url::base(true),$object->content->id,$object->title); ?>">< ?= $object->title; ?></a></strong><br>
                    < ?php } ? -->

                    <?= QsHtml::containerLink($user,[],/*"<span class='guest-tag'>Guest Post</span>"*/""); ?>
                    <?php if ($showContentContainer): ?>
                        <span class="viaLink">
                            <i class="fa fa-caret-right" aria-hidden="true"></i>
                            <?= Html::containerLink($container,["class"=>"text-muted"]); ?>
                        </span>
                    <?php endif; ?>

                    <div class="pull-right labels">
                        <?= WallEntryLabels::widget(['object' => $object]); ?>
                    </div>
                </div>
                <div class="media-subheading">
                    Posted: <?= TimeAgo::widget(['timestamp' => $createdAt]); ?>
                    <?php if ($updatedAt !== null) : ?>
                        &middot;
                        <span class="tt" title="<?= Yii::$app->formatter->asDateTime($updatedAt); ?>"><?= Yii::t('ContentModule.base', 'Updated'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <hr/>

            <div class="content" id="wall_content_<?= $object->getUniqueId(); ?>">
              <?= $content; ?>
            </div>

            <!-- wall-entry-addons class required since 1.2 -->
            <div class="stream-entry-addons clearfix">
                <?php
                  if(!empty(Yii::$app->user->identity->username) || Yii::$app->user->isAdmin())
                  {
                    ?>
                    <?= WallEntryAddons::widget(['object' => $object]); ?>
                    <?php
                  }
                  else
                  {
                  ?>
                    <?= humhub\modules\custom\widgets\GuestComment::widget(['object' => $object]); ?>
                  <?php
                  }
                ?>
            </div>

        </div>
    </div>
</div>
