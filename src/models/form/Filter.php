<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\models\form;

use yii\base\Model;
use yii\helpers\HtmlPurifier;

/**
 *
 * @property bool $isFiltered
 */
class Filter extends Model
{
    public $tag = [];
    public $q;

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [[
            'tag',
            'q'
        ],'safe'];
        $rules[] = [
            'q',
            'filter',
            'filter' => function ($value) {
                $value = strip_tags($value);
                return HtmlPurifier::process($value);
            }
        ];
        return $rules;
    }

    public function tagIds()
    {
        $tagIds = [];

        foreach ($this->tag as $tags) {
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    if (!empty($tag)) {
                        $tagIds[] = $tag;
                    }
                }
            }
        }

        return $tagIds;
    }

    public function getIsFiltered()
    {
        return !empty($this->tag) || !empty($this->q);
    }

    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['q'] = \Yii::t('shop', 'Suche');
        return $attributeLabels;
    }
}