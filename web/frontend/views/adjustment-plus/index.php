<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use mdm\admin\components\Helper;
use common\models\Warehouses;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\View;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Adjustment Plus';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile("@web/js/apps/component/base.js?v" . date('Ymd'), ['depends' => [\yii\web\YiiAsset::class]]);
?>
<div class="card mb-4">
    <div class="card-body">
        <?php Pjax::begin(['id' => 'tableGrid0', 'timeout' => 5000, 'enablePushState' => false, 'options' => ['data-model' => 'tadjustmenthd', 'data-home' => '/adjustment-plus']]); ?>
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
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'vAdjNo'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 7%;'],
                    'attribute' => 'dConfirm',
                    'options' => [
                        'format' => 'YYYY-MM-DD',
                    ],
                    'filterType' => GridView::FILTER_DATE_RANGE,
                    'filterWidgetOptions' => ([
                        'attribute' => 'dDate',
                        'presetDropdown' => false,
                        'convertFormat' => false,
                        'pluginOptions' => [
                            'separator' => ' - ',
                            'format' => 'YYYY-MM-DD',
                            'locale' => [
                                'format' => 'YYYY-MM-DD'
                            ],
                        ],
                        'pluginEvents' => [
                            "apply.daterangepicker" => "function() { apply_filter('only_date') }",
                        ],
                    ])
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'iGudangId',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(Warehouses::find()->where(['eDeleted' => 'Tidak'])->all(), 'iId', 'vNama'),
                    'filterWidgetOptions' => [
                        //'size' => Select2::MEDIUM,
                        'options' => ['placeholder' => 'Warehouse..'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ],
                    'value' => 'warehouse.vNama'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'eConfirm',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => [
                        'Ya' => 'Ya',
                        'Tidak' => 'Tidak'
                    ],
                    'filterWidgetOptions' => [
                        //'size' => Select2::MEDIUM,
                        'options' => ['placeholder' => 'Confirmed..'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'eKategori',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => [
                        'STOCK OPNAME' => 'STOCK OPNAME',
                        'DITEMUKAN' => 'DITEMUKAN',
                        'PEMBETULAN BATCH' => 'PEMBETULAN BATCH',
                        'SALDO AWAL' => 'SALDO AWAL',
                    ],
                    'filterWidgetOptions' => [
                        //'size' => Select2::MEDIUM,
                        'options' => ['placeholder' => 'Category..'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]
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
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'contentOptions' => ['class' => 'text-center'],
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '<div class="action-wrapper">' . Helper::filterActionColumn('{update} {view} {pdf} {delete}') . '</div>',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            if ($model->eConfirm != 'Ya') {
                                $icon = '<i class="fas fa-edit"></i>';
                                return Html::a($icon, $url, ['class' => 'btn btn-success btn-sm']);
                            }
                            return null;
                        },
                        'view' => function ($url, $model) {
                            $icon = '<i class="fas fa-eye"></i>';
                            return Html::button($icon, ['class' => 'btn btn-info btn-sm btn-view-grid', 'data-id' => $model->iId]);
                        },
                        'pdf' => function ($url, $model) {
                            if ($model->eConfirm == 'Ya') {
                                $icon = '<i class="fas fa-file-pdf"></i>';
                                return Html::a($icon, ['print', 'id' => $model->iId], ['class' => 'btn btn-danger btn-sm', 'data-pjax' => 0, 'target' => '_blank']);
                            }
                        },
                        'delete' => function ($url, $model) {
                            if ($model->eConfirm != 'Ya') {
                                $icon = '<i class="fas fa-trash-alt"></i>';
                                return Html::button($icon, ['class' => 'btn btn-danger btn-sm btn-delete-grid', 'data-id' => $model->iId]);
                            }
                            return null;
                        },
                    ],
                ],
            ],
            'containerOptions' => ['style' => 'overflow: auto'],
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true,
            'toolbar' =>  [
                [
                    'content' => Html::a('<i class="fas fa-plus"></i>', ['create'], ['class' => 'btn btn-primary', 'title' => Yii::t('kvgrid', 'Tambah Data'), 'id' => 'btn-add-grid'])
                ],
                [
                    'content' => Html::a('<i class="fas fa-file-alt"></i>', ['detail'], ['class' => 'btn btn-danger', 'title' => Yii::t('kvgrid', 'Report Detail'), 'data-pjax' => 0])
                ],
                [
                    'content' => Html::a('<i class="fas fa-redo-alt"></i>', ['/adjustment-plus'], ['class' => 'btn btn-warning', 'title' => Yii::t('kvgrid', 'Reset Grid')])
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