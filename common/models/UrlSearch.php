<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UrlSearch represents the model behind the search form of `common\models\Url`.
 */
class UrlSearch extends Url
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'check_interval', 'active', 'user_agent_id', 'last_check', 'created_at', 'updated_at'], 'integer'],
            [['url_title', 'url', 'request_type', 'expected_response'], 'safe'],
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
        $query = Url::find();

        # Если роль текущего пользователя не "админ", то показываем только url, которые создал текущий пользователь.
        $userID = Yii::$app->user->id;
        $user = User::findOne(['id' => $userID]);
        if ($user->getRole() == USER::ROLE_USER)
        {
            $query->andWhere(['user_id' => $userID]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate())
        {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'active' => $this->active,
            'check_interval' => $this->check_interval,
            'user_agent_id' => $this->user_agent_id,
            'last_check' => $this->last_check,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'url_title', $this->url_title])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'request_type', $this->request_type])
            ->andFilterWhere(['like', 'expected_response', $this->expected_response]);

        return $dataProvider;
    }
}
