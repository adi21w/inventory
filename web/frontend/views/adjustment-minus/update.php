<?php

use yii\helpers\Html;

$this->title = 'Update - Adjustment Minus';
$this->params['breadcrumbs'][] = ['label' => 'Adjustment Minus', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
$this->params['breadcrumbs'][] = $model->vAdjNo;
?>
<div class="or-create">
    <?= $this->render('_form', [
        'model' => $model,
        'modelDetails' => $modelDetails,
    ]) ?>
</div>

<script>
    var onUpdate = true;
</script>