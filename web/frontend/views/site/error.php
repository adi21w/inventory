<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;

$this->title = $name;
// var_dump($exception);
?>
<div class="error-page container">
    <div class="col-md-8 col-12 offset-md-2">
        <?php if ($exception->statusCode == 404) { ?>
            <?= Html::img('@web/images/error-404.webp', ['alt' => 'Not Found', 'class' => 'img-error']); ?>
            <div class="text-center">
                <h1 class="error-title">NOT FOUND</h1>
                <p class='fs-5 text-gray-600'>The page you are looking not found.</p>
                <?= Html::a('Go Home', ['/'], ['class' => 'btn btn-lg btn-outline-primary mt-3']) ?>
            </div>
        <?php } else if ($exception->statusCode == 403) { ?>
            <?= Html::img('@web/images/error-403.webp', ['alt' => 'Not Found', 'class' => 'img-error']); ?>
            <div class="text-center">
                <h1 class="error-title">Forbidden</h1>
                <p class="fs-5 text-gray-600"><?= $message; ?></p>
                <?= Html::a('Go Home', ['/'], ['class' => 'btn btn-lg btn-outline-primary mt-3']) ?>
            </div>
        <?php } else { ?>
            <?= Html::img('@web/images/error-500.webp', ['alt' => 'Not Found', 'class' => 'img-error']); ?>
            <div class="text-center">
                <h1 class="error-title">System Error</h1>
                <p class="fs-5 text-gray-600"><?= $message; ?></p>
                <?= Html::a('Go Home', ['/'], ['class' => 'btn btn-lg btn-outline-primary mt-3']) ?>
            </div>
        <?php } ?>
    </div>
</div>