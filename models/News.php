<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property int $id
 * @property string $title Титул
 * @property string $content Контент
 * @property integer $created_at Создан
 * @property integer $updated_at Обновлён
 * @property int|null $status Статус
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%news}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'created_at'], 'required'],
            [['content'], 'string'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['title'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Титул',
            'content' => 'Контент',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлён',
            'status' => 'Статус',
        ];
    }

    public function getCreator()
    {
        return $this->hasOne(User::class, ['user_id' => 'id']);
    }
}
