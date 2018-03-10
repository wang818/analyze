<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 2018/1/3
 * Time: 下午6:26
 */

namespace app\models;

class Event extends \yii\db\ActiveRecord
{

    public static function tableName(){
        return "{{event}}";
    }
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'damai_id', 'yongle_id', 'piaoniu_id', 'xishiqu_id', 'tking_id', 'youpiao_id', 'start_time', 'end_time', 'is_index'], 'required'],
            [['id', 'damai_id', 'yongle_id', 'piaoniu_id', 'youpiao_id'], 'integer'],
            [['xishiqu_id', 'tking_id'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 200],
            [['note'], 'string', 'max' => 255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '项目名称',
            'damai_id' => '大麦ID',
            'yongle_id' => '永乐ID',
            'piaoniu_id' =>'票牛ID',
            'xishiqu_id' =>'西十区ID',
            'tking_id' =>'摩天轮ID',
            'youpiao_id' =>'有票ID',
            'start_time' =>'演出开始时间',
            'end_time' =>'演出结束时间',
            'is_index' =>'是否首页显示',
            'note' =>'备注',
        ];
    }
}