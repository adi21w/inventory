<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TAdjustmentDt;

/**
 * TAdjustmentDtSearch represents the model behind the search form of `common\models\TAdjustmentDt`.
 */
class TAdjustmentDtSearch extends TAdjustmentDt
{
    /**
     * {@inheritdoc}
     */
    public $gudang, $kategori, $status, $date;
    public function rules()
    {
        return [
            [['vAdjNo', 'iProdukId', 'vBatch', 'iKemasanId', 'dExpired', 'gudang', 'kategori', 'status', 'date'], 'safe'],
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
        $query = TAdjustmentDt::find()->alias('ap');
        $query->joinWith([
            'header' => function ($q) {
                return $q->alias('h')->joinWith([
                    'warehouse w'
                ]);
            },
            'product p',
            'pack k'
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

        $query->where(['h.eDeleted' => 'Tidak', 'h.eType' => $tipe]);
        // grid filtering conditions
        $query->andFilterWhere([
            'w.vNama' => $this->gudang,
            'h.eKategori' => $this->kategori,
            'h.eConfirm' => $this->status,
            'k.vNama' => $this->iKemasanId
        ]);

        $query->andFilterWhere(['like', 'ap.vAdjNo', $this->vAdjNo]);

        if (isset($this->date) && $this->date != '') {
            $date_explode = explode(" - ", $this->date);
            $date1 = trim($date_explode[0]);
            $date2 = trim($date_explode[1]);
            $query->andFilterWhere(['BETWEEN', 'h.dConfirm', $date1, $date2]);
        }

        return $dataProvider;
    }
}
