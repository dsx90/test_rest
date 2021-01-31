<?php

namespace app\components;

use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\rest\Serializer;
use yii\web\Response;

class ActiveController extends \yii\rest\ActiveController
{
    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
        \Yii::$app->urlManager->enableStrictParsing = true;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotitator'] = [
            'class' => ContentNegotiator::class,
            'formatParam' => '_format',
            'formats' => [
                'json' => Response::FORMAT_JSON,
                'xml' => Response::FORMAT_XML
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                //HttpBasicAuth::class,
                HttpBearerAuth::class,
                QueryParamAuth::class,
            ]
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@']
                ]
            ]
        ];
        //$behaviors['rateLimiter']['enableRateLimitHeaders'] = true;
        return $behaviors;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        // проверить, имеет ли пользователь доступ к $action и $model
        // выбросить ForbiddenHttpException, если доступ следует запретить
        if ($action === 'update' || $action === 'delete') {
            if ($model->user_id !== \Yii::$app->user->id)
                throw new \yii\web\ForbiddenHttpException(sprintf('You can only %s articles that you\'ve created.', $action));
        }
    }

    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items',
    ];
}