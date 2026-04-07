<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TrHist;

/**
 * TOrderRequestSearch represents the model behind the search form of `common\models\TrHist`.
 */
class TrHistSearch extends TrHist
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vTr_Number', 'eTr_Type', 'iTr_ProdukId', 'vTr_Batch', 'iTr_GudangId', 'dTr_Date', 'iTr_Kemasan'], 'safe'],
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
        $query = TrHist::find()->alias('tr');
        $query->joinWith([
            'product p',
            'warehouse w'
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

        // $query->where(['p.eDeleted' => 'Tidak']);
        // grid filtering conditions
        $query->andFilterWhere([
            'tr.eTr_Type' => $this->eTr_Type,
            'tr.vTr_Batch' => $this->vTr_Batch,
            'w.vNama' => $this->iTr_GudangId,
        ]);

        $query->andFilterWhere(['like', 'tr.vTr_Number', $this->vTr_Number])
            ->andFilterWhere(['like', 'p.vNama', $this->iTr_ProdukId]);

        if (isset($this->dTr_Date) && $this->dTr_Date != '') {
            $date_explode = explode(" - ", $this->dTr_Date);
            $date1 = trim($date_explode[0]);
            $date2 = trim($date_explode[1]);
            $query->andFilterWhere(['BETWEEN', 'tr.dTr_Date', $date1, $date2]);
        }

        return $dataProvider;
    }
}
