<?php

use yii\helpers\Html;
use frontend\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
list(, $url) = Yii::$app->assetManager->publish('@mdm/admin/assets');
$this->registerCssFile($url . '/main.css');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="<?= Yii::getAlias('@web') ?>/images/logo.png" type="image/png" />
</head>

<body>
    <?php $this->beginBody() ?>
    <nav class="navbar navbar-light">
        <div class="container d-block">
            <?= Html::a('<i class="bi bi-chevron-left"></i>', ['/']); ?>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 bg-white vh-40">
                <h5 class="mt-3">Admin Menu</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><?= Html::a('Role', ['/admin/role'], ['class' => 'nav-link text-primary']) ?></li>
                    <li class="nav-item"><?= Html::a('Permission', ['/admin/permission'], ['class' => 'nav-link text-primary']) ?></li>
                    <li class="nav-item"><?= Html::a('Assignment', ['/admin/assignment'], ['class' => 'nav-link text-primary']) ?></li>
                    <li class="nav-item"><?= Html::a('Route', ['/admin/route'], ['class' => 'nav-link text-primary']) ?></li>
                    <li class="nav-item"><?= Html::a('Menu', ['/admin/menu'], ['class' => 'nav-link text-primary']) ?></li>
                </ul>
            </div>

            <!-- Content -->
            <div class="col-md-10">
                <?= $content ?>
            </div>
        </div>
    </div>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>