<?php

//use humhub\modules\directory\widgets\Menu;
use humhub\modules\custom\widgets\directory\NewMembers as Sidebar;
use \yii\helpers\Url;

\humhub\assets\JqueryKnobAsset::register($this);
?>

<div class="container">
    <div class="row banner">
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-body">
            <h1>Consulting Experts</h1>
            <p>360Experts are former and existing compliance managers, regulators, system operators and consultants in different industries.</p>
            <a href="<?= Url::toRoute('/experts'); ?>" class="label label-default">Read More</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-body">
            <h1>Find GRC Topics</h1>
            <p>Discover the latest industry news, uncover solutions to GRC problems, and see what topics are trending in the GRC sector.</p>
            <a href="<?= Url::toRoute('/topics'); ?>" class="label label-default">Read More</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-body">
            <h1>GRC Insight</h1>
            <p>Thought leadership and discussions about the current state and the future of corporate governance, risk, and compliance.</p>
            <a href="https://www.360factors.com/blog/" class="label label-default">Read More</a>
          </div>
        </div>
      </div>
    </div>
    <div class="middle-text">
        <h1 style="margin-top:-10px !important; margin-bottom:-20px !important;">360ExpertConnect - Mentoring & Discussions</h1>
		<p style="text-align: center;">You can join 360factor GRC mentoring & discussions for Free Ask Question, Give Answer, Discuss GRC Problems, Learn, and Grow</p>
    </div>
    <div class="row">
        <?php /*<div class="col-md-2">
            <?= Menu::widget(); ?>
        </div> */?>
        <div class="col-md-9">
            <?= $content; ?>
        </div>
        <div class="col-md-3">
            <?= Sidebar::widget(); ?>
            <?= \humhub\modules\dashboard\widgets\DashboardContent::widget(); ?>
            <?php
            echo \humhub\modules\dashboard\widgets\Sidebar::widget([
                'widgets' => [
                    [
                        \humhub\modules\activity\widgets\Stream::className(),
                        ['streamAction' => '/dashboard/dashboard/stream'],
                        ['sortOrder' => 150]
                    ]
                ]
            ]);
            ?>
        </div>

    </div>
</div>
