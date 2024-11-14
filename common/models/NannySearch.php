<?php

namespace common\models;

use common\components\helpers\SearchQueryHelper;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NannySearch represents the model behind the search form of `common\models\Nanny`.
 */
final class NannySearch extends Nanny
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['child_name', 'child_profession', 'picture', 'image_vk', 'image_fb', 'token', 'created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with a search query applied
     *
     * @throws InvalidConfigException
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Nanny::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'child_name', $this->child_name])
            ->andFilterWhere(['like', 'child_profession', $this->child_profession])
            ->andFilterWhere(['like', 'picture', $this->picture])
            ->andFilterWhere(['like', 'image_vk', $this->image_vk])
            ->andFilterWhere(['like', 'image_fb', $this->image_fb])
            ->andFilterWhere(['like', 'token', $this->token]);

        // date filtering helper
        SearchQueryHelper::filterDataRange(['created_at', 'updated_at'], $this, $query);

        return $dataProvider;
    }
}
