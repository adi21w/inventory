<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\services\ProductsService;

/**
 * Products controller
 */
class ProductsController extends Controller
{
    protected $modelService;

    public function init()
    {
        parent::init();

        $this->modelService = new ProductsService;
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'common\helper\ErrorAction',
                // 'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = $this->modelService->createModel();
        $searchModel = $this->modelService->createSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model
            ]);
        } else {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model
            ]);
        }
    }

    public function actionCreate()
    {
        $model = $this->modelService->createModel();

        if (Yii::$app->request->post()) {
            $out = $this->modelService->createData(Yii::$app->request->post());

            Yii::$app->getSession()->setFlash('alert', [
                'icon' => $out['status'] ? 'success' : 'error',
                'title' => $out['status'] ? 'Success' : 'Failed',
                'text' => $out['message'] ?? $out['error'],
            ]);

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->modelService->findModel($id);

        if (Yii::$app->request->post()) {
            $out = $this->modelService->updateData(Yii::$app->request->post(), $id);

            Yii::$app->getSession()->setFlash('alert', [
                'icon' => $out['status'] ? 'success' : 'error',
                'title' => $out['status'] ? 'Success' : 'Failed',
                'text' => $out['message'] ?? $out['error'],
            ]);

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionGetView($id, $template = 0)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = $out = Yii::$app->params['baseRes'];
        if (Yii::$app->request->isAjax) {
            $out = $this->modelService->detailData($id);
            if ($out['status']) {
                $out['data'] = match ((int)$template) {
                    1 => $this->modelService->packDataView($out['data']),
                    default => $this->modelService->parseDataView($out['data']),
                };
            }
        }

        return $out;
    }

    public function actionGetList($q = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = $out = Yii::$app->params['baseRes'];
        if (Yii::$app->request->isAjax) {
            $out = $this->modelService->searchList($q);
        }

        return $out;
    }

    public function actionDelete($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = $out = Yii::$app->params['baseRes'];
        if (Yii::$app->request->isAjax) {
            $out = $this->modelService->deleteData($id);
        }

        return $out;
    }

    public function actionStatus($id, $status)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = $out = Yii::$app->params['baseRes'];
        if (Yii::$app->request->isAjax) {
            $out = $this->modelService->statusData($id, $status);
        }

        return $out;
    }

    public function actionHistoryPrice()
    {
        $searchModel = $this->modelService->historySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('history', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}
