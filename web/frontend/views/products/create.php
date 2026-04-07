<?php

/** @var yii\web\View $this */

$this->title = 'Add Products';
$this->params['breadcrumbs'][] = ['label' => 'Master Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registrasi-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>