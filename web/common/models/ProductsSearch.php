<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Products;

/**
 * ProductsSearch represents the model behind the search form of `frontend\models\Products`.
 */
class ProductsSearch extends Products
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vNama', 'iStatus', 'iKemasanBesarId', 'iKemasanKecilId', 'iRak'], 'safe'],
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
        $query = Products::find()->alias('p');
        $query->joinWith([
            'kemasan k',
            'kemasankecil kk',
            'rak r'
        ]);
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
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

        $query->andWhere(['p.eDeleted' => 'Tidak']);
        // grid filtering conditions
        $query->andFilterWhere([
            'p.iStatus' => $this->iStatus,
            'k.vNama' => $this->iKemasanBesarId,
            'kk.vNama'   => $this->iKemasanKecilId,
            'r.vNama' => $this->iRak
        ]);

        $query->andFilterWhere(['like', 'p.vNama', $this->vNama]);

        return $dataProvider;
    }
}
