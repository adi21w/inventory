<?php

namespace api\components;

use Yii;
use yii\base\Behavior;
use yii\web\Application;

class BeforeRequest extends Behavior
{
    public function events()
    {
        return [
            Application::EVENT_BEFORE_REQUEST => 'handleRequest',
        ];
    }

    public function handleRequest()
    {
        $request = Yii::$app->request;

        $route = Yii::$app->requestedRoute;
        $accept = $request->headers->get('Accept');

        $allowedRoutes = [
            'site/login',
        ];

        if (in_array($route, $allowedRoutes)) {
            return;
        }

        if ($accept && strpos($accept, 'text/html') !== false) {

            // 🔥 kalau browser → render view
            if (!Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
                echo \Yii::$app->view->renderFile('@api/views/site/forbidden.php');
                Yii::$app->end();
            }

            // fallback JSON
            Yii::$app->response->statusCode = 403;
            Yii::$app->response->data = [
                'success' => false,
                'message' => 'Forbidden',
            ];
            Yii::$app->response->send();
            Yii::$app->end();
        }
    }
}
