<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Users;
use common\models\LoginForm;
use common\services\StockService;

/**
 * Site controller
 */
class SiteController extends Controller
{
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
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
        $model = new StockService();
        $stockGoods = $model->checkStock();
        return $this->render('index', [
            'stock' => $stockGoods
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $this->layout = 'login'; // pakai layout khusus login

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionProfile()
    {
        $user = Yii::$app->user->identity;
        $model = Users::findOne($user->id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save(false)) {
                Yii::$app->getSession()->setFlash('alert', [
                    'icon' => 'success',
                    'title' => 'Success',
                    'text' => 'Berhasil Update Profile',
                ]);
            } else {
                Yii::$app->getSession()->setFlash('alert', [
                    'icon' => 'error',
                    'title' => 'Failed',
                    'text' => 'Terjadi Kesalahan Saat Menyimpan Data',
                ]);
            }
            return $this->refresh();
        }

        return $this->render('profile', [
            'model' => $model
        ]);
    }
}
