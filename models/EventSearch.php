<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 2018/1/15
 * Time: 下午4:54
 */

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use app\models\Event;

class EventSearch extends Event
{
    /**
     * @return array 字段类型
     */
    public function rules()
    {
        return [
            [['id', 'damai_id', 'yongle_id', 'piaoniu_id', 'youpiao_id'], 'integer'],
            [['xishiqu_id', 'tking_id'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params){
        $query = Event::find();
        if (isset($params['id'])){
            $query->getBehavior($params['id']);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $dataProvider;
    }
}