<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Menu */

$this->title = Yii::t('rbac-admin', 'Menus');
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .ui-autocomplete {
        position: absolute;
        top: 24em !important;
        left: 19em !important;
        cursor: default;
    }
</style>
<div class="menu-index card mt-5 p-2">
    <div class="card-header">
        <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        <?= Html::a(Yii::t('rbac-admin', 'Create Menu'), ['create'], ['class' => 'btn btn-primary']) ?>
    </div>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-hover'], // ⬅️ tambahkan ini
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'menuParent.name',
                'filter' => Html::activeTextInput($searchModel, 'parent_name', [
                    'class' => 'form-control',
                    'id' => null
                ]),
                'label' => Yii::t('rbac-admin', 'Parent'),
            ],
            'route',
            'order',
            'level',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>

</div>