<?php
/**
 * @var $content string
 */

use app\widgets\Alert;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        if (!Yii::$app->user->isGuest) {
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    ['label' => '个人中心', 'url' => ['dashboard/index']],
                    ['label' => 'CDKEY兑换', 'url' => ['cdkey/records']],
                    ['label' => '消费流水', 'url' => ['costlog/history']]
                ],
            ]);
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => Yii::$app->user->isGuest ? [
                ['label' => '登录', 'url' => ['user/signin']],
                ['label' => '注册', 'url' => ['user/signup']],
            ] : [
                ['label' => '修改密码', 'url' => ['dashboard/change-password']],
                ['label' => '退出登录', 'url' => ['dashboard/logout']]
            ],
        ]);
        NavBar::end();
        ?>

        <div class="container" style="margin-top: 60px;">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>