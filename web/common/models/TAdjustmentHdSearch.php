<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TAdjustmentHd;

/**
 * TAdjustmentHdSearch represents the model behind the search form of `common\models\TAdjustmentHd`.
 */
class TAdjustmentHdSearch extends TAdjustmentHd
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vAdjNo', 'iGudangId', 'eKategori', 'dConfirm', 'eConfirm'], 'safe'],
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
    public function search($params, $tipe = 'PLUS')
    {
        $query = TAdjustmentHd::find()->alias('ap');
        $query->joinWith([
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

        $query->where(['ap.eDeleted' => 'Tidak', 'ap.eType' => $tipe]);
        // grid filtering conditions
        $query->andFilterWhere([
            'ap.iGudangId' => $this->iGudangId,
            'ap.eKategori' => $this->eKategori,
            'ap.eConfirm' => $this->eConfirm
        ]);

        $query->andFilterWhere(['like', 'ap.vAdjNo', $this->vAdjNo]);

        if (isset($this->dConfirm) && $this->dConfirm != '') {
            $date_explode = explode(" - ", $this->dConfirm);
            $date1 = trim($date_explode[0]);
            $date2 = trim($date_explode[1]);
            $query->andFilterWhere(['BETWEEN', 'ap.dConfirm', $date1, $date2]);
        }

        return $dataProvider;
    }
}
