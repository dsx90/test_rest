<?php

use Faker\Factory;
use yii\db\Migration;

/**
 * Class m210126_202731_news
 */
class m210126_202731_init_data extends Migration
{
    private $user_id = 4;

    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $user = new \app\models\User([
            'username' => 'admin',
            'auth_key' => 'MnbUd7cNGR7XLoRmb4QJ3yPdridECqED',
            'password' => 'admin',
            'email' => 'admin@admin.com',
            'status' => 10,
        ]);

        if($user->save()){
            $this->user_id = $user->id;
            $this->newsBathInsert();
        }

        $this->newsBathInsert();

    }

    private function newsBathInsert()
    {
        $rows = [];
        $faker = Factory::create('ru_RU');

        for($i = 0; $i <= 100000; $i++)
        {
            $rows[] = [
                'title' => $faker->text(rand(30, 128)),
                'content' => $faker->text(rand(1000, 2000)),
                'status' => rand(0, 1),
                'user_id' => $this->user_id,
                'created_at' => $faker->unixTime('now')
            ];

            if(isset($rows[1000])){
                $this->batchInsert('news', array_keys($rows[0]), $rows);
                $rows = [];
            }
        }

    }
}
