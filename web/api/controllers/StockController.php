<?php

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use common\services\StockService;

class StockController extends ActiveController
{
    public $modelClass = 'common\models\Stock';
    protected $modelService;

    public function actions()
    {
        $actions = parent::actions();
        // Matikan aksi delete bawaan agar kita bisa buat kustom
        unset($actions['delete']);
        unset($actions['index']);
        unset($actions['update']);
        unset($actions['create']);
        unset($actions['view']);
        return $actions;
    }

    public function init()
    {
        parent::init();
        // Inisialisasi Service
        $this->modelService = new StockService;
    }

    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $mappedParams = $this->modelService->mapQueryParams($params, true);

        $searchModel = $this->modelService->createSearch();
        $dataProvider = $searchModel->search($mappedParams);

        // Panggil service untuk mendapatkan data provider
        return $dataProvider;
    }
}
