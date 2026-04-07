<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use mdm\admin\components\Helper;
// use kartik\select2\Select2;
// use yii\web\JsExpression;
// use yii\bootstrap5\ActiveForm;
// use yii\web\View;
// use yii\helpers\Url;
// use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var bdg\models\PoHdSearch $searchModel
 */

$this->title = 'Master Users';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile("@web/js/apps/component/base.js?v" . date('Ymd'), ['depends' => [\yii\web\YiiAsset::class]]);
$this->registerJsFile("@web/js/apps/component/password.js?v" . date('Ymd'), ['depends' => [\yii\web\YiiAsset::class]]);
?>
<div class="card">
    <div class="card-body">
        <?php Pjax::begin(['id' => 'tableGrid0', 'timeout' => 5000, 'enablePushState' => false, 'options' => ['data-model' => 'users', 'data-home' => '/user']]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'headerOptions' => ['style' => 'max-width: 10%;'],
                    'attribute' => 'username'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 10%;'],
                    'attribute' => 'name'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 10%;'],
                    'attribute' => 'email'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 10%;'],
                    'attribute' => 'role',
                    'value' => 'role.item_name'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 10%;'],
                    'attribute' => 'number_phone'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 10%;'],
                    'contentOptions' => ['class' => 'text-center'],
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '<div class="action-wrapper">' . Helper::filterActionColumn('{update} {password} {status}') . '</div>',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            $icon = '<i class="fas fa-edit"></i>';
                            return Html::button($icon, ['class' => 'btn btn-success btn-sm btn-update-grid', 'data-id' => $model->id]);
                        },
                        'password' => function ($url, $model) {
                            $icon = '<i class="fas fa-key"></i>';
                            return Html::button($icon, ['class' => 'btn btn-info btn-sm btn-password-grid', 'data-id' => $model->id, 'data-name' => $model->name]);
                        },
                        'status' => function ($url, $model) {
                            if ($model->status == 10) {
                                $icon = '<i class="fas fa-ban"></i>';
                                return Html::button($icon, ['class' => 'btn btn-warning btn-sm btn-status-grid', 'data-id' => $model->id, 'data-active' => '2', 'title' => 'Status']);
                            } else {
                                $icon = '<i class="fas fa-sync-alt"></i>';
                                return Html::button($icon, ['class' => 'btn btn-purple btn-sm btn-status-grid', 'data-id' => $model->id, 'data-active' => '10', 'title' => 'Status']);
                            }
                        }

                    ],
                ],
            ],
            'containerOptions' => ['style' => 'overflow: auto'],
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true,
            'toolbar' =>  [
                [
                    'content' => Html::button('<i class="fas fa-plus"></i>', ['class' => 'btn btn-primary', 'title' => Yii::t('kvgrid', 'Tambah Data'), 'id' => 'btn-add-grid'])
                ],
                [
                    'content' => Html::a('<i class="fas fa-redo-alt"></i>', ['/user'], ['class' => 'btn btn-warning', 'title' => Yii::t('kvgrid', 'Reset Grid')])
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
                'prevPageLabel' => '<i class="fas fa-angle-left"></i>',
                'nextPageLabel' => '<i class="fas fa-angle-right"></i>',
                'firstPageLabel' => '<i class="fas fa-angle-double-left"></i>',
                'lastPageLabel' => '<i class="fas fa-angle-double-right"></i>',
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

<?= $this->render('_form', [
    'model' => $model
]);
