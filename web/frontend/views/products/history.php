<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
// use mdm\admin\components\Helper;
// use kartik\select2\Select2;
// use yii\web\JsExpression;
// use yii\bootstrap5\ActiveForm;
// use yii\helpers\Url;
// use yii\helpers\ArrayHelper;
// use yii\web\View;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'History Price';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body" data-route-parent='/products/index'>
        <?php Pjax::begin(['id' => 'produkGrid0']); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'headerOptions' => ['style' => 'max-width: 20%;'],
                    'attribute' => 'iProdukId',
                    'value' => 'product.vNama'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 10%;'],
                    'attribute' => 'oldPrice',
                    'format' => ['decimal', 2]
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 10%;'],
                    'attribute' => 'newPrice',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $change = $model->newPrice - $model->oldPrice;
                        if ($change > 0) {
                            return Html::tag('span', Yii::$app->formatter->asDecimal($model->newPrice, 2) . ' ▲', ['class' => 'text-success']);
                        } elseif ($change < 0) {
                            return Html::tag('span', Yii::$app->formatter->asDecimal($model->newPrice, 2) . ' ▼', ['class' => 'text-danger']);
                        } else {
                            return Yii::$app->formatter->asDecimal($model->newPrice, 2);
                        }
                    },
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 10%;'],
                    'attribute' => 'tUpdated'
                ],
                [
                    'headerOptions' => ['style' => 'max-width: 5%;'],
                    'attribute' => 'iUpdatedid',
                    'value' => 'updated.name'
                ],
            ],
            'containerOptions' => ['style' => 'overflow: auto'],
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true,
            'toolbar' =>  [
                [
                    'content' => Html::a('<i class="fas fa-redo-alt"></i>', ['/products/history-price'], ['class' => 'btn btn-warning', 'title' => Yii::t('kvgrid', 'Reset Grid')])
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