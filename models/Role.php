<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string $name 角色名称
 * @property int $c_time 创建时间
 * @property int $u_time 修改时间
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_time', 'u_time'], 'integer'],
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
            'name' => '名称',
            'c_time' => '创建时间',
            'u_time' => '修改时间',
        ];
    }
}
