<?php
/**
 *
 */

namespace eluhr\shop\components\behaviors;

use eluhr\shop\models\ActiveRecord;
use yii\base\Behavior;
use yii\base\ErrorException;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Add the eluhr\shop\components\traits\FiltersTrait trait to your class
 * @property ActiveRecord $junctionModel
 * @property string $typeColumnName
 * @property string $columnName
 * @property string $filterColumnName
 * @property string $primaryKey
*/
class FiltersBehavior extends Behavior
{
    public $junctionModel;
    public $typeColumnName;
    public $filterColumnName = 'filter_id';
    public $columnName = 'filterIds';
    public $primaryKey = 'id';

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'loadFilterIds',
            ActiveRecord::EVENT_AFTER_INSERT => 'updateFilterIds',
            ActiveRecord::EVENT_AFTER_UPDATE => 'updateFilterIds',
        ];
    }

    public function loadFilterIds()
    {
        $junctionModel = $this->junctionModel;
        $this->owner->{$this->columnName} = ArrayHelper::getColumn($junctionModel::findAll([$this->typeColumnName => $this->owner->{$this->primaryKey}]), $this->filterColumnName);
    }

    /**
     * @throws ErrorException
     * @throws Exception
     */
    public function updateFilterIds()
    {
        $junctionModel = $this->junctionModel;
        $transaction = Yii::$app->db->beginTransaction();
        $junctionModel::deleteAll([$this->typeColumnName => $this->owner->{$this->primaryKey}]);
        if (!empty($this->owner->{$this->columnName})) {
            foreach ($this->owner->{$this->columnName} as $filterId) {
                /** @var ActiveRecord $filterModel */
                $filterModel = new $junctionModel([
                    $this->typeColumnName => $this->owner->{$this->primaryKey},
                    $this->filterColumnName => $filterId
                ]);

                if (!$filterModel->save()) {
                    $transaction->rollBack();
                    throw new ErrorException(Yii::t('crud', 'Cannot attach filter'));
                }
            }
        }
        $transaction->commit();
    }
}
