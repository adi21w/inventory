<?php

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use common\services\PacksService;

class PackController extends ActiveController
{
    public $modelClass = 'common\models\Packs';
    protected $modelService;

    public function actions()
    {
        $actions = parent::actions();
        // Matikan aksi delete bawaan agar kita bisa buat kustom
        unset($actions['delete']);
        unset($actions['index']);
        unset($actions['update']);
        unset($actions['create']);
        return $actions;
    }

    public function init()
    {
        parent::init();
        // Inisialisasi Service
        $this->modelService = new PacksService;
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

    public function actionCreate()
    {
        $mapping = $this->modelService->mapQueryParams(Yii::$app->request->getBodyParams());
        $out = $this->modelService->createData($mapping);
        return $out;
    }

    public function actionUpdate($id)
    {
        if (Yii::$app->request->isPut) {
            throw new \yii\web\MethodNotAllowedHttpException("Metode PUT not allowed, use PATCH to action.");
        }

        $mapping = $this->modelService->mapQueryParams(Yii::$app->request->getBodyParams());
        $out = $this->modelService->updateData($mapping, $id);
        return $out;
    }


    public function actionDelete($id)
    {
        $out = $this->modelService->deleteData($id);

        return $out;
    }
}
