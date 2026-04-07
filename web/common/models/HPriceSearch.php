<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\HPrice;

/**
 * HPriceSearch represents the model behind the search form of `frontend\models\Dokumen`.
 */
class HPriceSearch extends HPrice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iProdukId'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = HPrice::find()->alias('hb');
        $query->joinWith(['product p']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'iId' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        // $query->andFilterWhere([
        // ]);

        $query->andFilterWhere(['like', 'p.vNama', $this->iProdukId]);

        return $dataProvider;
    }
}
