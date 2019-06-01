<?php

//use humhub\modules\directory\widgets\Menu;
use humhub\modules\directory\widgets\Sidebar;

\humhub\assets\JqueryKnobAsset::register($this);
?>

<div class="container">
    <div class="row banner">
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-body">
            <h1>Consulting Experts</h1>
            <p>Curabitur ac lacus arcu. Sed vehicula varius lectus auctor viverra. Vehicula nibh vel ante commodo feugiat. Nulla ut enim lobortis orci gravida volutpat.</p>
            <a href="#" class="label label-default">Read More</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-body">
            <h1>Find GRC Topics</h1>
            <p>Curabitur ac lacus arcu. Sed vehicula varius lectus auctor viverra. Vehicula nibh vel ante commodo feugiat. Nulla ut enim lobortis orci gravida volutpat.</p>
            <a href="#" class="label label-default">Read More</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-body">
            <h1>Interact With A Community</h1>
            <p>Curabitur ac lacus arcu. Sed vehicula varius lectus auctor viverra. Vehicula nibh vel ante commodo feugiat. Nulla ut enim lobortis orci gravida volutpat.</p>
            <a href="#" class="label label-default">Read More</a>
          </div>
        </div>
      </div>
    </div>
    <div class="middle-text">
        <h1>360factors specializes in Governance, Risk, and Compliance (GRC) solutions which enable organizations to support their GRC functions, streamline their GRC processes, and report to stakeholders</h1>
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
        </div>
    </div>
</div>
