<?php

// use yii\helpers\Html;

$this->title = 'Create - Adjustment Minus';
$this->params['breadcrumbs'][] = ['label' => 'Adjustment Minus', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Create';
?>
<div class="or-create">
    <?= $this->render('_form', [
        'model' => $model,
        'modelDetails' => $modelDetails,
    ]) ?>
</div>