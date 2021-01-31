<?php

namespace app\models\rest;

use http\Url;
use yii\helpers\StringHelper;
use yii\web\Link;
use yii\web\Linkable;

class News extends \app\models\News implements Linkable
{

    public function fields()
    {
        return [
            'title',
            'description' => function(self $model){
                return StringHelper::truncate($model->content, 100, '...');
            },
            'content',
            'created_at',
            'updated_at',
        ];
    }

    public function extraFields()
    {
        return [
            'creator'
        ];
    }

    public function getLinks()
    {
        return [
            Link::REL_SELF => \yii\helpers\Url::to(['news/view', 'id' => $this->id], true),
        ];
    }
}