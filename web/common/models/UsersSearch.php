<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Users;

/**
 * UsersSearch represents the model behind the search form of `frontend\models\Users`.
 */
class UsersSearch extends Users
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'username', 'number_phone'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Users::find()->alias('u');
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // $query->andWhere(['u.eDeleted' => 'Tidak']);
        // grid filtering conditions
        $query->andFilterWhere([
            'p.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'u.name', $this->name])
            ->andFilterWhere(['like', 'u.email', $this->email])
            ->andFilterWhere(['like', 'u.username', $this->username])
            ->andFilterWhere(['like', 'u.number_phone', $this->number_phone]);

        return $dataProvider;
    }
}
