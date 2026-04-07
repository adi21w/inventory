<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use common\models\Warehouses;
// use mdm\admin\components\Helper;
// use kartik\select2\Select2;
// use yii\web\View;
// use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Stock';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card mb-4">
    <div class="card-body">
        <?php Pjax::begin(['id' => 'gridGrid0']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'value' => function ($model, $key, $index, $column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detail' => function ($model, $key, $index, $column) {
                        $data = $model->expands;
                        return Yii::$app->controller->renderPartial('_expandView', [
                            'data'   => $data
                        ]);
                    }
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 15%;'],
                    'attribute' => 'iProdukId',
                    'value' => 'product.vNama'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 10%;'],
                    'attribute' => 'iGudangId',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(Warehouses::find()->where(['eDeleted' => 'Tidak'])->all(), 'iId', 'vNama'),
                    'filterWidgetOptions' => [
                        //'size' => Select2::MEDIUM,
                        'options' => ['placeholder' => 'Warehouse'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ],
                    'value' => 'warehouse.vNama'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'iQty',
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 10%;'],
                    'attribute' => 'iUpdatedId',
                    'value' => 'updated.name'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 10%;'],
                    'attribute' => 'tCreated',
                    'label' => 'Times At',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $created = Html::tag('span', "Created: $model->tCreated");
                        $updated = Html::tag('span', "Updated: $model->tUpdated");
                        return "{$created}<br>{$updated}";
                    }
                ],
            ],
            'containerOptions' => ['style' => 'overflow: auto'],
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true,
            'toolbar' =>  [
                [
                    'content' => Html::a('<i class="fas fa-history me-1"></i> Transaksi', ['/trhist'], ['class' => 'btn btn-primary', 'title' => Yii::t('kvgrid', 'Assignment Asset')])
                ],
                [
                    'content' => Html::a('<i class="fas fa-redo-alt"></i>', ['/stock'], ['class' => 'btn btn-warning', 'title' => Yii::t('kvgrid', 'Reset Grid')])
                ],
                '{export}',
                '{toggleData}',
            ],
            'options' => ['class' => 'table'], // buang "table-striped"
            'export' => [
                'fontAwesome' => true,
                'icon' => false, // Aktifin icon
                'label' => 'Export',
                'iconOptions' => ['class' => 'text-info'],
            ],
            'bordered' => true,
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'pager' => [
                'options' => ['class' => 'pagination'],
                'prevPageLabel' => '<i class="bi bi-arrow-left-circle"></i>',
                'nextPageLabel' => '<i class="bi bi-arrow-right-circle"></i>',
                'firstPageLabel' => '<i class="bi bi-arrow-left-circle-fill"></i>',
                'lastPageLabel' => '<i class="bi bi-arrow-right-circle-fill"></i>',
                'maxButtonCount' => 10,
            ],
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                // 'heading' => 'list-produk',
                'headingOptions' => ['class' => 'bg-header-table header-panel text-white'], // ⬅️ Custom CSS di sini
            ],
            'persistResize' => false,
            'toggleDataOptions' => ['minCount' => 10],
            'exportConfig' => 'excel',
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>