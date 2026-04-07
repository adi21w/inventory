<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mdm\admin\models\Menu;
use yii\helpers\Json;
use mdm\admin\AutocompleteAsset;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\Menu */
/* @var $form yii\widgets\ActiveForm */

AutocompleteAsset::register($this);
$opts = Json::htmlEncode([
    'menus' => Menu::getMenuSource(),
    'routes' => Menu::getSavedRoutes(),
]);
$this->registerJs("var _opts = $opts;");
$this->registerJs($this->render('_script.js'));
?>

<div class="menu-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= Html::activeHiddenInput($model, 'parent', ['id' => 'parent_id']); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 128]) ?>
            <?= $form->field($model, 'parent')->widget(Select2::classname(), [
                'bsVersion' => '5.x', // 🛠️ Ini WAJIB!
                'data' => ArrayHelper::map(Menu::find()->where(['eparent' => 'Ya'])->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Pilih parent'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
            <?= $form->field($model, 'route')->textInput(['id' => 'route']) ?>
            <?= $form->field($model, 'icon')->textInput(['id' => 'icon']) ?>
            <?= $form->field($model, 'eparent')->dropDownList(
                ['Tidak' => 'Tidak', 'Ya' => 'Ya']
            ); ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'order')->input('number') ?>
            <?= $form->field($model, 'level')->input('number') ?>
            <?= $form->field($model, 'data')->textarea(['rows' => 4]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t('rbac-admin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>