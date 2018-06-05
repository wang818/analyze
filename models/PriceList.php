<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "price_list".
 *
 * @property int $id
 * @property string $price 票档价格
 * @property int $event_id 项目ID
 * @property int $session_id 场次ID
 * @property string $third_price_id 第三方场次ID
 * @property string $name 票档名称
 */
class PriceList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price'], 'number'],
            [['event_id', 'session_id'], 'integer'],
            [['third_price_id'], 'string', 'max' => 60],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price' => 'Price',
            'event_id' => 'Event ID',
            'session_id' => 'Session ID',
            'third_price_id' => 'Third Price ID',
            'name' => 'Name',
        ];
    }
}
