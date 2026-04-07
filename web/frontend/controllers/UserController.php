<?php

namespace frontend\controllers;

use Yii;
// use Exception;
use yii\web\Controller;
use yii\filters\VerbFilter;
// use yii\web\NotFoundHttpException;
// use common\helper\Helper;
use common\services\UsersService;

/**
 * User controller
 */
class UserController extends Controller
{
    protected $modelService;

    public function init()
    {
        parent::init();

        $this->modelService = new UsersService;
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

    public function actionCreate()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = $out = Yii::$app->params['baseRes'];
        if (Yii::$app->request->isAjax) {
            $out = $this->modelService->createData(Yii::$app->request->post());
        }

        return $out;
    }

    public function actionUpdate($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = $out = Yii::$app->params['baseRes'];
        if (Yii::$app->request->isAjax) {
            $out = $this->modelService->updateData(Yii::$app->request->post(), $id);
        }

        return $out;
    }

    public function actionGetData($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = $out = Yii::$app->params['baseRes'];
        if (Yii::$app->request->isAjax) {
            $out = $this->modelService->detailData($id);
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

    public function actionPassword($id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = $out = Yii::$app->params['baseRes'];

        if (Yii::$app->request->isAjax && Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            if ($id == null) {
                $id = Yii::$app->user->id;
            }

            $out = $this->modelService->changePassword($id, $post['password']);
        }

        return $out;
    }
}
