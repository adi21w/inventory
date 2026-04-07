<?php

/** @var \yii\web\View $this */
/** @var string $content */

use frontend\assets\AppAsset;
use yii\bootstrap5\Html;
// use common\widgets\Alert;
// use frontend\assets\Bootstrap5Asset;
// use yii\bootstrap5\Breadcrumbs;
// use yii\bootstrap5\Nav;
// use yii\bootstrap5\NavBar;

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
    const basePATH = "<?= Yii::$app->request->BaseUrl; ?>";
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
            } else if (typeof params === 'string' && params !== null) {
                url += '?'
                url += params
            }
            return trims(url, '&');
        }
    }
</script>

<body class="">
    <?php $this->beginBody() ?>
    <div id="app">
        <div class="active" id="sidebar">
            <?= $this->render('_left-menu') ?>
        </div>
        <div class="layout-navbar" id="main">
            <?= $this->render('_navbar') ?>
            <div id="main-content">
                <div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3><?= $this->title; ?></h3>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                    <ol class="breadcrumb">
                                        <?= \yii\widgets\Breadcrumbs::widget([
                                            'homeLink' => ['label' => 'Dashboard', 'url' => Yii::$app->homeUrl],
                                            'links' => $this->params['breadcrumbs'] ?? [],
                                            'options' => ['class' => 'breadcrumb'], // Bootstrap 5 class
                                            'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n",
                                            'activeItemTemplate' => "<li class=\"breadcrumb-item active\" aria-current=\"page\">{link}</li>\n",
                                        ]) ?>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <section class="section">
                        <?= $content ?>
                        <div class="row">
                            <div id="view-page-grid" class="card d-none opacity-0 transition-opacity">
                                <div class="card-header d-flex align-items-center justify-content-between" id="view-title-grid">
                                    <h5 class="mb-0">Detail View</h5>
                                    <small class="text-muted float-end"></small>
                                </div>
                                <div class="card-body" id="view-content-grid">
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <footer class="bg-primary footer-base p-1">
                <div class="d-flex justify-content-center align-items-center gap-3 text-center text-white">
                    <p>&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
                    <p><?= Yii::powered() ?></p>
                </div>
            </footer>
        </div>
    </div>
    <?php $this->endBody() ?>
    <script>
        <?php
        if (Yii::$app->session->hasFlash('alert')) {
            $flash = Yii::$app->session->getFlash('alert');
            $ejson = json_encode($flash);
            echo 'eval(Swal.fire(' . $ejson . '))';
        }
        ?>
    </script>
</body>

</html>
<?php $this->endPage();
