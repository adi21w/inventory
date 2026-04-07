<?php

namespace api\components;

use Yii;
use yii\base\Behavior;
use yii\web\Application;
use yii\web\UnauthorizedHttpException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleware extends Behavior
{
    public $except = [];

    public function events()
    {
        return [
            Application::EVENT_BEFORE_REQUEST => 'checkToken',
        ];
    }

    public function checkToken()
    {
        $route = Yii::$app->request->pathInfo;

        foreach ($this->except as $exceptRoute) {
            if ($route && strpos($route, $exceptRoute) !== false) {
                return;
            }
        }

        $headers = Yii::$app->request->headers;
        $authHeader = $headers->get('Authorization');

        if (!$authHeader) {
            throw new \yii\web\UnauthorizedHttpException('Token tidak ditemukan');
        }

        $token = trim(str_replace('Bearer', '', $authHeader));
        $key = Yii::$app->params['jwtSecret'];

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $user = \common\models\User::findOne($decoded->uid);

            if (!$user) {
                throw new UnauthorizedHttpException('User tidak ditemukan');
            }

            Yii::$app->user->setIdentity($user);
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('Token tidak valid / expired');
        }
    }
}
