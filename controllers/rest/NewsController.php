<?php

namespace app\controllers\rest;

use app\models\rest\News;
use app\components\ActiveController;

class NewsController extends ActiveController
{
    public $modelClass = News::class;
}