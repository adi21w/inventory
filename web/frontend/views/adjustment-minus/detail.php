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

$this->title = 'Adjustment Plus Detail';
$this->params['breadcrumbs'][] = ['label' => 'Adjustment Plus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card mb-4" data-route-parent="/adjustment-plus">
    <div class="card-body">
        <?php Pjax::begin(['id' => 'gridGrid0']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'headerOptions' => ['style' => 'max-width: 7%;'],
                    'attribute' => 'vAdjNo'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 10%;'],
                    'attribute' => 'iProdukId',
                    'value' => 'product.vNama'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'iKemasanId',
                    'value' => 'pack.vNama'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'gudang',
                    'label' => 'Warehouse',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(Warehouses::find()->where(['eDeleted' => 'Tidak'])->all(), 'iId', 'vNama'),
                    'filterWidgetOptions' => [
                        //'size' => Select2::MEDIUM,
                        'options' => ['placeholder' => 'Warehouse..'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ],
                    'value' => 'header.warehouse.vNama'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 7%;'],
                    'attribute' => 'date',
                    'label' => 'Date',
                    'options' => [
                        'format' => 'YYYY-MM-DD',
                    ],
                    'filterType' => GridView::FILTER_DATE_RANGE,
                    'filterWidgetOptions' => ([
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
                    ]),
                    'value' => 'header.dDate'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'status',
                    'label' => 'Confirm',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => [
                        'Ya' => 'Ya',
                        'Tidak' => 'Tidak'
                    ],
                    'filterWidgetOptions' => [
                        //'size' => Select2::MEDIUM,
                        'options' => ['placeholder' => 'Status..'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ],
                    'value' => 'header.eStatus'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'kategori',
                    'label' => 'Catgeory',
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
                    ],
                    'value' => 'header.eKategori'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 3%;', 'class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'attribute' => 'iQty',
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 7%;'],
                    'attribute' => 'dHarga',
                    'format' => ['decimal', 2],
                    'pageSummary' => true,
                    'pageSummaryFunc' => GridView::F_SUM,
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 7%;'],
                    'attribute' => 'dTotal',
                    'format' => ['decimal', 2],
                    'pageSummary' => true,
                    'pageSummaryFunc' => GridView::F_SUM,
                ],
            ],
            'showPageSummary' => true,
            'containerOptions' => ['style' => 'overflow: auto'],
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true,
            'toolbar' =>  [
                [
                    'content' => Html::a('<i class="fas fa-redo-alt"></i>', ['detail'], ['class' => 'btn btn-warning', 'title' => Yii::t('kvgrid', 'Reset Grid')])
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