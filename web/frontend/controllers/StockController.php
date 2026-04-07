<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\services\StockService;

/**
 * Stock controller
 */
class StockController extends Controller
{
    protected $modelService;

    public function init()
    {
        parent::init();

        $this->modelService = new StockService;
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
            ]);
        } else {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);
        }
    }

    public function actionGetView()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = $out = Yii::$app->params['baseRes'];
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request;
            $gudang = $request->get('gudang');
            $id = $request->get('id', []); // Default array kosong
            $template = $request->get('template', 0);

            if (!$gudang) {
                throw new \yii\web\BadRequestHttpException("Gudang wajib diisi");
            }

            $out['data'] = $this->modelService->templateDataView($id, $gudang, $template);
        }

        return $out;
    }
}
