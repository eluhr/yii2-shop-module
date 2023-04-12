<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vat}}`.
 */
class m230224_120002_migrate_variant_vat_values_2_ref_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $knownVats = [];

        foreach (\eluhr\shop\models\Variant::find()->all() as $varModel) {
            $vatVal = $varModel->vat;
            if (!array_key_exists($vatVal, $knownVats)) {
                $vatModel = \eluhr\shop\models\Vat::find()->andWhere(['value' => $vatVal])->one();
                if (!$vatModel) {
                    $vatModel = new \eluhr\shop\models\Vat();
                    $vatModel->value = $vatVal;
                    $vatModel->desc = 'autocreated vat';
                    if ($vatModel->validate() && $vatModel->save()) {

                        $knownVats[$vatVal] = $vatModel;
                    } else {
                        throw new \yii\base\UnknownPropertyException('new vat model cannot be created ' . print_r($vatModel->getErrors(), 1));
                    }
                }
            }
            $varModel->vat_id = $knownVats[$vatVal]->id;
            if (!$varModel->save()){
                throw new \yii\base\UnknownPropertyException('variant model cannot be saved with new vat ref_id ' . print_r($varModel->getErrors(), 1));
            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
