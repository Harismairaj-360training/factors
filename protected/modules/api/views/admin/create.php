<?php

use yii\helpers\Html;

?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo '<strong>Add</strong> Api User'; ?></div>
    <div class="panel-body">
        <?= \humhub\modules\admin\widgets\UserMenu::widget(); ?>
        <p />

        <?php $form = \yii\widgets\ActiveForm::begin(); ?>
        <?php echo $hForm->render($form); ?>
        <?php \yii\widgets\ActiveForm::end(); ?>

    </div>
</div>