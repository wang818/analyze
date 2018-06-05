<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AdminUser;

/**
 * AdminUserSearch represents the model behind the search form of `app\models\AdminUser`.
 */
class AdminUserSearch extends AdminUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'c_time', 'l_time', 'phone'], 'integer'],
            [['username', 'password', 'auth_key', 'access_token', 'memo'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AdminUser::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'c_time' => $this->c_time,
            'l_time' => $this->l_time,
            'phone' => $this->phone,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'memo', $this->memo]);

        return $dataProvider;
    }
}
