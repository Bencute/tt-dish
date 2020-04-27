<?php

namespace dish\model\ar;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * IngredientSearchQuery represents the model behind the search form of `dish\model\ar\Ingredient`.
 */
class IngredientSearchQuery extends Ingredient
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'enable'], 'integer'],
            [['name'], 'safe'],
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
        $query = Ingredient::find();

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
            'enable' => $this->enable,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
