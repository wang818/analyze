<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "session".
 *
 * @property int $id
 * @property string $name 场次名称
 * @property string $start_time 演出时间
 * @property int $event_id 项目ID
 * @property string $third_session_id 第三方场次ID
 * @property string $start_sell_time 开售时间
 * @property int $source 1：大麦2：永乐3：摩天轮4：有票5：票牛6：西十区
 */
class Session extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_time', 'source'], 'required'],
            [['start_time', 'start_sell_time'], 'safe'],
            [['event_id', 'source'], 'integer'],
            [['name', 'third_session_id'], 'string', 'max' => 255],
        ];
    }

    public function getEvent(){
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'start_time' => 'Start Time',
            'event_id' => 'Event ID',
            'third_session_id' => 'Third Session ID',
            'start_sell_time' => 'Start Sell Time',
            'source' => 'Source',
        ];
    }
}
