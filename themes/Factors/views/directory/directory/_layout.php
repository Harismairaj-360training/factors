<?php

//use humhub\modules\directory\widgets\Menu;
use humhub\modules\directory\widgets\Sidebar;

\humhub\assets\JqueryKnobAsset::register($this);
?>

<div class="container">
    <div class="row">
        <?php /*<div class="col-md-2">
            <?= Menu::widget(); ?>
        </div> */?>
        <div class="col-md-7">
            <?= $content; ?>
        </div>
        <div class="col-md-3">
            <?= Sidebar::widget(); ?>
        </div>
    </div>
</div>
