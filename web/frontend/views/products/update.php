<?php

/** @var yii\web\View $this */

$this->title = 'Update Products: ' . $model->vNama;
$this->params['breadcrumbs'][] = ['label' => 'Master Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
$this->params['breadcrumbs'][] = $model->vNama;
?>
<div class="registrasi-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>