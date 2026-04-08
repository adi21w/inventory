<?php

namespace api\controllers;

use Yii;
use yii\rest\Controller;
use common\models\User;
use Firebase\JWT\JWT;
use yii\web\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class, // Atau \yii\filters\Cors
        ];

        return $behaviors;
    }

    public function actionLogin()
    {
        $request = Yii::$app->request;

        $username = $request->post('username');
        $password = $request->post('password');

        $user = User::findOne(['username' => $username]);

        if (!$user || !$user->validatePassword($password)) {
            throw new UnauthorizedHttpException('Username atau password salah');
        }

        $key = Yii::$app->params['jwtSecret'];

        $payload = [
            'iss' => 'inventory-api',
            'aud' => 'mobile-app',
            'iat' => time(),
            'exp' => time() + 3600, // 1 jam
            'uid' => $user->id,
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        return [
            'token' => $token,
        ];
    }

    // AuthController.php di Yii2
    public function actionProfile()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'message' => 'Success',
            'user' => \Yii::$app->user->identity,
        ];
    }
}
