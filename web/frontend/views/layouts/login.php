<?php

/** @var \yii\web\View $this */
/** @var string $content */

use frontend\assets\AppAsset;
use yii\bootstrap5\Html;

AppAsset::register($this);
// Bootstrap5Asset::register($this);
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
<script>
    var pathON = {
        base: "<?= Yii::$app->request->BaseUrl; ?>",
        createUrl: function(path = null, params = null) {
            if (path == null) {
                return this.base
            }
            var url = this.base + path;
            if (typeof params === 'object' && params !== null) {
                if (Object.keys(params).length > 0) {
                    url += '?'
                    $.each(params, function(key, value) {
                        url += `${key}=${encodeUrl(value)}&`;
                    });
                }
            }
            return trims(url, '&');
        }
    }
</script>

<body>
    <?php $this->beginBody() ?>
    <?= $content ?>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage();
