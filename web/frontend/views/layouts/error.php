<?php

/** @var \yii\web\View $this */
/** @var string $content */

use frontend\assets\ErrorAsset;
use yii\bootstrap5\Html;

ErrorAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="<?= Yii::getAlias('@web') ?>/images/logo.png" type="image/png" />
</head>

<body>
    <?php $this->beginBody() ?>
    <div id="error">
        <?= $content ?>
    </div>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage();
