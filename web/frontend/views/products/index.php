<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use mdm\admin\components\Helper;
use yii\web\View;
use common\helper\Helper as Help;
// use common\widgets\Alert;
// use kartik\select2\Select2;
// use yii\web\JsExpression;
// use yii\widgets\ActiveForm;
// use yii\helpers\Url;
// use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var bdg\models\PoHdSearch $searchModel
 */

$this->title = 'Master Products';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile("@web/js/template-s2.js?v" . date('Ymd'), ['position' => View::POS_HEAD]);
$this->registerJsFile("@web/js/apps/component/base.js?v" . date('Ymd'), ['position' => View::POS_HEAD]);
?>
<div class="card">
    <div class="card-body">
        <?php Pjax::begin(['id' => 'tableGrid0', 'timeout' => 5000, 'enablePushState' => false, 'options' => ['data-model' => 'products', 'data-home' => '/products']]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'headerOptions' => ['style' => 'max-width: 15%;'],
                    'attribute' => 'vNama'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'label' => 'Rak',
                    'attribute' => 'iRak',
                    'value' => 'rak.vNama'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'iStatus',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => [
                        '0' => 'Disabled',
                        '1' => 'Active'
                    ],
                    'filterWidgetOptions' => [
                        //'size' => Select2::MEDIUM,
                        'options' => ['placeholder' => 'Status...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ],
                    'value' => function ($model) {
                        return Html::tag('span', $model->iStatus == 1 ? 'Active' : 'Disabled', ['class' => $model->iStatus == 1 ? 'badge bg-success' : 'badge bg-secondary']);
                    },
                    'format' => 'raw',
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'label' => 'Kemasan',
                    'attribute' => 'iKemasanBesarId',
                    'value' => function ($model) {
                        return $model->kemasan?->vNama . ' (' . $model->iIsiKemasanBesar . ')';
                    }
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'label' => 'Kemasan Kecil',
                    'attribute' => 'iKemasanKecilId',
                    'value' => function ($model) {
                        return $model->kemasankecil?->vNama . ' (' . $model->iIsiKemasanKecil . ')';
                    }
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'dPrice',
                    'label' => 'Harga',
                    'format' => ['decimal', 2],
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'dMargin',
                    'label' => 'Harga Jual + PPN',
                    'value' => function ($model) {
                        $hargaJual = Help::totalHargaJual($model->dPrice, $model->dMargin);
                        return $hargaJual;
                    },
                    'format' => ['decimal', 2],
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'iCreatedId',
                    'value' => 'created.name'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'iUpdatedId',
                    'value' => 'updated.name'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 14%;'],
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
                    'headerOptions' => ['style' => 'max-width: 10%; width:10%'],
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '<div class="action-wrapper">' . Helper::filterActionColumn('{update} {status} {view} {delete}') . '</div>',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            $icon = '<i class="fas fa-edit"></i>';
                            return Html::a($icon, $url, ['class' => 'btn btn-primary btn-sm', 'data-pjax' => 0]);
                        },
                        'status' => function ($url, $model) {
                            if ($model->iStatus == 1) {
                                $icon = '<i class="fas fa-ban"></i>';
                                return Html::button($icon, ['class' => 'btn btn-warning btn-sm btn-status-grid', 'data-id' => $model->iId, 'data-active' => '0', 'title' => 'Status']);
                            } else {
                                $icon = '<i class="fas fa-sync-alt"></i>';
                                return Html::button($icon, ['class' => 'btn btn-purple btn-sm btn-status-grid', 'data-id' => $model->iId, 'data-active' => '1', 'title' => 'Status']);
                            }
                        },
                        'view' => function ($url, $model) {
                            $icon = '<i class="fas fa-eye"></i>';
                            return Html::button($icon, ['class' => 'btn btn-info btn-sm btn-view-grid', 'data-id' => $model->iId]);
                        },
                        'delete' => function ($url, $model) {
                            $icon = '<i class="fas fa-trash-alt"></i>';
                            return Html::button($icon, ['class' => 'btn btn-danger btn-sm btn-delete-grid', 'data-id' => $model->iId]);
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
                    'content' => (Helper::checkRoute('/products/create')) ? Html::a('<i class="fas fa-plus"></i>', ['create'], ['class' => 'btn btn-blue', 'title' => Yii::t('kvgrid', 'Add Data'), 'data-pjax' => 0]) : null
                ],
                [
                    'content' => (Helper::checkRoute('/products/history-price')) ? Html::a('<i class="fas fa-money-check-alt"></i>', ['/products/history-price'], ['class' => 'btn btn-info', 'title' => Yii::t('kvgrid', 'History Price'), 'data-pjax' => 0]) : null
                ],
                [
                    'content' => Html::a('<i class="fas fa-redo-alt"></i>', ['/products'], ['class' => 'btn btn-warning', 'title' => Yii::t('kvgrid', 'Reset Grid')])
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
                'headingOptions' => ['class' => 'bg-primary-g header-panel text-white'],
            ],
            'persistResize' => false,
            'toggleDataOptions' => ['minCount' => 10],
            'exportConfig' => 'excel',
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>