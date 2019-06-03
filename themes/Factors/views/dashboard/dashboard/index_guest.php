<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?= \humhub\modules\dashboard\widgets\DashboardContent::widget(); ?>
        </div>
        <div class="col-md-4 layout-sidebar-container">
            <?php
            echo \humhub\modules\dashboard\widgets\Sidebar::widget([
                'widgets' => [
                    [
                        \humhub\modules\custom\widgets\directory\NewMembers::className(),
                        ['showMoreButton' => true],
                        ['sortOrder' => 300]
                    ]/*,
                    [
                        \humhub\modules\directory\widgets\NewSpaces::className(),
                        ['showMoreButton' => true],
                        ['sortOrder' => 400]
                    ],*/
                ]
            ]);
            ?>
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
