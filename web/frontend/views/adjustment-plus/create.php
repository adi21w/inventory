<?php

// use yii\helpers\Html;

$this->title = 'Create - Adjustment Plus';
$this->params['breadcrumbs'][] = ['label' => 'Adjustment Plus', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Create';
?>
<div class="or-create">
    <?= $this->render('_form', [
        'model' => $model,
        'modelDetails' => $modelDetails,
    ]) ?>
</div>