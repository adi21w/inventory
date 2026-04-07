<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Stock;

/**
 * TOrderRequestSearch represents the model behind the search form of `common\models\Stock`.
 */
class StockSearch extends Stock
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iProdukId', 'iGudangId'], 'safe'],
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
        $query = Stock::find()->alias('s');
        $query->joinWith([
            'product p',
            'warehouse g'
        ]);
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

        $query->where(['p.eDeleted' => 'Tidak']);
        $query->andWhere(['>', 's.iQty', 0]);
        // grid filtering conditions
        $query->andFilterWhere([
            'g.vNama' => $this->iGudangId,
        ]);

        $query->andFilterWhere(['like', 'p.vNama', $this->iProdukId]);

        return $dataProvider;
    }
}
