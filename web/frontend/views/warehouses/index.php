<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use mdm\admin\components\Helper;
// use yii\bootstrap5\ActiveForm;
// use kartik\select2\Select2;
// use yii\web\JsExpression;
// use yii\web\View;
// use yii\helpers\Url;
// use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Master Warehouses';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile("@web/js/apps/component/base.js?v" . date('Ymd'), ['depends' => [\yii\web\YiiAsset::class]]);
?>
<div class="card">
    <div class="card-body">
        <?php Pjax::begin(['id' => 'tableGrid0', 'timeout' => 5000, 'enablePushState' => false, 'options' => ['data-model' => 'warehouses', 'data-home' => '/warehouses']]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'vNama',
                [
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
                    'attribute' => 'eStock',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => [
                        'Ya' => 'Ya',
                        'Tidak' => 'Tidak'
                    ],
                    'filterWidgetOptions' => [
                        //'size' => Select2::MEDIUM,
                        'options' => ['placeholder' => 'Stock...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]
                ],
                [
                    'attribute' => 'iCreatedId',
                    'value' => 'created.name'
                ],
                [
                    'attribute' => 'iUpdatedId',
                    'value' => 'updated.name'
                ],
                [
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
                    'contentOptions' => ['class' => 'text-center'],
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '<div class="action-wrapper">' . Helper::filterActionColumn('{update} {status} {view} {delete}') . '</div>',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            $icon = '<i class="fas fa-edit"></i>';
                            return Html::button($icon, ['class' => 'btn btn-primary btn-sm btn-update-grid', 'data-id' => $model->iId]);
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
                    'content' => (Helper::checkRoute('/packs/create')) ? Html::button('<i class="fas fa-plus"></i>', ['class' => 'btn btn-blue', 'title' => Yii::t('kvgrid', 'Add Data'), 'id' => 'btn-add-grid']) : null
                ],
                [
                    'content' => Html::a('<i class="fas fa-redo-alt"></i>', ['/warehouses'], ['class' => 'btn btn-warning', 'title' => Yii::t('kvgrid', 'Reset Grid')])
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
                'headingOptions' => ['class' => 'bg-primary-g header-panel text-white'], // ⬅️ Custom CSS di sini
            ],
            'persistResize' => false,
            'toggleDataOptions' => ['minCount' => 10],
            'exportConfig' => 'excel',
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>

<?= $this->render('_form', [
    'model' => $model
]);
?>