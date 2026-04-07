<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Warehouses;

/**
 * WarehousesSearch represents the model behind the search form of `frontend\models\Warehouses`.
 */
class WarehousesSearch extends Warehouses
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vNama', 'iStatus', 'eStock'], 'safe'],
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
        $query = Warehouses::find()->alias('mw');
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'iId' => SORT_DESC,
                ],
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andWhere(['mw.eDeleted' => 'Tidak']);
        // grid filtering conditions
        $query->andFilterWhere([
            'mw.iStatus' => $this->iStatus,
            'mw.eStock' => $this->eStock,
        ]);

        $query->andFilterWhere(['like', 'mw.vNama', $this->vNama]);

        return $dataProvider;
    }
}
