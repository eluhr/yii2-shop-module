<?php

namespace eluhr\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use eluhr\shop\models\Product as ProductModel;
use yii\helpers\VarDumper;

/**
 * ProductFinder provides the search() Method for`eluhr\shop\models\Product`.
 */
class ProductFinder extends Model
{
    public $tagIds;
    public $q;

    public $defaultOrder = ['rank' => SORT_ASC];

    /**
     * we need query as property to be able to access it in other Methods of this instance
     * @var \yii\db\ActiveQuery
     */
    protected $query;

    /**
     * internal cache Var for self::getAllModelIds
     *
     * @var false|array
     */
    protected $_allModelIds = false;

    protected $_availableModelIds = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tagIds'], 'integer', 'allowArray' => true],
            [['q'], 'string'],
        ];
    }

    public function formName()
    {
        return '';
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
        $this->query = $this->getBaseQuery();

        $dataProvider = new ActiveDataProvider([
            'query' => $this->query,
        ]);
        $sort = $dataProvider->getSort();
        $sort->defaultOrder = $this->defaultOrder;

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $this->query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->tagIds)) {
            $this->query->hasTagsAssigned($this->tagIds);
        }

        if (!empty($this->q)) {
            $this->query->fullTextSearch($this->q);
        }

        return $dataProvider;
    }


    protected function getBaseQuery()
    {
        return ProductModel::find()->online();
    }

    /**
     * get all Product Model IDs that are 'online' without filtering
     *
     * @return array
     */
    public function getAvailableModelIds()
    {
        if ($this->_availableModelIds === false) {
            $this->_availableModelIds = $this->getBaseQuery()->column();
        }
        return $this->_availableModelIds;
    }

    /**
     * get all ModelIds in the current finder context, which are allModels of this dataProvider()
     *
     * @return array|false
     */
    public function getAllModelIds()
    {
        if ($this->_allModelIds === false) {
            $query = clone $this->query;
            $query->limit(-1)->offset(-1)->asArray();
            $this->_allModelIds = $query->column();

        }
        return $this->_allModelIds;
    }
}
